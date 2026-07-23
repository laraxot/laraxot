<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class WorkOrderServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'WorkOrder';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
