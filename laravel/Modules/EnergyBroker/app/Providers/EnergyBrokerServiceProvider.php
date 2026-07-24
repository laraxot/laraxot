<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\EnergyBroker\Models\EnergyBrokerCustomerMatch;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyHistory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;
use Modules\EnergyBroker\Policies\EnergyBrokerPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class EnergyBrokerServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'EnergyBroker';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        foreach ([
            EnergyBrokerCustomerMatch::class,
            EnergyBrokerDocument::class,
            EnergyBrokerImportBatch::class,
            EnergyBrokerLargeUpload::class,
            EnergyBrokerSupplyHistory::class,
            EnergyBrokerSupplyPoint::class,
        ] as $model) {
            Gate::policy($model, EnergyBrokerPolicy::class);
        }
    }
}
