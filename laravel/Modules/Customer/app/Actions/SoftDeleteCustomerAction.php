<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;
use Spatie\QueueableAction\QueueableAction;

final class SoftDeleteCustomerAction
{
    use QueueableAction;

    public function execute(Customer $customer): void
    {
        $customer->delete();
    }
}
