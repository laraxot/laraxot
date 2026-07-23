<?php

declare(strict_types=1);

namespace Modules\Bom\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Bom\Models\Bom;

final class BomPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Bom');
    }

    public function view(AuthUser $authUser, Bom $bom): bool
    {
        return $authUser->can('View:Bom');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Bom');
    }

    public function update(AuthUser $authUser, Bom $bom): bool
    {
        return $authUser->can('Update:Bom');
    }

    public function delete(AuthUser $authUser, Bom $bom): bool
    {
        return $authUser->can('Delete:Bom');
    }
}

