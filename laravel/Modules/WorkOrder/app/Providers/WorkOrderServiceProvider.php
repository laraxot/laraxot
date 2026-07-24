<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Policies\WorkOrderPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class WorkOrderServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'WorkOrder';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(WorkOrder::class, WorkOrderPolicy::class);
    }
}
