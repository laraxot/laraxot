<?php

declare(strict_types=1);

namespace Modules\Quotation\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class QuotationServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Quotation';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;
}
