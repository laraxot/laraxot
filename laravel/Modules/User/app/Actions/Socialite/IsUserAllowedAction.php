<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
=======
// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
>>>>>>> f548be94 (.)
=======
// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class IsUserAllowedAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(SocialiteUserContract $user): bool
    {
        $domains = app(GetDomainAllowListAction::class)->execute();

        // When no domains are specified, all users are allowed
        if (\count($domains) < 1) {
            return true;
        }

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::notNull($user->getEmail(), '['.__FILE__.']['.__LINE__.']');
=======
        Assert::notNull($user->getEmail(), '[' . __FILE__ . '][' . __LINE__ . ']');
>>>>>>> f548be94 (.)
=======
        Assert::notNull($user->getEmail(), '[' . __FILE__ . '][' . __LINE__ . ']');
=======
        Assert::notNull($user->getEmail(), '['.__FILE__.']['.__LINE__.']');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        // Get the domain of the email for the specified user
        $emailDomain = Str::of($user->getEmail())
            ->afterLast('@')
            ->lower()
            ->__toString();

        // See if everything after @ is in the domains array
        return \in_array($emailDomain, $domains, false);
    }
}
