<?php

declare(strict_types=1);

namespace Modules\Intervention\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class InterventionServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Intervention';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
