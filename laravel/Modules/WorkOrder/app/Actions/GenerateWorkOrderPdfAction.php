<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\WorkOrder\Models\WorkOrder;

final class GenerateWorkOrderPdfAction
{
    public function execute(WorkOrder $workOrder): string
    {
        $workOrder->loadMissing(['customer', 'site', 'primaryContact', 'status', 'activities', 'assignments', 'statusEvents']);

        return Pdf::loadView('work_orders::pdf.work-order', [
            'workOrder' => $workOrder,
        ])->output();
    }
}

