<?php

declare(strict_types=1);

namespace Modules\Fiscal\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class FiscalServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Fiscal';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
