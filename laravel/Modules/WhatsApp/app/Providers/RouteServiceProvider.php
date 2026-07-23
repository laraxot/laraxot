<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'WhatsApp';

    protected string $moduleNamespace = 'Modules\WhatsApp\Http\Controllers';
}
