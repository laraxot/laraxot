<?php

declare(strict_types=1);

namespace Themes\Sixteen\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Themes\Sixteen\Actions\SpidAuthAction;
use Themes\Sixteen\Events\SpidAuthenticated;
use Themes\Sixteen\Events\SpidLoggedOut;
use Themes\Sixteen\Models\User;

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
            $returnUrl = $request->query('return_url', route('dashboard'));

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
            $returnUrl = Session::pull('spid.return_url', route('dashboard'));

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
            $userData = Session::get('spid.user_data');
            $provider = Session::get('spid.provider');

            if ($user && $userData && $provider) {
                // Se abbiamo i dati per il Single Logout, usiamoli
                if (isset($userData['name_id']) && isset($userData['session_index'])) {
                    Log::info('SPID logout initiated', [
                        'user_id' => $user->id,
                        'provider' => $provider,
                    ]);

                    $logoutUrl = $this->spidService->getLogoutUrl(
                        $provider,
                        $userData['name_id'],
                        $userData['session_index']
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

            if ($user && $userData) {
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
            // Processa la richiesta SLO
            $logoutRequest = $request->input('SAMLRequest');
            $relayState = $request->input('RelayState');

            Log::info('SPID SLO received', [
                'relay_state' => $relayState,
                'user_id' => Auth::id(),
            ]);

            // Effettua logout se l'utente è loggato
            if (Auth::check()) {
                $user = Auth::user();
                $userData = Session::get('spid.user_data', []);

                Auth::logout();
                $this->spidService->logout();
                Session::invalidate();

                event(new SpidLoggedOut($user, $userData));
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
     * Trova o crea un utente basato sui dati SPID
     */
    protected function findOrCreateUser(array $attributes): User
    {
        $fiscalCode = $attributes['fiscal_code'];

        if (empty($fiscalCode)) {
            throw new \Exception('Codice fiscale mancante nei dati SPID');
        }

        // Cerca utente per codice fiscale
        $user = User::where('fiscal_code', $fiscalCode)->first();

        if ($user) {
            // Aggiorna i dati se necessario
            $this->updateUserFromSpid($user, $attributes);

            return $user;
        }

        // Crea nuovo utente
        return $this->createUserFromSpid($attributes);
    }

    /**
     * Crea un nuovo utente dai dati SPID
     */
    protected function createUserFromSpid(array $attributes): User
    {
        $userData = [
            'name' => $attributes['name'],
            'surname' => $attributes['surname'],
            'email' => $attributes['email'],
            'fiscal_code' => $attributes['fiscal_code'],
            'birth_date' => $attributes['birth_date'],
            'birth_place' => $attributes['birth_place'],
            'gender' => $attributes['gender'],
            'mobile_phone' => $attributes['mobile'],
            'address' => $attributes['address'],
            'spid_provider' => $attributes['provider'],
            'auth_method' => 'spid',
            'email_verified_at' => $attributes['email'] ? now() : null,
        ];

        // Genera email temporanea se mancante
        if (empty($userData['email'])) {
            $userData['email'] = 'spid.'.$attributes['fiscal_code'].'@noemail.local';
        }

        return User::create($userData);
    }

    /**
     * Aggiorna un utente esistente con i dati SPID
     */
    protected function updateUserFromSpid(User $user, array $attributes): void
    {
        $updateData = [];

        // Aggiorna campi se diversi
        if ($user->name !== $attributes['name']) {
            $updateData['name'] = $attributes['name'];
        }

        if ($user->surname !== $attributes['surname']) {
            $updateData['surname'] = $attributes['surname'];
        }

        if ($attributes['email'] && $user->email !== $attributes['email']) {
            $updateData['email'] = $attributes['email'];
            $updateData['email_verified_at'] = now();
        }

        if ($attributes['mobile'] && $user->mobile_phone !== $attributes['mobile']) {
            $updateData['mobile_phone'] = $attributes['mobile'];
        }

        // Aggiorna provider se diverso
        if ($user->spid_provider !== $attributes['provider']) {
            $updateData['spid_provider'] = $attributes['provider'];
        }

        // Aggiorna ultimo accesso
        $updateData['last_login_at'] = now();

        if (! empty($updateData)) {
            $user->update($updateData);
        }
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
               '  <saml:Issuer>'.config('spid.entity_id').'</saml:Issuer>'.PHP_EOL.
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
               '  <saml:Issuer>'.config('spid.entity_id').'</saml:Issuer>'.PHP_EOL.
               '  <samlp:Status>'.PHP_EOL.
               '    <samlp:StatusCode Value="urn:oasis:names:tc:SAML:2.0:status:Responder"/>'.PHP_EOL.
               '  </samlp:Status>'.PHP_EOL.
               '</samlp:LogoutResponse>';
    }
}
