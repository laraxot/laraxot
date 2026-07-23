<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class CatalogServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Catalog';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
