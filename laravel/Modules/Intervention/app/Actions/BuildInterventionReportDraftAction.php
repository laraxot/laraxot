<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\AI\InterventionReportExtractorInterface;
use Modules\Intervention\Domain\DTO\DictatedMaterialLine;
use Modules\Intervention\Domain\DTO\InterventionReportDraft;

/**
 * Orchestrates voice → report draft: runs the LLM extractor over the transcript,
 * then normalizes and validates its untrusted output into a typed draft with
 * catalog-matched materials.
 */
final class BuildInterventionReportDraftAction
{
    /** @var list<string> */
    private const ALLOWED_OUTCOMES = ['completed', 'suspended', 'quotation'];

    public function __construct(
        private readonly InterventionReportExtractorInterface $extractor,
        private readonly MatchDictatedMaterialsToCatalogAction $matcher,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $pastInterventions
     */
    public function execute(string $transcript, array $pastInterventions = []): InterventionReportDraft
    {
        $raw = $this->extractor->extract($transcript, $pastInterventions);

        $materials = $this->matcher->execute(
            $this->parseMaterials($raw['materials'] ?? null),
        );

        return new InterventionReportDraft(
            notes: $this->parseString($raw['notes'] ?? ''),
            workMinutes: $this->parseNullablePositiveInt($raw['work_minutes'] ?? null),
            outcomeCode: $this->parseOutcome($raw['outcome_code'] ?? null),
            materials: $materials,
            suggestedAmount: $this->parseNullablePositiveFloat($raw['suggested_amount'] ?? null),
        );
    }

    /**
     * @return list<DictatedMaterialLine>
     */
    private function parseMaterials(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $lines = [];
        foreach ($value as $item) {
            if (! is_array($item)) {
                continue;
            }

            $description = $this->parseString($item['description'] ?? '');
            if ($description === '') {
                continue;
            }

            $quantity = $this->parseNullablePositiveFloat($item['quantity'] ?? null) ?? 1.0;

            $lines[] = new DictatedMaterialLine(
                description: $description,
                quantity: $quantity,
                unitPrice: $this->parseNullablePositiveFloat($item['unit_price'] ?? null),
            );
        }

        return $lines;
    }

    private function parseString(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private function parseOutcome(mixed $value): ?string
    {
        return is_string($value) && in_array($value, self::ALLOWED_OUTCOMES, true)
            ? $value
            : null;
    }

    private function parseNullablePositiveInt(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        $int = (int) $value;

        return $int > 0 ? $int : null;
    }

    private function parseNullablePositiveFloat(mixed $value): ?float
    {
        if (! is_numeric($value)) {
            return null;
        }

        $float = (float) $value;

        return $float > 0 ? $float : null;
    }
}
