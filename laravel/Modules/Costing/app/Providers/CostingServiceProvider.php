<?php

declare(strict_types=1);

namespace Modules\Costing\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class CostingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Costing';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
