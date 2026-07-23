<?php

declare(strict_types=1);

namespace Modules\Catalog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Catalog\Models\CatalogFrequency;

class CatalogFrequencyPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatalogFrequency');
    }

    public function view(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('View:CatalogFrequency');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatalogFrequency');
    }

    public function update(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('Update:CatalogFrequency');
    }

    public function delete(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('Delete:CatalogFrequency');
    }

    public function restore(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('Restore:CatalogFrequency');
    }

    public function forceDelete(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('ForceDelete:CatalogFrequency');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatalogFrequency');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatalogFrequency');
    }

    public function replicate(AuthUser $authUser, CatalogFrequency $catalogFrequency): bool
    {
        return $authUser->can('Replicate:CatalogFrequency');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatalogFrequency');
    }
}
