<?php

declare(strict_types=1);

namespace Modules\HR\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'HR';

    protected string $moduleNamespace = 'Modules\HR\Http\Controllers';
}
