<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'AsdSoci';

    protected string $moduleNamespace = 'Modules\AsdSoci\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
