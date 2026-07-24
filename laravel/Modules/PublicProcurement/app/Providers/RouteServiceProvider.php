<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'PublicProcurement';

    protected string $moduleNamespace = 'Modules\PublicProcurement\Http\Controllers';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
