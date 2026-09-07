<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\UserContract;
=======
use Modules\Xot\Contracts\UserContract;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\UserContract;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
=======
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Xot\Contracts\UserContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

class FetchUserApiTokenCommand extends Command
{
<<<<<<< HEAD
<<<<<<< HEAD
    private const int INVALID_ENV = 1;

    private const int USER_NOT_FOUND = 2;
=======
    private const INVALID_ENV = 1;

    private const USER_NOT_FOUND = 2;
>>>>>>> f548be94 (.)
=======
    private const INVALID_ENV = 1;

    private const USER_NOT_FOUND = 2;
=======
    private const int INVALID_ENV = 1;

    private const int USER_NOT_FOUND = 2;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    protected $signature = 'passport:fetch-user-token
                            {email : The email of the user to impersonate}';

    protected $description = 'Fetches an OAuth Token to be able to test APIs';

<<<<<<< HEAD
<<<<<<< HEAD
=======
    

>>>>>>> f548be94 (.)
=======
    

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function handle(): int
    {
        if (app()->isProduction()) {
            $this->error('The command cannot be used in PRODUCTION environments');

            return self::INVALID_ENV;
        }
        Assert::string($email = $this->argument('email'));
        $userEmail = trim($email);
        if (empty($userEmail)) {
            Assert::string($userEmail = $this->ask('Please enter the email of the user to impersonate'));
            $userEmail = trim($userEmail);
        }

        $user_class = XotData::make()->getUserClass();
        /** @var UserContract */
        $user = XotData::make()->getUserByEmail($userEmail);

        if ($user === null) {
            $this->error('User not found!');

            return self::USER_NOT_FOUND;
        }

        $oauthScopes = ['core-technicians'];

        $token = $user->createToken(
            name: sprintf('Debug Token [%s]', Carbon::now()->format('Y-m-d H:i:s')),
            scopes: $oauthScopes,
        );

        $this->info("Access token for `{$userEmail}`:");
        $this->comment($token->accessToken);
<<<<<<< HEAD
<<<<<<< HEAD
        $this->info('Scopes included: '.implode(', ', $oauthScopes));
=======
        $this->info('Scopes included: ' . implode(', ', $oauthScopes));
>>>>>>> f548be94 (.)
=======
        $this->info('Scopes included: ' . implode(', ', $oauthScopes));
=======
        $this->info('Scopes included: '.implode(', ', $oauthScopes));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        return self::SUCCESS;
    }
}
