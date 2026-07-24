<?php

declare(strict_types=1);

namespace Modules\Vehicle\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Vehicle';

    protected string $moduleNamespace = 'Modules\Vehicle\Http\Controllers';
}
