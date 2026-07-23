<?php

declare(strict_types=1);

namespace Modules\Catalog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Catalog\Models\CatalogPartType;

class CatalogPartTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CatalogPartType');
    }

    public function view(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('View:CatalogPartType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CatalogPartType');
    }

    public function update(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('Update:CatalogPartType');
    }

    public function delete(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('Delete:CatalogPartType');
    }

    public function restore(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('Restore:CatalogPartType');
    }

    public function forceDelete(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('ForceDelete:CatalogPartType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CatalogPartType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CatalogPartType');
    }

    public function replicate(AuthUser $authUser, CatalogPartType $catalogPartType): bool
    {
        return $authUser->can('Replicate:CatalogPartType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CatalogPartType');
    }
}
