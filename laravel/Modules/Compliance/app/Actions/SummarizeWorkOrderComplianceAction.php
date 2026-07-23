<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\WorkOrder\Models\WorkOrder;

final class SummarizeWorkOrderComplianceAction
{
    public function execute(WorkOrder $workOrder): string
    {
        $readiness = app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);
        $documents = ComplianceDocument::query()->where('work_order_id', $workOrder->id)->count();
        $pendingChanges = WorkOrderChangeOrder::query()
            ->where('work_order_id', $workOrder->id)
            ->whereIn('status', ['proposed', 'pending_approval'])
            ->count();

        $status = match ($readiness['status']) {
            'green' => 'verde: la commessa risulta pronta sui controlli configurati.',
            'yellow' => 'giallo: ci sono avvisi da risolvere prima di chiudere o fatturare.',
            default => 'rosso: ci sono elementi bloccanti prima di iniziare o consegnare.',
        };

        return sprintf(
            'Semaforo %s Documenti collegati: %d. Elementi bloccanti: %d. Avvisi: %d. Extra lavori non approvati: %d.',
            $status,
            $documents,
            (int) $readiness['blocking'],
            (int) $readiness['warnings'],
            $pendingChanges,
        );
    }
}
