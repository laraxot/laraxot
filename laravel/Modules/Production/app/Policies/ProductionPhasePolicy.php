<?php

declare(strict_types=1);

namespace Modules\Production\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Production\Models\ProductionPhase;

final class ProductionPhasePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductionPhase');
    }

    public function view(AuthUser $authUser, ProductionPhase $phase): bool
    {
        return $authUser->can('View:ProductionPhase');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductionPhase');
    }

    public function update(AuthUser $authUser, ProductionPhase $phase): bool
    {
        return $authUser->can('Update:ProductionPhase');
    }

    public function delete(AuthUser $authUser, ProductionPhase $phase): bool
    {
        return $authUser->can('Delete:ProductionPhase');
    }
}

