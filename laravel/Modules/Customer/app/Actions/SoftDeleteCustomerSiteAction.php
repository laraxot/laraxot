<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Models\CustomerSite;
use Spatie\QueueableAction\QueueableAction;

final class SoftDeleteCustomerSiteAction
{
    use QueueableAction;

    public function execute(CustomerSite $site): void
    {
        $site->delete();
    }
}
