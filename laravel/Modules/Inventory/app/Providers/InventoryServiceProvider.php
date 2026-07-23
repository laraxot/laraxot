<?php

declare(strict_types=1);

namespace Modules\Inventory\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class InventoryServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Inventory';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
