<?php

declare(strict_types=1);

namespace Modules\Compliance\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Compliance\Models\Equipment;

final class EquipmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Equipment');
    }

    public function view(AuthUser $authUser, Equipment $equipment): bool
    {
        return $authUser->can('View:Equipment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Equipment');
    }

    public function update(AuthUser $authUser, Equipment $equipment): bool
    {
        return $authUser->can('Update:Equipment');
    }

    public function delete(AuthUser $authUser, Equipment $equipment): bool
    {
        return $authUser->can('Delete:Equipment');
    }
}
