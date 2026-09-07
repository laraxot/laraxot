<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Filament\Facades\Filament;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Events\Dispatcher;
=======
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Events\Dispatcher;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Http\RedirectResponse;
use Modules\User\Events\SocialiteUserConnected;
use Modules\User\Models\SocialiteUser;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class LoginUserAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(SocialiteUser $socialiteUser): RedirectResponse
    {
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::notNull($user = $socialiteUser->user, '['.__FILE__.']['.__LINE__.']');

        if (! $user instanceof Authenticatable) {
            throw new \LogicException('User instance must implement Authenticatable.');
        }

        // PHPStan: assicuriamoci che l'utente sia Authenticatable per il login
        /** @var Authenticatable $authenticatableUser */
        $authenticatableUser = $user;
        Filament::auth()->login($authenticatableUser);
        session()->regenerate();
        app(Dispatcher::class)->dispatch(new SocialiteUserConnected($socialiteUser));

        return redirect()->intended('/'.app()->getLocale());
=======
=======
>>>>>>> 87273113 (.)
        Assert::notNull($user = $socialiteUser->user, '[' . __FILE__ . '][' . __LINE__ . ']');
        Filament::auth()->login($user);
        SocialiteUserConnected::dispatch($socialiteUser);
        // session()->regenerate();

        // return redirect()->intended(Filament::getUrl());
        return redirect()->intended('/');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::notNull($user = $socialiteUser->user, '['.__FILE__.']['.__LINE__.']');

        if (! $user instanceof Authenticatable) {
            throw new \LogicException('User instance must implement Authenticatable.');
        }

        // PHPStan: assicuriamoci che l'utente sia Authenticatable per il login
        /** @var Authenticatable $authenticatableUser */
        $authenticatableUser = $user;
        Filament::auth()->login($authenticatableUser);
        session()->regenerate();
        app(Dispatcher::class)->dispatch(new SocialiteUserConnected($socialiteUser));

        return redirect()->intended('/'.app()->getLocale());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
