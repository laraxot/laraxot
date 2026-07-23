<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;

final class CreateCustomerAction
{
    public function execute(array $data): Customer
    {
        return Customer::query()->create($data);
    }
}
