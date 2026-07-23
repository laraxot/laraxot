<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;

final class RestoreCustomerCascadeAction
{
    public function execute(Customer $customer): void
    {
        if (! $customer->trashed()) {
            return;
        }

        $customer->restore();

        CustomerSite::query()
            ->onlyTrashed()
            ->where('customer_id', $customer->id)
            ->restore();

        Contact::query()
            ->onlyTrashed()
            ->where('customer_id', $customer->id)
            ->restore();
    }
}
