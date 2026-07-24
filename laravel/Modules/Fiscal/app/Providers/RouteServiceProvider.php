<?php

declare(strict_types=1);

namespace Modules\Fiscal\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Fiscal';

    protected string $moduleNamespace = 'Modules\Fiscal\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
