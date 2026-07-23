<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class PublicProcurementServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'PublicProcurement';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
