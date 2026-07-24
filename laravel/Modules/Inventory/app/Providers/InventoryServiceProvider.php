<?php

declare(strict_types=1);

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\ProductUnit;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Inventory\Policies\InventoryMovementPolicy;
use Modules\Inventory\Policies\ProductUnitPolicy;
use Modules\Inventory\Policies\WarehouseLocationPolicy;
use Modules\Inventory\Policies\WarehousePolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class InventoryServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Inventory';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Warehouse::class, WarehousePolicy::class);
        Gate::policy(WarehouseLocation::class, WarehouseLocationPolicy::class);
        Gate::policy(InventoryMovement::class, InventoryMovementPolicy::class);
        Gate::policy(ProductUnit::class, ProductUnitPolicy::class);
    }
}
