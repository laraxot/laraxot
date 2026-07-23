<?php

declare(strict_types=1);

namespace Modules\Inventory\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Inventory\Models\WarehouseLocation;

final class WarehouseLocationPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WarehouseLocation');
    }

    public function view(AuthUser $authUser, WarehouseLocation $warehouseLocation): bool
    {
        return $authUser->can('View:WarehouseLocation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WarehouseLocation');
    }

    public function update(AuthUser $authUser, WarehouseLocation $warehouseLocation): bool
    {
        return $authUser->can('Update:WarehouseLocation');
    }

    public function delete(AuthUser $authUser, WarehouseLocation $warehouseLocation): bool
    {
        return $authUser->can('Delete:WarehouseLocation');
    }
}
