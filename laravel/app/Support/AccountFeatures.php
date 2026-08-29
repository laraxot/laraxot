<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Models\User;

final class AccountFeatures
{
    public static function hasAdministrativeRole(mixed $user): bool
    {
        return $user instanceof User
            && (
                $user->hasRole('admin')
                || $user->hasRole('administrator')
                || $user->hasRole('super_admin')
                || $user->hasRole('super-admin')
            );
    }

    public static function isWtsAdmin(mixed $user): bool
    {
        return $user instanceof User
            && ($user->hasRole('wts_admin') || self::hasAdministrativeRole($user));
    }

    /**
     * Pagine WTS visibili al wts_operator in "modalità Brondi": solo lo snodo lavorazioni
     * e le 4 pagine di lavorazione. Il wts_admin invece vede tutto.
     *
     * Stringhe (non ::class): il modulo Wts non è ancora portato in questa base.
     *
     * @var list<string>
     */
    public const WTS_OPERATOR_PAGES = [
        'Modules\\Wts\\Filament\\Pages\\WtsProcessingPage',
        'Modules\\Wts\\Filament\\Pages\\WtsSortingPage',
        'Modules\\Wts\\Filament\\Pages\\WtsUnpackagingPage',
        'Modules\\Wts\\Filament\\Pages\\WtsGrindingWashingPage',
        'Modules\\Wts\\Filament\\Pages\\WtsPressingPage',
    ];

    /**
     * Accesso a una singola pagina del pannello WTS. wts_admin: tutte. wts_operator
     * (modalità Brondi): solo snodo + 4 lavorazioni.
     */
    public static function canAccessWtsPage(mixed $user, string $pageClass): bool
    {
        if (! self::canAccessPanel($user, 'wts')) {
            return false;
        }

        if (self::isWtsAdmin($user)) {
            return true;
        }

        return in_array($pageClass, self::WTS_OPERATOR_PAGES, true);
    }

    public static function hasPanelRole(mixed $user, string $panelId): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        return match ($panelId) {
            'admin' => self::hasAdministrativeRole($user),
            'operator' => $user->hasRole('operator'),
            'wts' => $user->hasRole('wts_operator') || $user->hasRole('wts_admin'),
            'dds' => $user->hasRole('dds_admin')
                || $user->hasRole('dds_technician')
                || $user->hasRole('dds_customer'),
            'thermo' => $user->hasRole('thermo_admin')
                || $user->hasRole('thermo_technician')
                || $user->hasRole('thermo_supplier_call_center')
                || $user->hasRole('thermo_customer'),
            'energy-broker' => $user->hasRole('energy_broker_admin')
                || $user->hasRole('energy_broker_sales')
                || $user->hasRole('energy_broker_backoffice')
                || $user->hasRole('energy_broker_compliance')
                || $user->hasRole('energy_broker_agent')
                || $user->hasRole('energy_broker_support'),
            'asd-soci' => $user->hasRole('asd_admin')
                || $user->hasRole('asd_secretary')
                || $user->hasRole('asd_treasurer')
                || $user->hasRole('asd_member')
                || $user->hasRole('asd_event_manager'),
            'giveback-platform' => $user->hasRole('giveback_admin')
                || $user->hasRole('giveback_merchant')
                || $user->hasRole('giveback_support'),
            'timber' => $user->hasRole('timber_operator') || $user->hasRole('timber_admin'),
            'metalworks' => $user->hasRole('metalworks_operator') || $user->hasRole('metalworks_admin'),
            'customer' => $user->hasRole('customer'),
            'supplier' => $user->hasRole('supplier'),
            default => false,
        };
    }

    /**
     * I panel Laraxot hanno id "{modulo}::admin" (es. timber::admin): la matrice
     * account_features usa la chiave corta del modulo. Normalizza prima del match.
     */
    public static function normalizePanelId(?string $panelId): ?string
    {
        if ($panelId !== null && str_contains($panelId, '::')) {
            return (string) strtok($panelId, ':');
        }

        return $panelId;
    }

    public static function canAccessPanel(mixed $user, string $panelId): bool
    {
        $panelId = (string) self::normalizePanelId($panelId);
        if (! $user instanceof User) {
            return false;
        }

        $matrix = self::matrixFor($user);

        if (array_key_exists($panelId, $matrix['panels'])) {
            return (bool) $matrix['panels'][$panelId];
        }

        return match ($panelId) {
            'admin' => self::hasPanelRole($user, 'admin'),
            'operator' => self::hasPanelRole($user, 'operator') || self::hasPanelRole($user, 'admin'),
            'wts' => self::hasPanelRole($user, 'wts') || self::hasPanelRole($user, 'admin'),
            'dds' => self::hasPanelRole($user, 'dds') || self::hasPanelRole($user, 'admin'),
            'thermo' => self::hasPanelRole($user, 'thermo') || self::hasPanelRole($user, 'admin'),
            'energy-broker' => self::hasPanelRole($user, 'energy-broker') || self::hasPanelRole($user, 'admin'),
            'asd-soci' => self::hasPanelRole($user, 'asd-soci') || self::hasPanelRole($user, 'admin'),
            'giveback-platform' => self::hasPanelRole($user, 'giveback-platform') || self::hasPanelRole($user, 'admin'),
            'timber' => self::hasPanelRole($user, 'timber') || self::hasPanelRole($user, 'admin'),
            'metalworks' => self::hasPanelRole($user, 'metalworks') || self::hasPanelRole($user, 'admin'),
            'customer' => self::hasPanelRole($user, 'customer'),
            'supplier' => self::hasPanelRole($user, 'supplier'),
            default => false,
        };
    }

    public static function hasFeature(mixed $user, string $feature, bool $default = false): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        // Account-level configuration is authoritative when it addresses the feature.
        $accountFeatures = self::accountMatrixFor($user)['features'] ?? [];
        $accountState = self::resolveFeatureState($accountFeatures, $feature);
        if ($accountState !== null) {
            return $accountState;
        }

        // Roles are additive: a feature granted (true) by any role wins over a
        // deny (false) coming from another role of the same user.
        $sawTrue = false;
        $sawFalse = false;

        foreach ($user->getRoleNames() as $roleName) {
            $roleKey = is_string($roleName) ? $roleName : '';
            $roleFeatures = config('account_features.roles.'.$roleKey.'.features', []);
            if (! is_array($roleFeatures)) {
                continue;
            }

            $state = self::resolveFeatureState($roleFeatures, $feature);
            if ($state === true) {
                $sawTrue = true;
            } elseif ($state === false) {
                $sawFalse = true;
            }
        }

        if ($sawTrue) {
            return true;
        }

        if ($sawFalse) {
            return false;
        }

        return $default;
    }

    public static function hasAccountFeature(mixed $user, string $feature, bool $default = false): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        $accountMatrix = self::accountMatrixFor($user);

        return self::resolveFeature($accountMatrix['features'] ?? [], $feature, $default);
    }

    public static function logoFor(mixed $user): ?string
    {
        if (! $user instanceof User) {
            return null;
        }

        /** @var string|null $logo */
        $logo = self::accountMatrixFor($user)['logo'] ?? null;

        return $logo;
    }

    /**
     * @return array<int, string>
     */
    public static function permissionOverridesFor(mixed $user): array
    {
        if (! $user instanceof User) {
            return [];
        }

        /** @var array<int, string> $permissions */
        $permissions = self::accountMatrixFor($user)['permissions'] ?? [];

        return $permissions;
    }

    public static function hasAssignedPerimeter(mixed $user): bool
    {
        return self::hasAccountFeature($user, 'admin.assigned_perimeter');
    }

    public static function hasGlobalAdminAccess(mixed $user): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        if (self::hasAdministrativeRole($user) && $user->hasRole('super_admin')
                || $user->hasRole('super-admin')) {
            return true;
        }

        return self::hasAdministrativeRole($user) && ! self::hasAssignedPerimeter($user);
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

        $panelId = self::normalizePanelId(filament()->getCurrentPanel()?->getId());

        if ($panelId === 'admin') {
            return $adminFeature !== null
                ? self::hasFeature($user, $adminFeature, $adminDefault)
                : $adminDefault;
        }

        if ($panelId === 'operator') {
            return $operatorFeature !== null
                ? self::hasFeature($user, $operatorFeature, $operatorDefault)
                : $operatorDefault;
        }

        if ($panelId === 'wts') {
            return self::hasFeature($user, 'wts.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'dds') {
            return self::hasFeature($user, 'dds.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'thermo') {
            return self::hasFeature($user, 'thermo.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'energy-broker') {
            return self::hasFeature($user, 'energy_broker.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'asd-soci') {
            return self::hasFeature($user, 'asd.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'giveback-platform') {
            return self::hasFeature($user, 'giveback.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'timber') {
            return self::hasFeature($user, 'timber.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'customer') {
            return self::hasFeature($user, 'customer.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        if ($panelId === 'supplier') {
            return self::hasFeature($user, 'supplier.'.($adminFeature ?? $operatorFeature ?? 'access'), true);
        }

        return match (true) {
            request()->is('admin') || request()->is('admin/*') => $adminFeature !== null
                ? self::hasFeature($user, $adminFeature, $adminDefault)
                : $adminDefault,
            request()->is('operator') || request()->is('operator/*') => $operatorFeature !== null
                ? self::hasFeature($user, $operatorFeature, $operatorDefault)
                : $operatorDefault,
            request()->is('wts') || request()->is('wts/*') => self::hasFeature($user, 'wts.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('dds') || request()->is('dds/*') => self::hasFeature($user, 'dds.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('thermo') || request()->is('thermo/*') => self::hasFeature($user, 'thermo.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('energy-broker') || request()->is('energy-broker/*') => self::hasFeature($user, 'energy_broker.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('asd-soci') || request()->is('asd-soci/*') => self::hasFeature($user, 'asd.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('giveback-platform') || request()->is('giveback-platform/*') => self::hasFeature($user, 'giveback.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('timber') || request()->is('timber/*') => self::hasFeature($user, 'timber.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('customer') || request()->is('customer/*') => self::hasFeature($user, 'customer.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            request()->is('supplier') || request()->is('supplier/*') => self::hasFeature($user, 'supplier.'.($adminFeature ?? $operatorFeature ?? 'access'), true),
            default => $fallback,
        };
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function scopeAssignedWorkOrders(Builder $query, User $user, string $workOrdersTable = 'work_orders'): Builder
    {
        return $query->whereExists(function (\Illuminate\Database\Query\Builder $subQuery) use ($user, $workOrdersTable): void {
            $subQuery->selectRaw('1')
                ->from('work_order_assignments')
                ->whereColumn('work_order_assignments.work_order_id', $workOrdersTable.'.id')
                ->where('work_order_assignments.user_id', $user->id);
        });
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function scopeAssignedInterventions(Builder $query, User $user, string $interventionsTable = 'interventions'): Builder
    {
        return $query->where(function (Builder $q) use ($user, $interventionsTable): void {
            // Via work order assignment
            $q->whereExists(function (\Illuminate\Database\Query\Builder $sub) use ($user, $interventionsTable): void {
                $sub->selectRaw('1')
                    ->from('work_order_assignments')
                    ->whereColumn('work_order_assignments.work_order_id', $interventionsTable.'.work_order_id')
                    ->where('work_order_assignments.user_id', $user->id);
            })
            // Direct-customer intervention: customer must be in the perimeter
                ->orWhere(function (Builder $direct) use ($user, $interventionsTable): void {
                    $direct->whereNotNull($interventionsTable.'.customer_id')
                        ->whereExists(function (\Illuminate\Database\Query\Builder $sub) use ($user, $interventionsTable): void {
                            $sub->selectRaw('1')
                                ->from('work_orders')
                                ->join('work_order_assignments', 'work_order_assignments.work_order_id', '=', 'work_orders.id')
                                ->whereColumn('work_orders.customer_id', $interventionsTable.'.customer_id')
                                ->where('work_order_assignments.user_id', $user->id);
                        });
                });
        });
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public static function scopeAssignedCustomers(Builder $query, User $user, string $customersTable = 'customers'): Builder
    {
        return $query->whereExists(function (\Illuminate\Database\Query\Builder $subQuery) use ($user, $customersTable): void {
            $subQuery->selectRaw('1')
                ->from('work_orders')
                ->join('work_order_assignments', 'work_order_assignments.work_order_id', '=', 'work_orders.id')
                ->whereColumn('work_orders.customer_id', $customersTable.'.id')
                ->where('work_order_assignments.user_id', $user->id);
        });
    }

    /**
     * @return array{panels: array<string, bool>, features: array<string, bool>, permissions: array<int, string>}
     */
    private static function matrixFor(User $user): array
    {
        $matrix = [
            'panels' => [],
            'features' => [],
            'permissions' => [],
        ];

        foreach ($user->getRoleNames() as $roleName) {
            $roleKey = is_string($roleName) ? $roleName : '';

            /** @var array{panels?: array<string, bool>, features?: array<string, bool>, permissions?: array<int, string>} $roleMatrix */
            $roleMatrix = config('account_features.roles.'.$roleKey, []);
            $matrix = self::mergeMatrix($matrix, $roleMatrix);
        }

        $accountMatrix = self::accountMatrixFor($user);

        return self::mergeMatrix($matrix, $accountMatrix);
    }

    /**
     * @return array{panels?: array<string, bool>, features?: array<string, bool>, permissions?: array<int, string>}
     */
    private static function accountMatrixFor(User $user): array
    {
        $email = strtolower((string) $user->email);

        /** @var array<string, array{panels?: array<string, bool>, features?: array<string, bool>, permissions?: array<int, string>}> $accounts */
        $accounts = config('account_features.accounts', []);

        return $accounts[$email] ?? [];
    }

    /**
     * @param  array{panels: array<string, bool>, features: array<string, bool>, permissions: array<int, string>}  $base
     * @param  array{panels?: array<string, bool>, features?: array<string, bool>, permissions?: array<int, string>}  $override
     * @return array{panels: array<string, bool>, features: array<string, bool>, permissions: array<int, string>}
     */
    private static function mergeMatrix(array $base, array $override): array
    {
        foreach (($override['panels'] ?? []) as $panel => $enabled) {
            $base['panels'][$panel] = (bool) $enabled;
        }

        foreach (($override['features'] ?? []) as $feature => $enabled) {
            $base['features'][$feature] = (bool) $enabled;
        }

        $base['permissions'] = array_values(array_unique([
            ...$base['permissions'],
            ...($override['permissions'] ?? []),
        ]));

        return $base;
    }

    /**
     * @param  array<string, bool>  $features
     */
    private static function resolveFeature(array $features, string $feature, bool $default): bool
    {
        return self::resolveFeatureState($features, $feature) ?? $default;
    }

    /**
     * Resolve a feature against a single feature map, honouring exact keys first
     * and then wildcard patterns. Returns null when the map does not address it.
     *
     * @param  array<array-key, mixed>  $features
     */
    private static function resolveFeatureState(array $features, string $feature): ?bool
    {
        if (array_key_exists($feature, $features)) {
            return (bool) $features[$feature];
        }

        $matched = null;

        foreach ($features as $pattern => $enabled) {
            if (is_string($pattern) && str_contains($pattern, '*') && Str::is($pattern, $feature)) {
                $matched = (bool) $enabled;
            }
        }

        return $matched;
    }
}
