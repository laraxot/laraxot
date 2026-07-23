<?php

declare(strict_types=1);

namespace Modules\Billing\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class BillingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Billing';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
