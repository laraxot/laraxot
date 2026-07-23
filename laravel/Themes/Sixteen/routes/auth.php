<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Themes\Sixteen\Actions\CieAuthAction;
use Themes\Sixteen\Actions\SpidAuthAction;
use Themes\Sixteen\Http\Controllers\CieAuthController;
use Themes\Sixteen\Http\Controllers\SpidAuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes (SPID/CIE)
|--------------------------------------------------------------------------
|
| Route per l'autenticazione SPID e CIE secondo le specifiche AGID
| Queste route sono automaticamente registrate dal ThemeServiceProvider
|
*/

// SPID Authentication Routes
Route::prefix('auth/spid')
    ->name('spid.')
    ->middleware(['web'])
    ->group(function (): void {
        // Login con provider SPID specifico
        Route::get('login/{provider}', [SpidAuthController::class, 'login'])
            ->name('login')
            ->where('provider', '[a-z]+');
        // Callback dal provider SPID
        Route::post('callback', [SpidAuthController::class, 'callback'])
            ->name('callback');

        // Logout SPID
        Route::match(['get', 'post'], 'logout', [SpidAuthController::class, 'logout'])
            ->name('logout')
            ->middleware(['auth']);
        // Single Logout (SLO) dal provider
        Route::post('slo', [SpidAuthController::class, 'singleLogout'])
            ->name('slo');

        // Metadata SAML del Service Provider
        Route::get('metadata', [SpidAuthController::class, 'metadata'])
            ->name('metadata');
    });

// CIE Authentication Routes
Route::prefix('auth/cie')
    ->name('cie.')
    ->middleware(['web'])
    ->group(function (): void {
        // Login CIE web
        Route::get('login', [CieAuthController::class, 'login'])
            ->name('login');

        // Login CIE mobile (app CieID)
        Route::get('mobile', [CieAuthController::class, 'mobileLogin'])
            ->name('mobile');

        // Callback OAuth2 da CIE
        Route::match(['get', 'post'], 'callback', [CieAuthController::class, 'callback'])
            ->name('callback');

        // Logout CIE
        Route::match(['get', 'post'], 'logout', [CieAuthController::class, 'logout'])
            ->name('logout')
            ->middleware(['auth']);

        // Refresh token CIE
        Route::post('refresh', [CieAuthController::class, 'refresh'])
            ->name('refresh')
            ->middleware(['auth']);
        // Status autenticazione CIE
        Route::get('status', [CieAuthController::class, 'status'])
            ->name('status');

        // Debug endpoint (solo in sviluppo)
        Route::get('debug', [CieAuthController::class, 'debug'])
            ->name('debug')
            ->middleware(['web']);
    });

// Route di utilità per l'integrazione con il tema
Route::prefix('sixteen/auth')
    ->name('sixteen.auth.')
    ->middleware(['web'])
    ->group(function (): void {
        // Selezione provider di autenticazione
        Route::view('select-provider', 'pub_theme::auth.select-provider')
            ->name('select-provider');

        // Status generale autenticazione digitale
        Route::get('digital-identity/status', function () {
            $spidService = app(SpidAuthAction::class);
            $cieService = app(CieAuthAction::class);

            return response()->json([
                'spid' => [
                    'authenticated' => $spidService->isAuthenticated(),
                    'user_data' => $spidService->getAuthenticatedUser(),
                ],
                'cie' => [
                    'authenticated' => $cieService->isAuthenticated(),
                    'user_data' => $cieService->getAuthenticatedUser(),
                ],
                'active_method' => session('digital_identity_method'),
            ]);
        })->name('digital-identity.status');
        // Logout universale (SPID o CIE)
        Route::post('digital-identity/logout', function () {
            $spidService = app(SpidAuthAction::class);
            $cieService = app(CieAuthAction::class);

            if ($spidService->isAuthenticated()) {
                return redirect()->route('spid.logout');
            }

            if ($cieService->isAuthenticated()) {
                return redirect()->route('cie.logout');
            }

            // Fallback logout standard
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('home')
                ->with('success', 'Logout effettuato con successo.');
        })->name('digital-identity.logout')->middleware(['auth']);
    });

// Route per testing SPID/CIE (solo in sviluppo)
if (app()->environment(['local', 'development', 'testing'])) {
    Route::prefix('sixteen/test-auth')
        ->name('sixteen.test-auth.')
        ->middleware(['web'])
        ->group(function (): void {
            // Test page per SPID
            Route::view('spid', 'pub_theme::test.spid-test')
                ->name('spid');

            // Test page per CIE
            Route::view('cie', 'pub_theme::test.cie-test')
                ->name('cie');

            // Simulate SPID response (per testing)
            Route::post('spid/simulate', function () {
                $attributes = [
                    'spid_code' => 'TEST123456',
                    'name' => 'Mario',
                    'surname' => 'Rossi',
                    'fiscal_code' => 'RSSMRA80A01H501U',
                    'email' => 'mario.rossi@example.com',
                    'provider' => 'test',
                    'auth_level' => 2,
                ];
                session(['spid.test_attributes' => $attributes]);

                return redirect()->route('spid.callback')
                    ->with('success', 'Simulazione SPID attiva');
            })->name('spid.simulate');

            // Simulate CIE response (per testing)
            Route::post('cie/simulate', function () {
                $attributes = [
                    'cie_id' => 'CIE123456789',
                    'name' => 'Laura',
                    'surname' => 'Bianchi',
                    'fiscal_code' => 'BNCLRA85D45H501Z',
                    'email' => 'laura.bianchi@example.com',
                    'auth_method' => 'web',
                    'email_verified' => true,
                ];

                session(['cie.test_attributes' => $attributes]);

                return redirect()->route('cie.callback')
                    ->with('success', 'Simulazione CIE attiva');
            })->name('cie.simulate');
        });
}
