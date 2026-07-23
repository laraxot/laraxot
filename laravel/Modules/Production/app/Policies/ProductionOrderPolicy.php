<?php

declare(strict_types=1);

namespace Modules\Production\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Production\Models\ProductionOrder;

final class ProductionOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductionOrder');
    }

    public function view(AuthUser $authUser, ProductionOrder $order): bool
    {
        return $authUser->can('View:ProductionOrder');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductionOrder');
    }

    public function update(AuthUser $authUser, ProductionOrder $order): bool
    {
        return $authUser->can('Update:ProductionOrder');
    }

    public function delete(AuthUser $authUser, ProductionOrder $order): bool
    {
        return $authUser->can('Delete:ProductionOrder');
    }
}

