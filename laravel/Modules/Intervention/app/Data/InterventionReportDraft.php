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
     */
    public function __construct(
        public string $notes,
        public ?int $workMinutes,
        public ?string $outcomeCode,
        public array $materials,
        public ?float $suggestedAmount = null,
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
            'materials' => array_map(
                static fn (DictatedMaterialLine $material): array => $material->toArray(),
                $this->materials,
            ),
            'materials_total' => $this->materialsTotal(),
            'has_unmatched_materials' => $this->hasUnmatchedMaterials(),
        ];
    }
}
