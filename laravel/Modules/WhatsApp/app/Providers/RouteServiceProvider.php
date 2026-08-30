<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'WhatsApp';

    protected string $module_dir = __DIR__;

    protected string $moduleNamespace = 'Modules\WhatsApp\Http\Controllers';
}
