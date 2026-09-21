<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions\_scratch_repro;

use Modules\WorkOrder\Models\WorkOrder;

final class ReproAction
{
    public function run(WorkOrder $workOrder): string
    {
        $site = $workOrder->site;
        $customer = $workOrder->customer;

        return ($site?->city ?? $customer?->city) ?? '';
    }
}
