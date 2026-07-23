<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;

final class CreateCustomerSiteAction
{
    public function execute(Customer $customer, array $data): CustomerSite
    {
        return $customer->sites()->create($data);
    }
}
