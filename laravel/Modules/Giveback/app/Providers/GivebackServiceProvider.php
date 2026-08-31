<?php

declare(strict_types=1);

namespace Modules\Giveback\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class GivebackServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Giveback';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
