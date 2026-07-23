<?php

declare(strict_types=1);

namespace Modules\Intervention\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Intervention\Models\DailyNote;

class DailyNotePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DailyNote');
    }

    public function view(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('View:DailyNote');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DailyNote');
    }

    public function update(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('Update:DailyNote');
    }

    public function delete(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('Delete:DailyNote');
    }

    public function restore(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('Restore:DailyNote');
    }

    public function forceDelete(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('ForceDelete:DailyNote');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DailyNote');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DailyNote');
    }

    public function replicate(AuthUser $authUser, DailyNote $dailyNote): bool
    {
        return $authUser->can('Replicate:DailyNote');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DailyNote');
    }
}
