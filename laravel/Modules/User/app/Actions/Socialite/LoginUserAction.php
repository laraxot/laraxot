<?php

declare(strict_types=1);

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

namespace Modules\User\Actions\Socialite;

// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Modules\User\Events\SocialiteUserConnected;
use Modules\User\Models\SocialiteUser;
use Spatie\QueueableAction\QueueableAction;

class LoginUserAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(SocialiteUser $socialiteUser): RedirectResponse
    {
        /** @var \Modules\Xot\Contracts\UserContract $user */
        $user = $socialiteUser->user()->firstOrFail();

        event(new SocialiteUserConnected($socialiteUser));

        Filament::auth()->login($user);

        return redirect()->intended('/');
    }
}
