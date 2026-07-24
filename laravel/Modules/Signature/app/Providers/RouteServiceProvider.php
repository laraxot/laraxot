<?php

declare(strict_types=1);

namespace Modules\Signature\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Signature';

    protected string $moduleNamespace = 'Modules\Signature\Http\Controllers';
}
