<?php

declare(strict_types=1);

namespace Modules\Vehicle\Models\Policies;

use Modules\Vehicle\Models\Vehicle;
use Modules\Xot\Contracts\UserContract;

class VehiclePolicy extends VehicleBasePolicy
{
    public function viewAny(UserContract $user): bool
    {
        return $this->allows($user, 'vehicle.viewAny');
    }

    public function view(UserContract $user, Vehicle $_vehicle): bool
    {
        return $this->allows($user, 'vehicle.view');
    }

    public function create(UserContract $user): bool
    {
        return $this->allows($user, 'vehicle.create');
    }

    public function update(UserContract $user, Vehicle $_vehicle): bool
    {
        return $this->allows($user, 'vehicle.update');
    }

    public function delete(UserContract $user, Vehicle $_vehicle): bool
    {
        return $this->allows($user, 'vehicle.delete');
    }

    public function restore(UserContract $user, Vehicle $_vehicle): bool
    {
        return $this->allows($user, 'vehicle.restore');
    }

    public function forceDelete(UserContract $user, Vehicle $_vehicle): bool
    {
        return $this->allows($user, 'vehicle.forceDelete');
    }
}
