<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class EnergyBrokerServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'EnergyBroker';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
