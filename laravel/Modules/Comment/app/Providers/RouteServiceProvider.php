<?php

declare(strict_types=1);

namespace Modules\Comment\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Comment';

    /** @deprecated Vietato Http\Controllers — Folio + Actions */
    protected string $moduleNamespace = 'Modules\Comment\Http\Controllers';

    protected string $moduleDir = __DIR__;

    protected string $moduleNs = __NAMESPACE__;
}
