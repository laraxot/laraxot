<?php

declare(strict_types=1);

namespace Modules\Platform\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Platform';

    protected string $moduleNamespace = 'Modules\Platform\Http\Controllers';
}
