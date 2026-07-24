<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;
use Spatie\QueueableAction\QueueableAction;

final class CreateCustomerAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Customer
    {
        return Customer::query()->create($data);
    }
}
