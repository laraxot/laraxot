<?php

declare(strict_types=1);

namespace Modules\Compliance\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Compliance\Models\WorkOrderChangeOrder;

final class WorkOrderChangeOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WorkOrderChangeOrder');
    }

    public function view(AuthUser $authUser, WorkOrderChangeOrder $workOrderChangeOrder): bool
    {
        return $authUser->can('View:WorkOrderChangeOrder');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WorkOrderChangeOrder');
    }

    public function update(AuthUser $authUser, WorkOrderChangeOrder $workOrderChangeOrder): bool
    {
        return $authUser->can('Update:WorkOrderChangeOrder');
    }

    public function delete(AuthUser $authUser, WorkOrderChangeOrder $workOrderChangeOrder): bool
    {
        return $authUser->can('Delete:WorkOrderChangeOrder');
    }
}
