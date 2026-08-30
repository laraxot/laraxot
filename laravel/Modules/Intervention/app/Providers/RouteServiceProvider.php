<?php

declare(strict_types=1);

namespace Modules\Intervention\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Intervention';

    protected string $module_dir = __DIR__;

    protected string $moduleNamespace = 'Modules\Intervention\Http\Controllers';
}
