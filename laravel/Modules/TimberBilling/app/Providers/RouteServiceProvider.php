<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'TimberBilling';

    protected string $moduleNamespace = 'Modules\TimberBilling\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
