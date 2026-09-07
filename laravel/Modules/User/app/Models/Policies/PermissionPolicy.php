<?php

declare(strict_types=1);

namespace Modules\User\Models\Policies;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Xot\Contracts\UserContract;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\UserContract;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Contracts\UserContract as Post;

class PermissionPolicy extends UserBasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function viewAny(Post $user): bool
=======
    public function viewAny(UserContract $user): bool
>>>>>>> f548be94 (.)
=======
    public function viewAny(UserContract $user): bool
=======
    public function viewAny(Post $user): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function view(Post $_user, Post $_post): bool
=======
    public function view(UserContract $_user, Post $_post): bool
>>>>>>> f548be94 (.)
=======
    public function view(UserContract $_user, Post $_post): bool
=======
    public function view(Post $_user, Post $_post): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function create(Post $_user): bool
=======
    public function create(UserContract $_user): bool
>>>>>>> f548be94 (.)
=======
    public function create(UserContract $_user): bool
=======
    public function create(Post $_user): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function update(Post $_user, Post $_post): bool
=======
    public function update(UserContract $_user, Post $_post): bool
>>>>>>> f548be94 (.)
=======
    public function update(UserContract $_user, Post $_post): bool
=======
    public function update(Post $_user, Post $_post): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function delete(Post $_user, Post $_post): bool
=======
    public function delete(UserContract $_user, Post $_post): bool
>>>>>>> f548be94 (.)
=======
    public function delete(UserContract $_user, Post $_post): bool
=======
    public function delete(Post $_user, Post $_post): bool
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        // return $user->ownsTeam($team);
        return true;
    }
}
