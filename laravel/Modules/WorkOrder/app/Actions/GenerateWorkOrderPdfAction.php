<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Support\DocumentExportBridge;
use Spatie\QueueableAction\QueueableAction;

final class GenerateWorkOrderPdfAction
{
    use QueueableAction;

    public function execute(WorkOrder $workOrder): string
    {
        $workOrder->loadMissing(['customer', 'site', 'primaryContact', 'status', 'activities', 'assignments', 'statusEvents']);

        return DocumentExportBridge::pdfFromView('work_orders::pdf.work-order', [
            'workOrder' => $workOrder,
        ]);
    }
}
