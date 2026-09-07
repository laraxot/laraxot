<?php

declare(strict_types=1);

namespace Modules\User\Http\Response;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Webmozart\Assert\Assert;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class PasswordResetResponse implements Responsable
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // Assert::string($path = config('password-expiry.after_password_reset_redirect') ?: Filament::getLoginUrl());
        $path = url('/');

        return redirect()->to($path);
    }
}
