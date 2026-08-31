<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class AsdSociServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'AsdSoci';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
