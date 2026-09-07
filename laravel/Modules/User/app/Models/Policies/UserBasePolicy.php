<?php

declare(strict_types=1);

/**
 * ----------------------------------------------------------------.
 * EX XotBasePolicy.
 */

namespace Modules\User\Models\Policies;

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Models\Policies\XotBasePolicy;

abstract class UserBasePolicy extends XotBasePolicy
{
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;

// use Modules\Xot\Datas\XotData;

abstract class UserBasePolicy
{
    use HandlesAuthorization;

    public function before(UserContract $user, string $_ability): null|bool
    {
        $xotData = XotData::make();
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Models\Policies\XotBasePolicy;

abstract class UserBasePolicy extends XotBasePolicy
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
