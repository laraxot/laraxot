<?php

declare(strict_types=1);

/**
 * ----------------------------------------------------------------.
 * EX XotBasePolicy.
 */

namespace Modules\User\Models\Policies;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Exception;
>>>>>>> f548be94 (.)
=======
use Exception;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;
use Modules\User\Models\Permission;
use Modules\Xot\Contracts\UserContract;

// use Modules\Xot\Datas\XotData;

abstract class UserPermissionBasePolicy
{
    use HandlesAuthorization;

<<<<<<< HEAD
<<<<<<< HEAD
    public function before(UserContract $user, string $ability): ?bool
=======
    public function before(UserContract $user, string $ability): null|bool
>>>>>>> f548be94 (.)
=======
    public function before(UserContract $user, string $ability): null|bool
=======
    public function before(UserContract $user, string $ability): ?bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        $class_name = class_basename(static::class);
        $permission_name = Str::of($class_name)
            ->before('Policy')
            ->lower()
<<<<<<< HEAD
<<<<<<< HEAD
            ->append('.'.$ability)
=======
            ->append('.' . $ability)
>>>>>>> f548be94 (.)
=======
            ->append('.' . $ability)
=======
            ->append('.'.$ability)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ->toString();

        try {
            Permission::firstOrCreate(['name' => $permission_name]);
<<<<<<< HEAD
<<<<<<< HEAD
        } catch (\Exception $e) {
=======
        } catch (Exception $e) {
>>>>>>> f548be94 (.)
=======
        } catch (Exception $e) {
=======
        } catch (\Exception $e) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            // dddx($e);
        }
        if ($user->hasPermissionTo($permission_name)) {
            return true;
        }

        return null;
    }
}
