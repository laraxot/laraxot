<?php

declare(strict_types=1);

namespace Modules\Billing\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Policies\InvoicePolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class BillingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Billing';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Invoice::class, InvoicePolicy::class);
    }
}
