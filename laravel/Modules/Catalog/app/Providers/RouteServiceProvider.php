<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Catalog';

    protected string $moduleNamespace = 'Modules\Catalog\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
