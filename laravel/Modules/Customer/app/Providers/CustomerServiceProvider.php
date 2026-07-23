<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\Reseller;
use Modules\Customer\Policies\CustomerPolicy;
use Modules\Customer\Policies\ResellerPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class CustomerServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Customer';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Reseller::class, ResellerPolicy::class);
    }
}
