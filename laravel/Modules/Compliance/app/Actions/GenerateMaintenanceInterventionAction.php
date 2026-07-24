<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Carbon\CarbonInterface;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Intervention\Models\Intervention;
use Spatie\QueueableAction\QueueableAction;

/**
 * Creates a scheduled intervention for a due heating-system maintenance or
 * efficiency control, linked to the customer of the system. The technician then
 * carries it out and its result is recorded as a MaintenanceControl.
 */
final class GenerateMaintenanceInterventionAction
{
    use QueueableAction;

    public function execute(
        HeatingSystem $system,
        ?Boiler $boiler,
        CarbonInterface $scheduledAt,
        int $performedByUserId,
        string $controlType = 'maintenance',
    ): Intervention {
        $label = $controlType === 'efficiency_check'
            ? 'Controllo efficienza energetica'
            : 'Manutenzione impianto termico';

        if ($boiler !== null) {
            $descriptor = trim(implode(' ', array_filter([$boiler->brand, $boiler->model])));
            if ($descriptor !== '') {
                $label .= ' — '.$descriptor;
            }
        }

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->create([
            'work_order_id' => null,
            'customer_id' => $system->customer_id,
            'performed_by_user_id' => $performedByUserId,
            'scheduled_at' => $scheduledAt,
            'work_minutes' => 0,
            'materials_total' => 0,
            'notes' => $label,
        ]);

        return $intervention;
    }
}
