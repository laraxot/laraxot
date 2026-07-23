<?php

declare(strict_types=1);

namespace Modules\Auth\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Auth';

    protected string $moduleNamespace = 'Modules\Auth\Http\Controllers';
}
