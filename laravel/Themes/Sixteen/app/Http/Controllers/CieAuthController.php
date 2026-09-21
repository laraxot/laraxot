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
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Themes\Sixteen\Actions\CieAuthAction;
use Themes\Sixteen\Events\CieAuthenticated;
use Themes\Sixteen\Events\CieLoggedOut;

/**
 * Controller per l'autenticazione CIE
 *
 * Gestisce il flusso completo di autenticazione CIE secondo le specifiche AGID
 */
class CieAuthController extends Controller
{
    public function __construct(
        protected CieAuthAction $cieService
    ) {}

    /**
     * Reindirizza a CIE per l'autenticazione web
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

            $loginUrl = $this->cieService->getLoginUrl($returnUrl);

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
     * Reindirizza all'app CieID mobile
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

            $mobileUrl = $this->cieService->getMobileLoginUrl($returnUrl);

            // Se è una richiesta AJAX, ritorna JSON per gestire il deep linking
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'mobile_url' => $mobileUrl,
                    'fallback_url' => $this->cieService->getLoginUrl($returnUrl),
                    'timeout' => config()->integer('cie.mobile.deep_link_timeout', 10) * 1000, // millisecondi
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
     * Gestisce il callback OAuth2 da CIE
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
            $returnUrl = Session::pull('cie.return_url');
            $returnUrl = is_string($returnUrl) && $returnUrl !== '' ? $returnUrl : route('dashboard');

            return redirect()->to($returnUrl)
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
     * Gestisce il logout CIE
     */
    public function logout(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $sessionUserData = Session::get('cie.user_data');
            $userData = is_array($sessionUserData) ? $sessionUserData : null;
            $returnUrl = $request->query('return_url', route('home'));

            if ($user !== null && $userData !== null) {
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
                $logoutUrl = $this->cieService->getLogoutUrl($returnUrl);

                return redirect()->to($logoutUrl);
            }

            return redirect()->to($returnUrl)
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
     * Rinnova l'access token usando il refresh token
     */
    public function refresh(Request $request): JsonResponse
    {
        try {
            if (! $this->cieService->isAuthenticated()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Utente non autenticato con CIE',
                ], 401);
            }

            $tokenData = $this->cieService->refreshToken();

            if (! $tokenData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Impossibile rinnovare il token',
                ], 400);
            }

            Log::info('CIE token refreshed', [
                'user_id' => Auth::id(),
                'expires_in' => $tokenData['expires_in'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'expires_in' => $tokenData['expires_in'] ?? null,
                'token_type' => $tokenData['token_type'] ?? 'Bearer',
            ]);
        } catch (\Exception $e) {
            Log::error('CIE token refresh error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Errore nel rinnovare il token',
            ], 500);
        }
    }

    /**
     * Fornisce informazioni sullo stato dell'autenticazione CIE
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $isAuthenticated = $this->cieService->isAuthenticated();
            $userData = $isAuthenticated ? $this->cieService->getAuthenticatedUser() : null;

            return response()->json([
                'authenticated' => $isAuthenticated,
                'provider' => 'cie',
                'auth_method' => $userData['auth_method'] ?? null,
                'user_data' => $userData ? [
                    'name' => $userData['name'],
                    'surname' => $userData['surname'],
                    'fiscal_code' => $userData['fiscal_code'],
                    'auth_time' => $userData['auth_time'] ?? null,
                ] : null,
                'config_status' => $this->cieService->isConfigured(),
            ]);
        } catch (\Exception $e) {
            Log::error('CIE status check error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'authenticated' => false,
                'error' => 'Errore nel verificare lo stato CIE',
            ], 500);
        }
    }

    /**
     * Endpoint per debugging (solo in sviluppo)
     */
    public function debug(Request $request): JsonResponse
    {
        if (! config('app.debug') || ! app()->environment(['local', 'development'])) {
            abort(404);
        }

        $authUser = Auth::user();

        return response()->json([
            'config_info' => $this->cieService->getConfigInfo(),
            'session_data' => [
                'authenticated' => Session::get('cie.authenticated'),
                'has_user_data' => Session::has('cie.user_data'),
                'has_access_token' => Session::has('cie.access_token'),
                'state' => Session::get('cie.state'),
                'auth_method' => Session::get('cie.auth_method'),
            ],
            'auth_user' => $authUser !== null ? [
                'id' => $authUser->id,
                'email' => $authUser->email,
                'fiscal_code' => $authUser->getAttribute('fiscal_code'),
            ] : null,
        ]);
    }

    /**
     * Trova o crea un utente basato sui dati CIE
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function findOrCreateUser(array $attributes): UserContract
    {
        $fiscalCode = $attributes['fiscal_code'] ?? null;

        if (! is_string($fiscalCode) || $fiscalCode === '') {
            throw new \Exception('Codice fiscale mancante nei dati CIE');
        }

        // Cerca utente per codice fiscale
        $userClass = XotData::make()->getUserClass();
        $user = $userClass::where('fiscal_code', $fiscalCode)->first();

        if ($user instanceof UserContract) {
            // Aggiorna i dati se necessario
            $this->updateUserFromCie($user, $attributes);

            return $user;
        }

        if ($user !== null) {
            Log::error('CIE: existing user record does not implement UserContract', [
                'user_class' => $user::class,
                'fiscal_code' => $fiscalCode,
            ]);
        }

        // Crea nuovo utente
        return $this->createUserFromCie($attributes);
    }

    /**
     * Crea un nuovo utente dai dati CIE
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function createUserFromCie(array $attributes): UserContract
    {
        $fiscalCode = $attributes['fiscal_code'] ?? null;

        if (! is_string($fiscalCode) || $fiscalCode === '') {
            throw new \Exception('Codice fiscale mancante nei dati CIE');
        }

        $emailVerified = (bool) ($attributes['email_verified'] ?? false);
        $email = $attributes['email'] ?? null;

        $userData = [
            'name' => $attributes['name'] ?? null,
            'surname' => $attributes['surname'] ?? null,
            'email' => is_string($email) ? $email : null,
            'fiscal_code' => $fiscalCode,
            'birth_date' => $attributes['birth_date'] ?? null,
            'birth_place' => $attributes['birth_place'] ?? null,
            'gender' => $attributes['gender'] ?? null,
            'phone' => $attributes['phone'] ?? null,
            'address' => $attributes['address'] ?? null,
            'cie_provider' => 'cie',
            'auth_method' => 'cie',
            'email_verified_at' => $emailVerified ? now() : null,
            'phone_verified_at' => ($attributes['phone_verified'] ?? false) ? now() : null,
        ];

        // Genera email temporanea se mancante o non verificata
        if (empty($userData['email']) || ! $emailVerified) {
            $userData['email'] = 'cie.'.$fiscalCode.'@noemail.local';
            $userData['email_verified_at'] = null;
        }

        $userClass = XotData::make()->getUserClass();
        $user = $userClass::create($userData);

        if (! $user instanceof UserContract) {
            throw new \Exception('La classe utente configurata non implementa UserContract');
        }

        return $user;
    }

    /**
     * Aggiorna un utente esistente con i dati CIE
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function updateUserFromCie(UserContract $user, array $attributes): void
    {
        $updateData = [];

        // Aggiorna campi se diversi e più recenti
        if ($user->name !== $attributes['name']) {
            $updateData['name'] = $attributes['name'];
        }

        if ($user->getAttribute('surname') !== $attributes['surname']) {
            $updateData['surname'] = $attributes['surname'];
        }

        // Aggiorna email solo se verificata in CIE
        if (($attributes['email_verified'] ?? false) &&
            $attributes['email'] &&
            $user->email !== $attributes['email']) {
            $updateData['email'] = $attributes['email'];
            $updateData['email_verified_at'] = now();
        }

        // Aggiorna telefono solo se verificato in CIE
        if (($attributes['phone_verified'] ?? false) &&
            $attributes['phone'] &&
            $user->phone !== $attributes['phone']) {
            $updateData['phone'] = $attributes['phone'];
            $updateData['phone_verified_at'] = now();
        }

        // Aggiorna metodo auth se CIE
        if ($user->getAttribute('auth_method') !== 'cie') {
            $updateData['auth_method'] = 'cie';
            $updateData['cie_provider'] = 'cie';
        }

        // Aggiorna ultimo accesso
        $updateData['last_login_at'] = now();

        $user->update($updateData);
    }
}
