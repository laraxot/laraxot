<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Spatie\QueueableAction\QueueableAction;

final class CreateCustomerSiteAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Customer $customer, array $data): CustomerSite
    {
        /** @var CustomerSite $site */
        $site = $customer->sites()->create($data);

        return $site;
    }
}
