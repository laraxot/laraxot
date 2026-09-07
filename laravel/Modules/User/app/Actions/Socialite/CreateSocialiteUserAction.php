<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Models\SocialiteUser;
use Modules\Xot\Contracts\UserContract;
use Spatie\QueueableAction\QueueableAction;

class CreateSocialiteUserAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    public function __construct(
        private readonly SocialiteUser $socialiteUserModel,
    ) {
    }

<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Execute the action.
     */
    public function execute(string $provider, SocialiteUserContract $oauthUser, UserContract $user): SocialiteUser
    {
        $attributes = [
            'user_id' => $user->getKey(),
            'provider' => $provider,
            'provider_id' => $oauthUser->getId(),
            'name' => $oauthUser->getName(),
            // 'nickname' => $oauthUser->getNickname(),
            'email' => $oauthUser->getEmail(),
            'avatar' => $oauthUser->getAvatar(),
        ];

<<<<<<< HEAD
<<<<<<< HEAD
        return $this->socialiteUserModel->create(attributes: $attributes);
=======
        return SocialiteUser::create(attributes: $attributes);
>>>>>>> f548be94 (.)
=======
        return SocialiteUser::create(attributes: $attributes);
=======
        return $this->socialiteUserModel->create(attributes: $attributes);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
