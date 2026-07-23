<?php

declare(strict_types=1);

namespace Modules\Compliance\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Compliance\Models\Subcontractor;

final class SubcontractorPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Subcontractor');
    }

    public function view(AuthUser $authUser, Subcontractor $subcontractor): bool
    {
        return $authUser->can('View:Subcontractor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Subcontractor');
    }

    public function update(AuthUser $authUser, Subcontractor $subcontractor): bool
    {
        return $authUser->can('Update:Subcontractor');
    }

    public function delete(AuthUser $authUser, Subcontractor $subcontractor): bool
    {
        return $authUser->can('Delete:Subcontractor');
    }
}
