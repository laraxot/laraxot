<?php

declare(strict_types=1);

namespace Modules\Email\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Email';

    protected string $module_dir = __DIR__;

    protected string $moduleNamespace = 'Modules\Email\Http\Controllers';
}
