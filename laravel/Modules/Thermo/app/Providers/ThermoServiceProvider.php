<?php

declare(strict_types=1);

namespace Modules\Thermo\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class ThermoServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Thermo';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
