<?php

declare(strict_types=1);

namespace Modules\Production\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Production\Models\ProductionOrder;
use Modules\Production\Models\ProductionPhase;
use Modules\Production\Policies\ProductionOrderPolicy;
use Modules\Production\Policies\ProductionPhasePolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class ProductionServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Production';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(ProductionOrder::class, ProductionOrderPolicy::class);
        Gate::policy(ProductionPhase::class, ProductionPhasePolicy::class);
    }
}
