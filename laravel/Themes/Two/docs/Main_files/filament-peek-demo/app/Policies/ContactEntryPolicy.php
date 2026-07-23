<?php

namespace App\Policies;

use App\Models\ContactEntry;
use App\Models\ContactEntry;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\Response;
use Modules\User\Models\User;
use Modules\User\Models\User;

class ContactEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, ContactEntry $contactEntry)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, ContactEntry $contactEntry)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(User $user, ContactEntry $contactEntry)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, ContactEntry $contactEntry)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, ContactEntry $contactEntry)
    {
        return true;
    }
}
