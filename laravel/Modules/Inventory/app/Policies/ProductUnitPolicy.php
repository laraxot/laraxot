<?php

declare(strict_types=1);

namespace Modules\Inventory\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Inventory\Models\ProductUnit;

final class ProductUnitPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductUnit');
    }

    public function view(AuthUser $authUser, ProductUnit $unit): bool
    {
        return $authUser->can('View:ProductUnit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductUnit');
    }

    public function update(AuthUser $authUser, ProductUnit $unit): bool
    {
        return $authUser->can('Update:ProductUnit');
    }

    public function delete(AuthUser $authUser, ProductUnit $unit): bool
    {
        return $authUser->can('Delete:ProductUnit');
    }
}

