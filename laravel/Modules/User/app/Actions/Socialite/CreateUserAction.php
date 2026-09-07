<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

use Illuminate\Database\Eloquent\Model;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Handles the creation of a new user from a socialite authentication.
 */
class CreateUserAction
{
    use QueueableAction;

    /**
     * Execute the action to create a new user from socialite authentication.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param string                $provider  The socialite provider name (e.g., 'github', 'google')
     * @param SocialiteUserContract $oauthUser The socialite user instance
     *
=======
     * @param string $provider The socialite provider name (e.g., 'github', 'google')
     * @param SocialiteUserContract $oauthUser The socialite user instance
>>>>>>> f548be94 (.)
=======
     * @param string $provider The socialite provider name (e.g., 'github', 'google')
     * @param SocialiteUserContract $oauthUser The socialite user instance
=======
     * @param string                $provider  The socialite provider name (e.g., 'github', 'google')
     * @param SocialiteUserContract $oauthUser The socialite user instance
     *
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return UserContract The created user instance
     */
    public function execute(string $provider, SocialiteUserContract $oauthUser): UserContract
    {
        // Resolve user attributes from the identity provider
<<<<<<< HEAD
<<<<<<< HEAD
        $userAttributes = app(GetUserModelAttributesFromSocialiteAction::class)->execute($provider, $oauthUser);
=======
=======
>>>>>>> 87273113 (.)
        $userAttributes = app(GetUserModelAttributesFromSocialiteAction::class, [
            'provider' => $provider,
            'oauthUser' => $oauthUser,
        ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $userAttributes = app(GetUserModelAttributesFromSocialiteAction::class)->execute($provider, $oauthUser);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        // Get the user class from Xot configuration
        $userClass = XotData::make()->getUserClass();

        // Create the new user
        $newlyCreatedUser = $userClass::create([
            'name' => $userAttributes->name,
            'first_name' => $userAttributes->name,
<<<<<<< HEAD
<<<<<<< HEAD
            'last_name' => $userAttributes->lastName,
=======
            'last_name' => $userAttributes->last_name,
>>>>>>> f548be94 (.)
=======
            'last_name' => $userAttributes->last_name,
=======
            'last_name' => $userAttributes->lastName,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'email' => $userAttributes->email,
        ]);

        // Ensure the created user implements UserContract
        Assert::isInstanceOf($newlyCreatedUser, Model::class);
        Assert::isInstanceOf($newlyCreatedUser, UserContract::class);

        // Assign default roles to the new user
<<<<<<< HEAD
<<<<<<< HEAD
        app(SetDefaultRolesBySocialiteUserAction::class)->execute(
            provider: $provider,
=======
=======
>>>>>>> 87273113 (.)
        app(SetDefaultRolesBySocialiteUserAction::class, [
            'provider' => $provider,
            'userModel' => $newlyCreatedUser,
        ])->execute(
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        app(SetDefaultRolesBySocialiteUserAction::class)->execute(
            provider: $provider,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            userModel: $newlyCreatedUser,
            oauthUser: $oauthUser,
        );

        // Return the refreshed user instance
        /** @var UserContract $refreshedUser */
        $refreshedUser = $newlyCreatedUser->refresh();

        return $refreshedUser;
    }
}
