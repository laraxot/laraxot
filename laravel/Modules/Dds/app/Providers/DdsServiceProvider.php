<?php

declare(strict_types=1);

namespace Modules\Dds\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class DdsServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Dds';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
