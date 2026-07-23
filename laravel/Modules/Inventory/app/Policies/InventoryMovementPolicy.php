<?php

declare(strict_types=1);

namespace Modules\Inventory\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Inventory\Models\InventoryMovement;

final class InventoryMovementPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InventoryMovement');
    }

    public function view(AuthUser $authUser, InventoryMovement $movement): bool
    {
        return $authUser->can('View:InventoryMovement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InventoryMovement');
    }

    public function update(AuthUser $authUser, InventoryMovement $movement): bool
    {
        return $authUser->can('Update:InventoryMovement');
    }

    public function delete(AuthUser $authUser, InventoryMovement $movement): bool
    {
        return $authUser->can('Delete:InventoryMovement');
    }
}

