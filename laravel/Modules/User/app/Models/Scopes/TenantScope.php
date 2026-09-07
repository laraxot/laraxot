<?php

declare(strict_types=1);

namespace Modules\User\Models\Scopes;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
<<<<<<< HEAD
<<<<<<< HEAD

/**
 * Scope che limita le query ai record associati al tenant corrente.
 *
 * @implements Scope<Model>
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Tenant;

/**
 * Scope che limita le query ai record associati al tenant corrente.
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

/**
 * Scope che limita le query ai record associati al tenant corrente.
 *
 * @implements Scope<Model>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 */
class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $_model): void
    {
        $tenant_id = Filament::getTenant()?->getKey();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null !== $tenant_id) {
=======
        if ($tenant_id !== null) {
>>>>>>> f548be94 (.)
=======
        if ($tenant_id !== null) {
=======
        if (null !== $tenant_id) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $builder->where('tenant_id', '=', $tenant_id);
        }
    }
}
