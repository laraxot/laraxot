<?php

declare(strict_types=1);

namespace Modules\Auth\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class AuthServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Auth';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
