<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'EnergyBroker';

    protected string $moduleNamespace = 'Modules\EnergyBroker\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
