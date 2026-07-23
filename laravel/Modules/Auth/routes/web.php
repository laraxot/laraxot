<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\EmailTwoFactorController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::middleware(['web'])->group(function (): void {
    Route::get('/login', [LoginController::class, 'show'])->name('legacy.login');
    Route::post('/login', [LoginController::class, 'store'])->name('legacy.login.store');
    Route::get('/register', [RegisterController::class, 'show'])->name('account.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('account.register.store');
    Route::view('/registrazione-in-attesa', 'auth::registration-pending')->name('account.registration.pending');
    Route::middleware(['auth'])->group(function (): void {
        Route::get('/email-two-factor', [EmailTwoFactorController::class, 'show'])->name('email-two-factor.challenge');
        Route::post('/email-two-factor', [EmailTwoFactorController::class, 'verify'])->name('email-two-factor.verify');
        Route::post('/email-two-factor/resend', [EmailTwoFactorController::class, 'resend'])->name('email-two-factor.resend');
    });
    Route::post('/logout', [LoginController::class, 'destroy'])->name('legacy.logout');
});

