<?php

declare(strict_types=1);

namespace Modules\User\Models\Policies;

<<<<<<< HEAD
<<<<<<< HEAD
class SocialiteUserPolicy extends UserBasePolicy
{
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\SocialiteUser;
use Modules\Xot\Contracts\UserContract;

class SocialiteUserPolicy extends UserBasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UserContract $user): bool
    {
        return $user->hasPermissionTo('socialite-user.view.any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UserContract $user, SocialiteUser $socialiteUser): bool
    {
        return (
            $user->hasPermissionTo('socialite-user.view') ||
            $user->id === $socialiteUser->user_id ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UserContract $user): bool
    {
        return $user->hasPermissionTo('socialite-user.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UserContract $user, SocialiteUser $socialiteUser): bool
    {
        return (
            $user->hasPermissionTo('socialite-user.update') ||
            $user->id === $socialiteUser->user_id ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UserContract $user, SocialiteUser $socialiteUser): bool
    {
        return (
            $user->hasPermissionTo('socialite-user.delete') ||
            $user->id === $socialiteUser->user_id ||
            $user->hasRole('super-admin')
        );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UserContract $user, SocialiteUser $_socialiteUser): bool
    {
        return $user->hasPermissionTo('socialite-user.restore') || $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UserContract $user, SocialiteUser $socialiteUser): bool
    {
        return $user->hasPermissionTo('socialite-user.force-delete') || $user->hasRole('super-admin');
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
class SocialiteUserPolicy extends UserBasePolicy
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
