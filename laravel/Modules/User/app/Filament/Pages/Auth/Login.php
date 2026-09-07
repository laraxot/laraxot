<?php

declare(strict_types=1);

namespace Modules\User\Filament\Pages\Auth;

use Filament\Pages\Concerns\HasRoutes;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Pages\Auth\XotBaseLogin;

class Login extends XotBaseLogin
=======

class Login extends \Filament\Auth\Pages\Login
>>>>>>> f548be94 (.)
=======

class Login extends \Filament\Auth\Pages\Login
=======
use Modules\Xot\Filament\Pages\Auth\XotBaseLogin;

class Login extends XotBaseLogin
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    use HasRoutes;

    protected static string $routePath = 'newlogin';

    /**
     * View personalizzata per la pagina di login.
     * Rimuove il logo duplicato e migliora il layout.
     */
    protected string $view = 'filament-panels::pages.auth.login';
}
