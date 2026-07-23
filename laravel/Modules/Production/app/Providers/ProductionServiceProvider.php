<?php

declare(strict_types=1);

namespace Modules\Production\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class ProductionServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Production';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
