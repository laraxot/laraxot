<?php

/**
 * routes from laravel preset Tall.
 */

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\EmailVerificationController;
use Modules\User\Http\Controllers\Auth\LogoutController;
use Modules\User\Http\Livewire\Auth\Passwords\Confirm;
use Modules\User\Http\Livewire\Auth\Passwords\Email;
use Modules\User\Http\Livewire\Auth\Passwords\Reset;
use Modules\User\Http\Livewire\Auth\Register;
use Modules\User\Http\Livewire\Auth\Verify;
use Webmozart\Assert\Assert;
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Http\Livewire\Auth\Passwords\Email;
use Modules\User\Http\Livewire\Auth\Passwords\Reset;
use Modules\User\Http\Livewire\Auth\Verify;
use Modules\User\Http\Livewire\Auth\Passwords\Confirm;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\EmailVerificationController;
use Modules\User\Http\Controllers\Auth\LogoutController;
use Modules\User\Http\Livewire\Auth\Register;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\EmailVerificationController;
use Modules\User\Http\Controllers\Auth\LogoutController;
use Modules\User\Http\Livewire\Auth\Passwords\Confirm;
use Modules\User\Http\Livewire\Auth\Passwords\Email;
use Modules\User\Http\Livewire\Auth\Passwords\Reset;
use Modules\User\Http\Livewire\Auth\Register;
use Modules\User\Http\Livewire\Auth\Verify;
use Webmozart\Assert\Assert;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

// Route::view('/', 'welcome')->name('home');
<<<<<<< HEAD
<<<<<<< HEAD
Route::prefix('{lang}')->group(function (): void {
=======
Route::prefix('{lang}')->group(function () {
>>>>>>> f548be94 (.)
=======
Route::prefix('{lang}')->group(function () {
=======
Route::prefix('{lang}')->group(function (): void {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    Route::middleware('guest')
        ->namespace('\Modules\User\Http\Livewire\Auth')
        ->group(static function (): void {
            Route::get('login', 'Login')->name('login');

            Route::get('register', Register::class)->name('register');
        });

    Route::middleware([])->namespace('\Modules\User\Http\Livewire\Auth')->group(static function (): void {
        Route::get('password/reset', Email::class)->name('password.request');

        Route::get('password/reset/{token}', Reset::class)->name(
            'password.reset',
        );
    });

    Route::middleware('auth')
        ->namespace('\Modules\User\Http\Livewire\Auth')
        ->group(static function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
            $route = Route::get('email/verify', Verify::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->middleware('throttle:6,1');
            $route->name('verification.notice');

            $route = Route::get('password/confirm', Confirm::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->name('password.confirm');
=======
=======
>>>>>>> 87273113 (.)
            Route::get('email/verify', Verify::class)
                ->middleware('throttle:6,1')
                ->name('verification.notice');

            Route::get('password/confirm', Confirm::class)->name(
                'password.confirm',
            );
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            $route = Route::get('email/verify', Verify::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->middleware('throttle:6,1');
            $route->name('verification.notice');

            $route = Route::get('password/confirm', Confirm::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->name('password.confirm');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });

    Route::middleware('auth')
        // ->namespace('\Modules\User\Http\Livewire\Auth')
        ->group(static function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
            $route = Route::get('email/verify/{id}/{hash}', EmailVerificationController::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->middleware('signed');
            $route->name('verification.verify');
=======
            Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
                ->middleware('signed')
                ->name('verification.verify');
>>>>>>> f548be94 (.)
=======
            Route::get('email/verify/{id}/{hash}', EmailVerificationController::class)
                ->middleware('signed')
                ->name('verification.verify');
=======
            $route = Route::get('email/verify/{id}/{hash}', EmailVerificationController::class);
            Assert::isInstanceOf($route, Illuminate\Routing\Route::class);
            $route->middleware('signed');
            $route->name('verification.verify');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            Route::match(['get', 'post'], 'logout', LogoutController::class)->name('logout');
        });
})->whereIn('lang', ['it', 'en']);

Route::namespace('Socialite')
    ->name('socialite.')
    ->group(static function (): void {
        Route::get(
            '/login/{provider}',
            'RedirectToProviderController',
<<<<<<< HEAD
<<<<<<< HEAD
            // 'LoginController@redirectToProvider',
=======
        // 'LoginController@redirectToProvider',
>>>>>>> f548be94 (.)
=======
        // 'LoginController@redirectToProvider',
=======
            // 'LoginController@redirectToProvider',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        );
        // ->name('oauth.redirect')

        Route::get('/sso/{provider}/callback', 'ProcessCallbackController');

        // ->name('oauth.callback');
    });
