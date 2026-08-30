<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * AI-generated draft of an intervention report ("rapportino") built from an
 * operator's voice note. It is a *proposal*: the operator reviews and confirms
 * it before anything is persisted.
 */
final readonly class InterventionReportDraft
{
    /**
     * @param  list<DictatedMaterialLine>  $materials
     * @param  list<string>  $issues  Faults noticed on site but not fixed — the raw material of the next quotation.
     * @param  list<string>  $operatorNames  People the operator says were on the job, as dictated.
     * @param  ?float  $suggestedAmount  A total the operator quoted out loud — the whole job, not the labour.
     * @param  ?float  $labourAmount  Labour explicitly priced by the operator; overrides the tariff.
     * @param  ?float  $calloutFee  Callout fee explicitly mentioned; overrides the configured default.
     */
    public function __construct(
        public string $notes,
        public ?int $workMinutes,
        public ?string $outcomeCode,
        public array $materials,
        public ?float $suggestedAmount = null,
        public array $issues = [],
        public array $operatorNames = [],
        public ?int $travelKm = null,
        public ?float $labourAmount = null,
        public ?float $calloutFee = null,
    ) {}

    public function materialsTotal(): float
    {
        $total = 0.0;
        foreach ($this->materials as $material) {
            $total += $material->lineTotal();
        }

        return round($total, 2);
    }

    /**
     * True when at least one dictated material could not be matched to the
     * catalog, so it needs the operator's attention (price/description).
     */
    public function hasUnmatchedMaterials(): bool
    {
        foreach ($this->materials as $material) {
            if (! $material->isMatched()) {
                return true;
            }
        }

        return false;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'notes' => $this->notes,
            'work_minutes' => $this->workMinutes,
            'outcome_code' => $this->outcomeCode,
            'suggested_amount' => $this->suggestedAmount,
            'travel_km' => $this->travelKm,
            'labour_amount' => $this->labourAmount,
            'callout_fee' => $this->calloutFee,
            'issues' => $this->issues,
            'operator_names' => $this->operatorNames,
            'materials' => array_map(
                static fn (DictatedMaterialLine $material): array => $material->toArray(),
                $this->materials,
            ),
            'materials_total' => $this->materialsTotal(),
            'has_unmatched_materials' => $this->hasUnmatchedMaterials(),
        ];
    }
}
