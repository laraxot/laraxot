<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Contracts\UserContract;

/**
 * ---.
 *
 * @phpstan-require-extends Model
 */
interface UpdatesUserPasswords
{
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @param array<string, mixed> $input
     */
=======
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @param array<string, mixed> $input
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function update(UserContract $userContract, array $input): void;
}
