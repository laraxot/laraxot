<?php

declare(strict_types=1);

namespace Modules\User\Models\Policies;

use Modules\User\Models\Tenant;
use Modules\Xot\Contracts\UserContract;

class TenantPolicy extends UserBasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UserContract $user): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        // return $user->hasPermissionTo('tenant.view.any');
        return false;
=======
        return $user->hasPermissionTo('tenant.view.any');
>>>>>>> f548be94 (.)
=======
        return $user->hasPermissionTo('tenant.view.any');
=======
        // return $user->hasPermissionTo('tenant.view.any');
        return false;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UserContract $user, Tenant $tenant): bool
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return $user->hasPermissionTo('tenant.view')
            || $user->tenants->contains($tenant->id)
            || $user->hasRole('super-admin');
=======
=======
>>>>>>> 87273113 (.)
        return (
            $user->hasPermissionTo('tenant.view') ||
            $user->tenants->contains($tenant->id) ||
            $user->hasRole('super-admin')
        );
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        return $user->hasPermissionTo('tenant.view')
            || $user->tenants->contains($tenant->id)
            || $user->hasRole('super-admin');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('tenant.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UserContract $user, Tenant $_tenant): bool
    {
        return $user->hasPermissionTo('tenant.update') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UserContract $user, Tenant $_tenant): bool
    {
        return $user->hasPermissionTo('tenant.delete') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UserContract $user, Tenant $_tenant): bool
    {
        return $user->hasPermissionTo('tenant.restore') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UserContract $user, Tenant $tenant): bool
    {
        return $user->hasPermissionTo('tenant.force-delete') || $user->hasRole('super-admin');
    }
}
