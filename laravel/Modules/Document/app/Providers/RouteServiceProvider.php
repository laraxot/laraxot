<?php

declare(strict_types=1);

namespace Modules\Document\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Document';

    protected string $moduleNamespace = 'Modules\Document\Http\Controllers';
}
