<?php

declare(strict_types=1);

namespace Modules\Compliance\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\Equipment;
use Modules\Compliance\Models\Subcontractor;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Compliance\Policies\ComplianceDocumentPolicy;
use Modules\Compliance\Policies\EquipmentPolicy;
use Modules\Compliance\Policies\SubcontractorPolicy;
use Modules\Compliance\Policies\WorkOrderChangeOrderPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class ComplianceServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Compliance';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(ComplianceDocument::class, ComplianceDocumentPolicy::class);
        Gate::policy(Equipment::class, EquipmentPolicy::class);
        Gate::policy(Subcontractor::class, SubcontractorPolicy::class);
        Gate::policy(WorkOrderChangeOrder::class, WorkOrderChangeOrderPolicy::class);
    }
}
