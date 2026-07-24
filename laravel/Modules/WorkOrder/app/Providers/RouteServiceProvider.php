<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'WorkOrder';

    protected string $moduleNamespace = 'Modules\WorkOrder\Http\Controllers';
}
