<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite/blob/main/routes/web.php
 */

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\LogoutController;

require 'socialite.php';

Route::post('/logout', LogoutController::class)->name('logout');

// Route::get('/upgrade', 'UpgradeController');
