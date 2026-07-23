<?php

declare(strict_types=1);

namespace Themes\Sixteen\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Themes\Sixteen\Actions\CieAuthAction;
use Themes\Sixteen\Events\CieAuthenticated;
use Themes\Sixteen\Events\CieLoggedOut;
use Themes\Sixteen\Models\User;

/**
 * Controller per l'autenticazione CIE.
 *
 * Gestisce il flusso completo di autenticazione CIE secondo le specifiche AGID
 */
class CieAuthController extends Controller
{
    public function __construct(
        protected CieAuthAction $cieService
    ) {}

    /**
     * Reindirizza a CIE per l'autenticazione web.
     */
    public function login(Request $request): RedirectResponse
    {
        try {
            $returnUrl = $request->query('return_url', route('dashboard'));

            Log::info('CIE login initiated', [
                'method' => 'web',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $loginUrl = $this->cieService->getLoginUrl((string) $returnUrl);

            return redirect()->to($loginUrl);
        } catch (\Exception $e) {
            Log::error('CIE login error', [
                'method' => 'web',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Errore durante l\'avvio dell\'autenticazione CIE. Riprova più tardi.');
        }
    }

    /**
     * Reindirizza all'app CieID mobile.
     */
    public function mobileLogin(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $returnUrl = $request->query('return_url', route('dashboard'));

            Log::info('CIE mobile login initiated', [
                'method' => 'mobile',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $mobileUrl = $this->cieService->getMobileLoginUrl((string) $returnUrl);

            // Se è una richiesta AJAX, ritorna JSON per gestire il deep linking
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'mobile_url' => $mobileUrl,
                    'fallback_url' => $this->cieService->getLoginUrl((string) $returnUrl),
                    'timeout' => config('cie.mobile.deep_link_timeout', 10) * 1000, // millisecondi
                ]);
            }

            // Redirect diretto per browser
            return redirect()->to($mobileUrl);
        } catch (\Exception $e) {
            Log::error('CIE mobile login error', [
                'method' => 'mobile',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Errore durante l\'avvio dell\'autenticazione CIE mobile.',
                    'fallback_url' => route('cie.login', ['return_url' => $request->query('return_url')]),
                ], 500);
            }

            return redirect()->route('login')
                ->with('error', 'Errore durante l\'avvio dell\'autenticazione CIE mobile. Riprova più tardi.');
        }
    }

    /**
     * Gestisce il callback OAuth2 da CIE.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            // Processa la response OAuth2
            $userAttributes = $this->cieService->processCallback($request);

            // Trova o crea l'utente
            $user = $this->findOrCreateUser($userAttributes);

            // Effettua il login
            Auth::login($user, true);

            // Salva i dati CIE in sessione
            Session::put('cie.authenticated', true);
            Session::put('cie.user_data', $userAttributes);

            // Trigger evento
            event(new CieAuthenticated($user, $userAttributes));

            Log::info('CIE authentication completed', [
                'user_id' => $user->id,
                'auth_method' => $userAttributes['auth_method'],
                'fiscal_code' => $userAttributes['fiscal_code'],
            ]);

            // Redirect all'URL di ritorno
            $returnUrl = Session::pull('cie.return_url', route('dashboard'));

            return redirect()->to((string) $returnUrl)
                ->with('success', 'Autenticazione CIE completata con successo.');
        } catch (\Exception $e) {
            Log::error('CIE callback error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Pulisci la sessione in caso di errore
            $this->cieService->logout();

            return redirect()->route('login')
                ->with('error', 'Errore durante l\'autenticazione CIE: '.$e->getMessage());
        }
    }

    /**
     * Gestisce il logout CIE.
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $userData = Session::get('cie.user_data');
            $returnUrl = $request->query('return_url', route('home'));

            if ($user && $userData) {
                Log::info('CIE logout initiated', [
                    'user_id' => $user->id,
                    'auth_method' => $userData['auth_method'] ?? 'cie',
                ]);

                // Trigger evento prima del logout
                event(new CieLoggedOut($user, $userData));
            }

            // Effettua logout locale
            Auth::logout();
            $this->cieService->logout();
            Session::invalidate();
            Session::regenerateToken();

            // Se configurato, usa il logout endpoint CIE
            if (config('cie.logout_endpoint_enabled', false)) {
                $logoutUrl = $this->cieService->getLogoutUrl((string) $returnUrl);

                return redirect()->to($logoutUrl);
            }

            return redirect()->to((string) $returnUrl)
                ->with('success', 'Logout effettuato con successo.');
        } catch (\Exception $e) {
            Log::error('CIE logout error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Forza logout locale in caso di errore
            Auth::logout();
            $this->cieService->logout();
            Session::invalidate();
            Session::regenerateToken();

            return redirect()->route('home')
                ->with('warning', 'Logout locale completato.');
        }
    }

    /**
     * Trova o crea un utente basato sugli attributi CIE.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function findOrCreateUser(array $attributes): User
    {
        $user = User::where('fiscal_code', $attributes['fiscal_code'])->first();

        if (! $user) {
            $user = User::create([
                'name' => $attributes['given_name'].' '.$attributes['family_name'],
                'email' => $attributes['email'] ?? $attributes['fiscal_code'].'@cie.internal',
                'password' => bcrypt(str_random(16)),
                'fiscal_code' => $attributes['fiscal_code'],
                'given_name' => $attributes['given_name'],
                'family_name' => $attributes['family_name'],
                'birth_date' => $attributes['date_of_birth'],
            ]);
        }

        $this->updateUserFromCie($user, $attributes);

        return $user;
    }

    /**
     * Aggiorna i dati dell'utente con le informazioni CIE più recenti.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function updateUserFromCie(User $user, array $attributes): void
    {
        $updateData = [];

        if (isset($attributes['auth_method'])) {
            $updateData['auth_method'] = 'cie';
            $updateData['cie_provider'] = 'cie';
        }

        // Aggiorna ultimo accesso
        $updateData['last_login_at'] = now();

        if (! empty($updateData)) {
            $user->update($updateData);
        }
    }
}
