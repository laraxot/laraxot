<?php

declare(strict_types=1);

namespace Modules\Compliance\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class ComplianceServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Compliance';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
