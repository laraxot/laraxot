<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;

final class UpdateCustomerAction
{
    public function execute(Customer $customer, array $data): Customer
    {
        $customer->fill($data)->save();

        return $customer;
    }
}
