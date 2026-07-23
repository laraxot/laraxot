<?php

declare(strict_types=1);

namespace Modules\Signature\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class SignatureServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Signature';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
