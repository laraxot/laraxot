<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models\Policies;

use Modules\Vehicle\Models\Vehicle;
use Modules\Xot\Contracts\UserContract;

class VehiclePolicy extends VehicleBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('vehicle.viewAny');
    }

    public function view(UserContract $user, Vehicle $_vehicle): bool
    {
        return $user->hasPermissionTo('vehicle.view');
    }

    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('vehicle.create');
    }

    public function update(UserContract $user, Vehicle $_vehicle): bool
    {
        return $user->hasPermissionTo('vehicle.update');
    }

    public function delete(UserContract $user, Vehicle $_vehicle): bool
    {
        return $user->hasPermissionTo('vehicle.delete');
    }

    public function restore(UserContract $user, Vehicle $_vehicle): bool
    {
        return $user->hasPermissionTo('vehicle.restore');
    }

    public function forceDelete(UserContract $user, Vehicle $_vehicle): bool
    {
        return $user->hasPermissionTo('vehicle.forceDelete');
    }
}
