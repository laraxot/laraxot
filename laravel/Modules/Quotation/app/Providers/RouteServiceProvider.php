<?php

declare(strict_types=1);

namespace Modules\Quotation\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Quotation';

    protected string $moduleNamespace = 'Modules\Quotation\Http\Controllers';
}
