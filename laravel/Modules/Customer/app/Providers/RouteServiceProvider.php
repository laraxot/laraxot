<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Customer';

    protected string $moduleNamespace = 'Modules\Customer\Http\Controllers';
}
