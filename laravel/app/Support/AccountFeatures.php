<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;

/**
 * Shim permissivo da gestionale_commesse (App\Support).
 *
 * Scopo Wave 1: far compilare Customers/WorkOrders/Interventions senza portare
 * la matrice pannelli SRC. Comportamento: feature → default/fallback; scope → no-op.
 * Porting definitivo → Tenant (o User) in story successiva.
 */
final class AccountFeatures
{
    public static function hasAdministrativeRole(mixed $user): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        return method_exists($user, 'hasRole')
            && (
                $user->hasRole('admin')
                || $user->hasRole('administrator')
                || $user->hasRole('super_admin')
            );
    }

    public static function canAccessPanel(mixed $user, string $panelId): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        if (self::hasAdministrativeRole($user)) {
            return true;
        }

        return method_exists($user, 'hasRole')
            && (
                $user->hasRole($panelId)
                || $user->hasRole($panelId.'_operator')
                || $user->hasRole($panelId.'_admin')
            );
    }

    public static function hasFeature(mixed $user, string $feature, bool $default = false): bool
    {
        return $default;
    }

    public static function hasAccountFeature(mixed $user, string $feature, bool $default = false): bool
    {
        return self::hasFeature($user, $feature, $default);
    }

    public static function hasAssignedPerimeter(mixed $user): bool
    {
        return false;
    }

    public static function hasGlobalAdminAccess(mixed $user): bool
    {
        return self::hasAdministrativeRole($user);
    }

    public static function currentPanelAllowsFeature(
        mixed $user,
        ?string $adminFeature = null,
        ?string $operatorFeature = null,
        bool $adminDefault = true,
        bool $operatorDefault = true,
        bool $fallback = false,
    ): bool {
        if (! $user instanceof User) {
            return false;
        }

        $panelId = self::currentPanelId();

        return match ($panelId) {
            'admin' => $adminFeature === null
                ? $adminDefault
                : self::hasFeature($user, $adminFeature, $adminDefault),
            'operator' => $operatorFeature === null
                ? $operatorDefault
                : self::hasFeature($user, $operatorFeature, $operatorDefault),
            default => $fallback,
        };
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function scopeAssignedCustomers(Builder $query, User $user, string $customersTable = 'customers'): Builder
    {
        return $query;
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function scopeAssignedWorkOrders(Builder $query, User $user, string $workOrdersTable = 'work_orders'): Builder
    {
        return $query;
    }

    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return Builder<\Illuminate\Database\Eloquent\Model>
     */
    public static function scopeAssignedInterventions(Builder $query, User $user, string $interventionsTable = 'interventions'): Builder
    {
        return $query;
    }

    private static function currentPanelId(): ?string
    {
        try {
            if (function_exists('filament') && filament()->getCurrentPanel() !== null) {
                return filament()->getCurrentPanel()->getId();
            }
        } catch (\Throwable) {
            // panel non bootstrappato
        }

        $user = Auth::user();
        if ($user instanceof User && method_exists($user, 'hasRole')) {
            if (self::hasAdministrativeRole($user)) {
                return 'admin';
            }
            if ($user->hasRole('operator')) {
                return 'operator';
            }
        }

        return null;
    }
}
