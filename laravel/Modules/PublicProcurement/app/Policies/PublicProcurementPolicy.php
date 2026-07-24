<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

final class PublicProcurementPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PublicProcurementContract')
            || $authUser->can('ViewAny:PublicProcurementTenderOpportunity');
    }

    public function view(AuthUser $authUser, Model $record): bool
    {
        return $authUser->can('View:'.class_basename($record))
            || $authUser->can('View:PublicProcurementContract');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PublicProcurementContract')
            || $authUser->can('Create:PublicProcurementTenderOpportunity');
    }

    public function update(AuthUser $authUser, Model $record): bool
    {
        return $authUser->can('Update:'.class_basename($record))
            || $authUser->can('Update:PublicProcurementContract');
    }

    public function delete(AuthUser $authUser, Model $record): bool
    {
        return $authUser->can('Delete:'.class_basename($record))
            || $authUser->can('Delete:PublicProcurementContract');
    }
}
