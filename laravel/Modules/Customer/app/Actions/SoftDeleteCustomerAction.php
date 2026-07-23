<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;

final class SoftDeleteCustomerAction
{
    public function execute(Customer $customer): void
    {
        $customer->delete();
    }
}
