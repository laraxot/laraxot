<?php

declare(strict_types=1);

namespace Modules\Catalog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Catalog\Models\CatalogModel;

class CatalogModelPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatalogModel');
    }

    public function view(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('View:CatalogModel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatalogModel');
    }

    public function update(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('Update:CatalogModel');
    }

    public function delete(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('Delete:CatalogModel');
    }

    public function restore(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('Restore:CatalogModel');
    }

    public function forceDelete(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('ForceDelete:CatalogModel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatalogModel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatalogModel');
    }

    public function replicate(AuthUser $authUser, CatalogModel $catalogModel): bool
    {
        return $authUser->can('Replicate:CatalogModel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatalogModel');
    }
}
