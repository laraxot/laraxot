<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

use Spatie\QueueableAction\QueueableAction;

class IsProviderConfiguredAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(string $provider): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return config()->has('services.'.$provider);
=======
        return config()->has('services.' . $provider);
>>>>>>> f548be94 (.)
=======
        return config()->has('services.' . $provider);
=======
        return config()->has('services.'.$provider);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
