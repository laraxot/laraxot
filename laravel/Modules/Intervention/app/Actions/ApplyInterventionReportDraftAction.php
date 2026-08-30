<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Settings\InterventionTariffSettings;
use Modules\Intervention\Support\DailyNoteHtml;
use Spatie\QueueableAction\QueueableAction;

/**
 * Persists a voice-generated report draft that the operator has reviewed and
 * confirmed. Dictated materials are appended (they are extras on top of what
 * was already logged) and the economic totals are recomputed from all lines.
 *
 * Only scalar fields that the draft actually carries are overwritten, so an
 * empty transcript never wipes existing intervention data. The same rule
 * governs the money: labour, the callout fee and the travelled kilometres are
 * filled only when they are still empty, because a figure typed by a human
 * outranks one derived from a tariff.
 */
final class ApplyInterventionReportDraftAction
{
    use QueueableAction;

    public function __construct(
        private readonly InterventionTariffSettings $tariffSettings,
        private readonly ResolveInterventionPricingAction $resolvePricing,
        private readonly ResolveDictatedOperatorsAction $resolveOperators,
    ) {}

    public function execute(Intervention $intervention, InterventionReportDraft $draft, ?int $userId = null): Intervention
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

        $attributes += $this->resolvePricing->execute(
            currentAmount: $this->toNullableFloat($intervention->amount),
            currentCalloutFee: $this->toNullableFloat($intervention->callout_fee),
            currentTravelKm: $intervention->travel_km !== null ? (int) $intervention->travel_km : null,
            draft: $draft,
            tariff: $this->tariffSettings->current(),
            performedAt: $intervention->started_at ?? $intervention->scheduled_at,
            fallbackMinutes: (int) ($intervention->work_minutes ?? 0),
        )->attributes();

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
                    'unit' => $material->unit,
                    'unit_price' => $unitPrice,
                    'line_total' => round($quantity * $unitPrice, 2),
                ];
            },
            $draft->materials,
        );

        if ($lines !== []) {
            $intervention->materialLines()->createMany($lines);
        }

        $this->recordIssues($intervention, $draft, $userId);
        $this->attachOperators($intervention, $draft);

        // Shared with every other write path (Filament, inventory movements,
        // the AI assistant). Recomputing materials_total by hand here is what
        // left total_amount stale, and total_amount is the figure the customer
        // report and the work-order rendicontazione actually print.
        $intervention->refreshEconomicTotals();

        return $intervention->refresh();
    }

    private function recordIssues(Intervention $intervention, InterventionReportDraft $draft, ?int $userId): void
    {
        foreach ($draft->issues as $body) {
            $intervention->issueReports()->create([
                'body' => $body,
                'created_by_user_id' => $userId,
            ]);
        }
    }

    private function attachOperators(Intervention $intervention, InterventionReportDraft $draft): void
    {
        if ($draft->operatorNames === []) {
            return;
        }

        $ids = $this->resolveOperators->execute($draft->operatorNames);

        if ($ids !== []) {
            // Without detaching: the dictation names who else was there, it
            // does not claim to be the full crew list.
            $intervention->assignedUsers()->syncWithoutDetaching($ids);
        }
    }

    private function toNullableFloat(mixed $value): ?float
    {
        return is_numeric($value) ? (float) $value : null;
    }
}
