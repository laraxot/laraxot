<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusByCodeAction;

final class FinalizeInterventionReportAction
{
    /**
     * @param  'completed'|'suspended'|'quotation'  $outcomeCode
     */
    public function execute(
        Intervention $intervention,
        int $validatedByUserId,
        int $travelKm,
        int $travelMinutes,
        ?string $notes,
        string $outcomeCode,
    ): Intervention {
        $now = CarbonImmutable::now();

        // Ensure current operator punch is closed if any.
        try {
            app(StopTimePunchAction::class)->execute($intervention, $validatedByUserId, $now);
        } catch (\Throwable) {
            // No open punch for this intervention: ok.
        }

        // Sync computed minutes from time punches.
        app(SyncInterventionWorkMinutesFromTimePunchesAction::class)->execute($intervention->refresh());

        $intervention->forceFill([
            'travel_km' => $travelKm,
            'travel_minutes' => $travelMinutes,
            'notes' => $notes,
            'notes_html' => filled($notes) ? DailyNoteHtml::sanitize(DailyNoteHtml::legacyToHtml($notes)) : null,
            'outcome_code' => $outcomeCode,
            'validated_at' => $now,
            'validated_by_user_id' => $validatedByUserId,
        ])->save();

        $workOrderId = (int) $intervention->work_order_id;

        // Mirror legacy "Concluso/Sospeso/Preventivo" by updating the WorkOrder status + activity.
        if ($workOrderId > 0) {
            if ($outcomeCode === 'completed') {
                app(ChangeWorkOrderStatusByCodeAction::class)->execute(
                    workOrderId: $workOrderId,
                    toStatusCode: 'completed',
                    changedByUserId: $validatedByUserId,
                    note: 'Completed from intervention validation.',
                );
            } elseif ($outcomeCode === 'suspended') {
                app(ChangeWorkOrderStatusByCodeAction::class)->execute(
                    workOrderId: $workOrderId,
                    toStatusCode: 'in_progress',
                    changedByUserId: $validatedByUserId,
                    note: 'Suspended from intervention validation.',
                );
            } elseif ($outcomeCode === 'quotation') {
                app(ChangeWorkOrderStatusByCodeAction::class)->execute(
                    workOrderId: $workOrderId,
                    toStatusCode: 'in_progress',
                    changedByUserId: $validatedByUserId,
                    note: 'Quotation requested from intervention validation.',
                );
                app(AddWorkOrderActivityAction::class)->execute(
                    workOrderId: $workOrderId,
                    type: 'note',
                    body: 'Quotation requested by operator.',
                    createdByUserId: $validatedByUserId,
                    occurredAt: $now,
                );
            }
        }

        return $intervention->refresh();
    }
}
