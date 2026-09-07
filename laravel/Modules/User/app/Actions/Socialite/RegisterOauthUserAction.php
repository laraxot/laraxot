<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
=======
use Illuminate\Support\Facades\DB;
>>>>>>> f548be94 (.)
=======
use Illuminate\Support\Facades\DB;
=======
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\DatabaseManager;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Modules\User\Events\Registered;
use Modules\User\Models\SocialiteUser;
use Spatie\QueueableAction\QueueableAction;

class RegisterOauthUserAction
{
    use QueueableAction;

    public function execute(string $provider, SocialiteUserContract $oauthUser): SocialiteUser
    {
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = app(DatabaseManager::class)->transaction(static function () use ($provider, $oauthUser): SocialiteUser {
=======
        $socialiteUser = DB::transaction(static function () use ($provider, $oauthUser) {
>>>>>>> f548be94 (.)
=======
        $socialiteUser = DB::transaction(static function () use ($provider, $oauthUser) {
=======
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = app(DatabaseManager::class)->transaction(static function () use ($provider, $oauthUser): SocialiteUser {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            // Create a user
            $user = app(CreateUserAction::class)->execute(
                provider: $provider,
                oauthUser: $oauthUser,
            );

            // Create a new socialite user instance
            return app(CreateSocialiteUserAction::class)->execute(
                provider: $provider,
                oauthUser: $oauthUser,
                user: $user,
            );
        });
        // Dispatch the registered event
<<<<<<< HEAD
<<<<<<< HEAD
        app(Dispatcher::class)->dispatch(new Registered($socialiteUser));
=======
        Registered::dispatch($socialiteUser);
>>>>>>> f548be94 (.)
=======
        Registered::dispatch($socialiteUser);
=======
        app(Dispatcher::class)->dispatch(new Registered($socialiteUser));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        // Login the user
        // return app(LoginUserAction::class)->execute($socialiteUser);
        return $socialiteUser;
    }
}
