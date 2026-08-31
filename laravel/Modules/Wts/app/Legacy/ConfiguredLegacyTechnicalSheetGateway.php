<?php

declare(strict_types=1);

namespace Modules\Wts\Legacy;

use Illuminate\Database\ConnectionResolverInterface;

final readonly class ConfiguredLegacyTechnicalSheetGateway implements LegacyTechnicalSheetGateway
{
    public function __construct(
        private ConnectionResolverInterface $connections,
    ) {}

    public function findBySheetCode(string $sheetCode): ?LegacyTechnicalSheetData
    {
        $normalizedSheetCode = trim($sheetCode);
        if ($normalizedSheetCode === '') {
            return null;
        }

        $record = $this->findInConfiguredDatabase($normalizedSheetCode)
            ?? $this->findInFallbackConfig($normalizedSheetCode);

        return $record === null ? null : $this->mapRecord($record);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findInConfiguredDatabase(string $sheetCode): ?array
    {
        $connectionName = config('wts.legacy.connection');
        $table = config('wts.legacy.table');
        $configuredSheetCodeColumn = config('wts.legacy.sheet_code_column', 'sheet_code');
        $sheetCodeColumn = is_scalar($configuredSheetCodeColumn) ? (string) $configuredSheetCodeColumn : 'sheet_code';

        if (! is_string($connectionName) || $connectionName === '' || ! is_string($table) || $table === '') {
            return null;
        }

        $row = $this->connections
            ->connection($connectionName)
            ->table($table)
            ->where($sheetCodeColumn, $sheetCode)
            ->first();

        if ($row === null) {
            return null;
        }

        /** @var array<string, mixed> $record */
        $record = (array) $row;

        return $record;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findInFallbackConfig(string $sheetCode): ?array
    {
        /** @var array<string, array<string, mixed>> $records */
        $records = config('wts.legacy.fallback_records', []);

        return $records[$sheetCode] ?? null;
    }

    /**
     * @param  array<string, mixed>  $record
     */
    private function mapRecord(array $record): LegacyTechnicalSheetData
    {
        /** @var array<string, string> $mapping */
        $mapping = config('wts.legacy.columns', []);

        return new LegacyTechnicalSheetData(
            sheetCode: $this->stringValue($record[$mapping['sheet_code'] ?? 'sheet_code'] ?? null),
            producerName: $this->stringValue($record[$mapping['producer_name'] ?? 'producer_name'] ?? null),
            wasteCode: $this->stringValue($record[$mapping['waste_code'] ?? 'waste_code'] ?? null),
            hpCodes: $this->nullableStringValue($record[$mapping['hp_codes'] ?? 'hp_codes'] ?? null),
            packagingDescription: $this->nullableStringValue($record[$mapping['packaging_description'] ?? 'packaging_description'] ?? null),
            productionDescription: $this->nullableStringValue($record[$mapping['production_description'] ?? 'production_description'] ?? null),
            note1: $this->nullableStringValue($record[$mapping['note_1'] ?? 'note_1'] ?? null),
            note2: $this->nullableStringValue($record[$mapping['note_2'] ?? 'note_2'] ?? null),
            payload: $record,
        );
    }

    private function stringValue(mixed $value): string
    {
        return trim(is_scalar($value) ? (string) $value : '');
    }

    private function nullableStringValue(mixed $value): ?string
    {
        $normalized = $this->stringValue($value);

        return $normalized === '' ? null : $normalized;
    }
}
