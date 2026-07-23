<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\CustomerSite;

final class SoftDeleteCustomerSiteAction
{
    public function execute(CustomerSite $site): void
    {
        $site->delete();
    }
}
