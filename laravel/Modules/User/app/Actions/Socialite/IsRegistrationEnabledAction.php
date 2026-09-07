<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

use Spatie\QueueableAction\QueueableAction;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Webmozart\Assert\Assert;
>>>>>>> f548be94 (.)
=======
use Webmozart\Assert\Assert;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class IsRegistrationEnabledAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return (bool) config('socialite.registration', true);
=======
        Assert::boolean($res = config('filament-socialite.registration'));

        return $res;
>>>>>>> f548be94 (.)
=======
        Assert::boolean($res = config('filament-socialite.registration'));

        return $res;
=======
        return (bool) config('socialite.registration', true);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
