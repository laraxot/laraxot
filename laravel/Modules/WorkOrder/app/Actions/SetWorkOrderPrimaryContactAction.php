<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;

final class SetWorkOrderPrimaryContactAction
{
    public function execute(WorkOrder $workOrder, ?int $contactId): WorkOrder
    {
        $workOrder->primary_contact_id = $contactId !== null ? max(0, $contactId) : null;
        $workOrder->save();

        return $workOrder;
    }
}

