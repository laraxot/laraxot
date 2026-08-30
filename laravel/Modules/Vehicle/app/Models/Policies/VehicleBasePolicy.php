<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Xot\Contracts\UserContract;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

abstract class VehicleBasePolicy
{
    use HandlesAuthorization;

    public function before(UserContract $user, string $_ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    /**
     * Una policy deve rispondere "no", non esplodere, quando il permesso non e'
     * ancora stato seedato: spatie lancia PermissionDoesNotExist e un solo
     * permesso mancante fa fuori il rendering dell'intera sidebar Filament.
     */
    protected function allows(UserContract $user, string $permission): bool
    {
        try {
            return $user->hasPermissionTo($permission);
        } catch (PermissionDoesNotExist) {
            return false;
        }
    }
}
