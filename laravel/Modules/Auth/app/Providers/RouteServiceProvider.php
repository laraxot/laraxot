<?php

declare(strict_types=1);

namespace Modules\Auth\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider
{
    public string $name = 'Auth';

    protected string $moduleNamespace = 'Modules\Auth\Http\Controllers';

    /**
     * Bug reale: XotBaseRouteServiceProvider::$module_dir di default vale
     * __DIR__ della classe astratta (Modules/Xot/app/Providers), non del
     * modulo che estende la classe (le proprietà con default __DIR__ non
     * vengono ri-valutate nelle sottoclassi). Senza questo override,
     * `mapWebRoutes()`/`mapApiRoutes()` caricano Modules/Xot/routes/{web,api}.php
     * (quasi vuoto) invece di Modules/Auth/routes/{web,api}.php: nessuna rotta
     * legacy (/login, /register, ecc.) veniva mai registrata.
     */
    protected string $module_dir = __DIR__;
}
