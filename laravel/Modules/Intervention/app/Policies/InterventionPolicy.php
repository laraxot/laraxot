<?php

declare(strict_types=1);

namespace Modules\Intervention\Policies;

use App\Support\AccountFeatures;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Models\Intervention;

class InterventionPolicy
{
    use HandlesAuthorization;

    private function canAccessOwnedIntervention(AuthUser $authUser, Intervention $intervention): bool
    {
        if (AccountFeatures::hasGlobalAdminAccess($authUser)) {
            return true;
        }

        $authIdentifier = $authUser->getAuthIdentifier();
        if (! is_int($authIdentifier) && ! is_string($authIdentifier)) {
            return false;
        }

        $userId = (int) $authIdentifier;

        if (request()->is('admin/*') && AccountFeatures::hasAssignedPerimeter($authUser)) {
            return $this->hasAssignedWorkOrderAccess($userId, $intervention);
        }

        if (method_exists($authUser, 'hasRole') && ($authUser->hasRole('operator') || AccountFeatures::hasAdministrativeRole($authUser))) {
            if ((int) $intervention->performed_by_user_id === $userId) {
                return true;
            }

            return DB::table('intervention_user_assignments')
                ->where('intervention_id', $intervention->id)
                ->where('user_id', $userId)
                ->exists();
        }

        return false;
    }

    private function hasAssignedWorkOrderAccess(int $userId, Intervention $intervention): bool
    {
        $workOrderId = (int) $intervention->work_order_id;

        if ($workOrderId > 0) {
            return DB::table('work_order_assignments')
                ->where('work_order_id', $workOrderId)
                ->where('user_id', $userId)
                ->exists();
        }

        // Direct-customer intervention: check perimeter via any work order for that customer
        $customerId = (int) $intervention->customer_id;
        if ($customerId <= 0) {
            return false;
        }

        return DB::table('work_order_assignments')
            ->join('work_orders', 'work_orders.id', '=', 'work_order_assignments.work_order_id')
            ->where('work_orders.customer_id', $customerId)
            ->where('work_order_assignments.user_id', $userId)
            ->exists();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Intervention');
    }

    public function view(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('View:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Intervention');
    }

    public function update(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('Update:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function delete(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('Delete:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function restore(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('Restore:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function forceDelete(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('ForceDelete:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Intervention');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Intervention');
    }

    public function replicate(AuthUser $authUser, Intervention $intervention): bool
    {
        return $authUser->can('Replicate:Intervention') && $this->canAccessOwnedIntervention($authUser, $intervention);
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Intervention');
    }
}
