<?php

declare(strict_types=1);

namespace Modules\Catalog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Catalog\Models\CatalogMeasureUnit;

class CatalogMeasureUnitPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatalogMeasureUnit');
    }

    public function view(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('View:CatalogMeasureUnit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatalogMeasureUnit');
    }

    public function update(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('Update:CatalogMeasureUnit');
    }

    public function delete(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('Delete:CatalogMeasureUnit');
    }

    public function restore(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('Restore:CatalogMeasureUnit');
    }

    public function forceDelete(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('ForceDelete:CatalogMeasureUnit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatalogMeasureUnit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatalogMeasureUnit');
    }

    public function replicate(AuthUser $authUser, CatalogMeasureUnit $catalogMeasureUnit): bool
    {
        return $authUser->can('Replicate:CatalogMeasureUnit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatalogMeasureUnit');
    }
}
