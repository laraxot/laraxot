<?php

declare(strict_types=1);

namespace Modules\User\Http\Volt;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Volt\Routing\Attribute\Post;
>>>>>>> f548be94 (.)
=======
use Volt\Routing\Attribute\Post;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/*
 * Attribute class Volt\Routing\Attribute\Post does not exist.
 *
 * #[Post('/logout', name: 'logout', middleware: ['web', 'auth'])]
 */
final class LogoutAction
{
    public function __invoke(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home');
    }
}
