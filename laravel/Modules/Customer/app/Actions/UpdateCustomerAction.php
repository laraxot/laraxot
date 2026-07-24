<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;
use Spatie\QueueableAction\QueueableAction;

final class UpdateCustomerAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Customer $customer, array $data): Customer
    {
        $customer->fill($data)->save();

        return $customer;
    }
}
