<?php

declare(strict_types=1);

namespace Themes\Sixteen\Http\Controllers;

use Modules\User\Actions\Auth\GetPostLoginUrlAction;
use Modules\User\Actions\Auth\UpsertUserFromDigitalIdentityAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\Xot\Contracts\UserContract;
use Themes\Sixteen\Actions\SpidAuthAction;
use Themes\Sixteen\Actions\Auth\SanitizeReturnUrlAction;
use Themes\Sixteen\Events\SpidAuthenticated;
use Themes\Sixteen\Events\SpidLoggedOut;

/**
 * Controller per l'autenticazione SPID
 *
 * Gestisce il flusso completo di autenticazione SPID secondo le specifiche AGID
 */
class SpidAuthController extends Controller
{
    public function __construct(
        protected SpidAuthAction $spidService
    ) {}

    /**
     * Reindirizza al provider SPID per l'autenticazione
     */
    public function login(Request $request, string $provider): RedirectResponse
    {
        try {
            $level = (int) $request->query('level', 2);
            $fallbackUrl = app(GetPostLoginUrlAction::class)->execute();
            $returnUrl = app(SanitizeReturnUrlAction::class)->execute($request, $request->query('return_url'), $fallbackUrl);

            // Valida il provider
            $providers = $this->spidService->getProviders();
            if (! isset($providers[$provider])) {
                throw new \InvalidArgumentException("Provider SPID '{$provider}' non supportato");
            }

            // Valida il livello SPID
            if (! in_array($level, [1, 2, 3])) {
                throw new \InvalidArgumentException("Livello SPID non valido: {$level}");
            }

            Log::info('SPID login initiated', [
                'provider' => $provider,
                'level' => $level,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $loginUrl = $this->spidService->getLoginUrl($provider, $level, $returnUrl);

            return redirect()->to($loginUrl);
        } catch (\Exception $e) {
            Log::error('SPID login error', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Errore durante l\'avvio dell\'autenticazione SPID. Riprova più tardi.');
        }
    }

    /**
     * Gestisce il callback dal provider SPID
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            // Processa la response SAML
            $userAttributes = $this->spidService->processCallback($request);

            // Trova o crea l'utente
            $user = $this->findOrCreateUser($userAttributes);

            // Effettua il login
            Auth::login($user, true);

            // Salva i dati SPID in sessione
            Session::put('spid.authenticated', true);
            Session::put('spid.user_data', $userAttributes);

            // Trigger evento
            event(new SpidAuthenticated($user, $userAttributes));

            Log::info('SPID authentication completed', [
                'user_id' => $user->id,
                'provider' => $userAttributes['provider'],
                'fiscal_code' => $userAttributes['fiscal_code'],
            ]);

            // Redirect all'URL di ritorno
            $returnUrl = Session::pull('spid.return_url');
            $returnUrl = app(SanitizeReturnUrlAction::class)->execute($request, $returnUrl, app(GetPostLoginUrlAction::class)->execute());

            return redirect()->to($returnUrl)
                ->with('success', 'Autenticazione SPID completata con successo.');
        } catch (\Exception $e) {
            Log::error('SPID callback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Pulisci la sessione in caso di errore
            $this->spidService->logout();

            return redirect()->route('login')
                ->with('error', 'Errore durante l\'autenticazione SPID: '.$e->getMessage());
        }
    }

    /**
     * Gestisce il logout SPID
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $sessionUserData = Session::get('spid.user_data');
            $userData = is_array($sessionUserData) ? $sessionUserData : null;
            $sessionProvider = Session::get('spid.provider');
            $provider = is_string($sessionProvider) ? $sessionProvider : null;

            if ($user !== null && $userData !== null && $provider !== null) {
                // Se abbiamo i dati per il Single Logout, usiamoli
                $nameId = $userData['name_id'] ?? null;
                $sessionIndex = $userData['session_index'] ?? null;

                if (is_string($nameId) && is_string($sessionIndex)) {
                    Log::info('SPID logout initiated', [
                        'user_id' => $user->id,
                        'provider' => $provider,
                    ]);

                    $logoutUrl = $this->spidService->getLogoutUrl(
                        $provider,
                        $nameId,
                        $sessionIndex
                    );

                    // Effettua logout locale
                    Auth::logout();
                    $this->spidService->logout();
                    Session::invalidate();
                    Session::regenerateToken();

                    // Trigger evento
                    event(new SpidLoggedOut($user, $userData));

                    // Redirect al logout SPID
                    return redirect()->to($logoutUrl);
                }
            }

            // Fallback a logout locale se SLO non disponibile
            Auth::logout();
            $this->spidService->logout();
            Session::invalidate();
            Session::regenerateToken();

            if ($user !== null && $userData !== null) {
                event(new SpidLoggedOut($user, $userData));
            }

            return redirect()->route('home')
                ->with('success', 'Logout effettuato con successo.');
        } catch (\Exception $e) {
            Log::error('SPID logout error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Forza logout locale in caso di errore
            Auth::logout();
            $this->spidService->logout();
            Session::invalidate();
            Session::regenerateToken();

            return redirect()->route('home')
                ->with('warning', 'Logout locale completato. Potrebbero essere necessarie operazioni aggiuntive.');
        }
    }

    /**
     * Gestisce il Single Logout (SLO) dal provider SPID
     */
    public function singleLogout(Request $request): Response
    {
        try {
            // Processa la richiesta SLO (la SAMLRequest non viene ancora interpretata: oggi si registra e si chiude la sessione)
            $relayState = $request->string('RelayState')->toString();

            Log::info('SPID SLO received', [
                'relay_state' => $relayState,
                'user_id' => Auth::id(),
            ]);

            // Effettua logout se l'utente è loggato
            if (Auth::check()) {
                $user = Auth::user();
                $sessionUserData = Session::get('spid.user_data', []);
                $userData = is_array($sessionUserData) ? $sessionUserData : [];

                Auth::logout();
                $this->spidService->logout();
                Session::invalidate();

                if ($user !== null) {
                    event(new SpidLoggedOut($user, $userData));
                }
            }

            // Genera response SLO
            $sloResponse = $this->generateSloResponse($relayState);

            return response($sloResponse)
                ->header('Content-Type', 'text/xml');
        } catch (\Exception $e) {
            Log::error('SPID SLO error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Response di errore
            $errorResponse = $this->generateSloErrorResponse();

            return response($errorResponse, 500)
                ->header('Content-Type', 'text/xml');
        }
    }

    /**
     * Fornisce i metadata SAML del Service Provider
     */
    public function metadata(): Response
    {
        try {
            $metadata = $this->spidService->getMetadata();

            return response($metadata)
                ->header('Content-Type', 'application/samlmetadata+xml')
                ->header('Content-Disposition', 'inline; filename="metadata.xml"');
        } catch (\Exception $e) {
            Log::error('SPID metadata generation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            abort(500, 'Errore nella generazione del metadata');
        }
    }

    /**
     * Trova o crea l'utente dai dati SPID (logica condivisa con l'altro metodo in UpsertUserFromDigitalIdentityAction).
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function findOrCreateUser(array $attributes): UserContract
    {
        $provider = $attributes['provider'] ?? null;
        $email = $attributes['email'] ?? null;

        // SPID: l'email e' un attributo verificato dall'identity provider.
        return app(UpsertUserFromDigitalIdentityAction::class)->execute(
            'spid',
            $attributes,
            emailVerified: is_string($email) && $email !== '',
            identityProvider: is_string($provider) && $provider !== '' ? $provider : null,
        );
    }

    /**
     * Genera risposta SLO di successo
     */
    protected function generateSloResponse(string $relayState): string
    {
        $responseId = 'res_'.bin2hex(random_bytes(16));
        $issueInstant = gmdate('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
               '<samlp:LogoutResponse xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"'.PHP_EOL.
               '                      xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion"'.PHP_EOL.
               '                      ID="'.$responseId.'"'.PHP_EOL.
               '                      Version="2.0"'.PHP_EOL.
               '                      IssueInstant="'.$issueInstant.'">'.PHP_EOL.
               '  <saml:Issuer>'.config()->string('spid.entity_id').'</saml:Issuer>'.PHP_EOL.
               '  <samlp:Status>'.PHP_EOL.
               '    <samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Success"/>'.PHP_EOL.
               '  </samlp:Status>'.PHP_EOL.
               '</samlp:LogoutResponse>';
    }

    /**
     * Genera risposta SLO di errore
     */
    protected function generateSloErrorResponse(): string
    {
        $responseId = 'res_'.bin2hex(random_bytes(16));
        $issueInstant = gmdate('Y-m-d\TH:i:s\Z');

        return '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
               '<samlp:LogoutResponse xmlns:samlp="urn:oasis:names:tc:SAML:2.0:protocol"'.PHP_EOL.
               '                      xmlns:saml="urn:oasis:names:tc:SAML:2.0:assertion"'.PHP_EOL.
               '                      ID="'.$responseId.'"'.PHP_EOL.
               '                      Version="2.0"'.PHP_EOL.
               '                      IssueInstant="'.$issueInstant.'">'.PHP_EOL.
               '  <saml:Issuer>'.config()->string('spid.entity_id').'</saml:Issuer>'.PHP_EOL.
               '  <samlp:Status>'.PHP_EOL.
               '    <samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Responder"/>'.PHP_EOL.
               '  </samlp:Status>'.PHP_EOL.
               '</samlp:LogoutResponse>';
    }
}
