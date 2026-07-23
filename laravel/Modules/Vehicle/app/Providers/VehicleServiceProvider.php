<?php

declare(strict_types=1);

namespace Modules\Vehicle\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class VehicleServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Vehicle';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
