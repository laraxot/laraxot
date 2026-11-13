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
use Themes\Sixteen\Events\SpidAuthenticated;
use Themes\Sixteen\Events\SpidLoggedOut;
use Themes\Sixteen\Models\User;
use Themes\Sixteen\Actions\SpidAuthAction;

/**
 * Controller per l'autenticazione SPID.
 *
 * Gestisce il flusso completo di autenticazione SPID secondo le specifiche AGID
 */
class SpidAuthController extends Controller
{
    public function __construct(
        protected SpidAuthAction $spidService
    ) {
    }

    /**
     * Reindirizza al provider SPID per l'autenticazione.
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

            $loginUrl = $this->spidService->getLoginUrl($provider, $level, (string) $returnUrl);

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
     * Gestisce il callback dal provider SPID.
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

            return redirect()->to((string) $returnUrl)
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
     * Gestisce il logout SPID.
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $userData = Session::get('spid.user_data');
            $provider = Session::get('spid.provider');

            if ($user && $userData && $provider) {
                // Se abbiamo i dati per il Single Logout, usiamoli
                if (isset($userData['name_id'], $userData['session_index'])) {
                    Log::info('SPID logout initiated', [
                        'user_id' => $user->id,
                        'provider' => $provider,
                    ]);

                    $logoutUrl = $this->spidService->getLogoutUrl(
                        (string) $provider,
                        (string) $userData['name_id'],
                        (string) $userData['session_index']
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
     * Gestisce il Single Logout (SLO) dal provider SPID.
     */
    public function singleLogout(Request $request): Response
    {
        try {
            // Processa la richiesta SLO
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

                if ($user && is_array($userData)) {
                    event(new SpidLoggedOut($user, $userData));
                }
            }

            // Genera response SLO per il provider
            return $this->spidService->generateSloResponse($request);
        } catch (\Exception $e) {
            Log::error('SPID SLO error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('Errore durante il Single Logout', 500);
        }
    }

    /**
     * Trova o crea un utente basato sugli attributi SPID.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function findOrCreateUser(array $attributes): User
    {
        $user = User::where('fiscal_code', $attributes['fiscal_code'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $attributes['given_name'].' '.$attributes['family_name'],
                'email' => $attributes['email'] ?? $attributes['fiscal_code'].'@spid.internal',
                'password' => bcrypt(str_random(16)),
                'fiscal_code' => $attributes['fiscal_code'],
                'given_name' => $attributes['given_name'],
                'family_name' => $attributes['family_name'],
                'birth_date' => $attributes['date_of_birth'],
            ]);
        }

        $this->updateUserFromSpid($user, $attributes);

        return $user;
    }

    /**
     * Aggiorna i dati dell'utente con le informazioni SPID più recenti.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function updateUserFromSpid(User $user, array $attributes): void
    {
        $updateData = [];

        if (isset($attributes['provider'])) {
            $updateData['auth_method'] = 'spid';
            $updateData['spid_provider'] = $attributes['provider'];
        }

        // Aggiorna ultimo accesso
        $updateData['last_login_at'] = now();

        if (! empty($updateData)) {
            $user->update($updateData);
        }
    }
}
