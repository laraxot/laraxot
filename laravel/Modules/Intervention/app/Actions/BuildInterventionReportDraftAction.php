<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Contracts\InterventionReportExtractorContract;
use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Spatie\QueueableAction\QueueableAction;

/**
 * Orchestrates voice → report draft: runs the LLM extractor over the transcript,
 * then normalizes and validates its untrusted output into a typed draft with
 * catalog-matched materials.
 */
final class BuildInterventionReportDraftAction
{
    use QueueableAction;

    /** @var list<string> */
    private const array ALLOWED_OUTCOMES = ['completed', 'suspended', 'quotation'];

    public function __construct(
        private readonly InterventionReportExtractorContract $extractor,
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
            issues: $this->parseStringList($raw['issues'] ?? null),
            operatorNames: $this->parseStringList($raw['operators'] ?? null),
            travelKm: $this->parseNullablePositiveInt($raw['travel_km'] ?? null),
            labourAmount: $this->parseNullablePositiveFloat($raw['labour_amount'] ?? null),
            calloutFee: $this->parseNullablePositiveFloat($raw['callout_fee'] ?? null),
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
                unit: $this->parseUnit($item['unit'] ?? null),
            );
        }

        return $lines;
    }

    private function parseString(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    /**
     * Free-text lists coming back from the model: anything that is not a
     * non-empty string is dropped rather than coerced, and duplicates are
     * collapsed so a repeated remark does not become two records.
     *
     * @return list<string>
     */
    private function parseStringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $entry) {
            $text = $this->parseString($entry);
            if ($text === '' || in_array($text, $items, true)) {
                continue;
            }

            $items[] = $text;
        }

        return $items;
    }

    /**
     * The unit of measure decides whether "three metres of pipe" is billed as
     * three metres or three pieces, so an unrecognisable value falls back to
     * the previous hardcoded default instead of reaching the invoice.
     */
    private function parseUnit(mixed $value): string
    {
        $unit = mb_strtolower($this->parseString($value));

        return $unit !== '' && mb_strlen($unit) <= 8
            ? $unit
            : DictatedMaterialLine::DEFAULT_UNIT;
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
