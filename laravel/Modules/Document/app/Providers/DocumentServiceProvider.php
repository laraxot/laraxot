<?php

declare(strict_types=1);

namespace Modules\Document\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class DocumentServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Document';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
