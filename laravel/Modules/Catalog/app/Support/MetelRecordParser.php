<?php

declare(strict_types=1);

namespace Modules\Catalog\Support;

use Modules\Catalog\DTO\MetelArticleData;

use function Safe\preg_split;

/**
 * Pure parser for METEL price-list records: delimited (CSV/Excel export handed
 * over by the wholesaler) and fixed-width (official "tracciato listino"). Layout
 * offsets and column indexes are injected so the version-specific tracciato can
 * be tuned via config without touching this logic.
 */
final readonly class MetelRecordParser
{
    /**
     * @param  array<string, array{0: int, 1: int}>  $fixedLayout  field => [start, length]
     * @param  array<string, int>  $csvMap  field => 0-based column index
     */
    public function __construct(
        private array $fixedLayout,
        private int $fixedPriceDivisor,
        private array $csvMap,
    ) {}

    /**
     * Detects the format of a whole file: "csv" when the first non-empty line
     * carries a delimiter, otherwise "fixed".
     */
    public function detectFormat(string $content): string
    {
        foreach (preg_split('/\r\n|\r|\n/', $content) as $rawLine) {
            if (! is_string($rawLine)) {
                continue;
            }
            $line = $rawLine;

            if (trim($line) === '') {
                continue;
            }

            return (str_contains($line, ';') || str_contains($line, ',')) ? 'csv' : 'fixed';
        }

        return 'csv';
    }

    public function parseCsvLine(string $line): ?MetelArticleData
    {
        if (trim($line) === '') {
            return null;
        }

        // Italian Excel exports use ';' so the decimal comma survives inside prices.
        $delimiter = str_contains($line, ';') ? ';' : ',';
        $columns = str_getcsv($line, $delimiter);

        $code = trim((string) ($columns[$this->csvMap['metel_code']] ?? ''));
        $gross = $this->normalizePrice((string) ($columns[$this->csvMap['gross_price']] ?? ''));

        if ($code === '' || $gross === null) {
            return null;
        }

        return new MetelArticleData(
            metelCode: $code,
            description: trim((string) ($columns[$this->csvMap['description']] ?? '')),
            grossPrice: $gross,
            netPrice: null,
            unitOfMeasure: $this->cleanOptional($columns[$this->csvMap['unit_of_measure']] ?? null),
            packaging: $this->cleanOptional($columns[$this->csvMap['packaging']] ?? null),
            barcode: $this->cleanOptional($columns[$this->csvMap['barcode']] ?? null),
            etimCode: $this->cleanOptional($columns[$this->csvMap['etim_code']] ?? null),
        );
    }

    public function parseFixedLine(string $line): ?MetelArticleData
    {
        if (trim($line) === '') {
            return null;
        }

        $code = $this->slice($line, 'metel_code');
        $grossRaw = str_replace(',', '.', $this->slice($line, 'gross_price'));

        if ($code === '' || ! is_numeric($grossRaw)) {
            return null;
        }

        $divisor = $this->fixedPriceDivisor > 0 ? $this->fixedPriceDivisor : 1;

        return new MetelArticleData(
            metelCode: $code,
            description: $this->slice($line, 'description'),
            grossPrice: (float) $grossRaw / $divisor,
            netPrice: null,
            unitOfMeasure: $this->emptyToNull($this->slice($line, 'unit_of_measure')),
            packaging: $this->emptyToNull($this->slice($line, 'packaging')),
            barcode: $this->emptyToNull($this->slice($line, 'barcode')),
            etimCode: null,
            brandCode: $this->emptyToNull($this->slice($line, 'brand_code')),
        );
    }

    public function normalizePrice(string $raw): ?float
    {
        $value = str_replace(',', '.', trim($raw));

        return is_numeric($value) ? (float) $value : null;
    }

    private function slice(string $line, string $field): string
    {
        if (! isset($this->fixedLayout[$field])) {
            return '';
        }

        [$start, $length] = $this->fixedLayout[$field];

        return trim(substr($line, $start, $length));
    }

    private function cleanOptional(mixed $value): ?string
    {
        $string = is_scalar($value) ? trim((string) $value) : '';

        return $this->emptyToNull($string);
    }

    private function emptyToNull(string $value): ?string
    {
        return $value === '' ? null : $value;
    }
}
