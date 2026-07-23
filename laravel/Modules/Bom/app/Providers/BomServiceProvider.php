<?php

declare(strict_types=1);

namespace Modules\Bom\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class BomServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Bom';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
