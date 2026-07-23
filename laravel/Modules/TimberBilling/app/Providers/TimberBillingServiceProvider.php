<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class TimberBillingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'TimberBilling';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
