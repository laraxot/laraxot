<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Support\DailyNoteHtml;
use Spatie\QueueableAction\QueueableAction;

/**
 * Persists a voice-generated report draft that the operator has reviewed and
 * confirmed. Dictated materials are appended (they are extras on top of what
 * was already logged) and the materials total is recomputed from all lines.
 *
 * Only scalar fields that the draft actually carries are overwritten, so an
 * empty transcript never wipes existing intervention data.
 */
final class ApplyInterventionReportDraftAction
{
    use QueueableAction;

    public function execute(Intervention $intervention, InterventionReportDraft $draft): Intervention
    {
        $attributes = [];

        if ($draft->notes !== '') {
            $attributes['notes'] = $draft->notes;
            $attributes['notes_html'] = DailyNoteHtml::sanitize(DailyNoteHtml::legacyToHtml($draft->notes));
        }

        if ($draft->workMinutes !== null) {
            $attributes['work_minutes'] = $draft->workMinutes;
        }

        if ($draft->outcomeCode !== null) {
            $attributes['outcome_code'] = $draft->outcomeCode;
        }

        if ($attributes !== []) {
            $intervention->forceFill($attributes)->save();
        }

        $lines = array_map(
            static function (DictatedMaterialLine $material): array {
                $quantity = round($material->quantity, 2);
                $unitPrice = round($material->unitPrice ?? 0.0, 2);

                return [
                    'description' => $material->description,
                    'quantity' => $quantity,
                    'unit' => 'pz',
                    'unit_price' => $unitPrice,
                    'line_total' => round($quantity * $unitPrice, 2),
                ];
            },
            $draft->materials,
        );

        if ($lines !== []) {
            $intervention->materialLines()->createMany($lines);
        }

        $intervention->forceFill([
            'materials_total' => round((float) $intervention->materialLines()->sum('line_total'), 2),
        ])->save();

        return $intervention->refresh();
    }
}
