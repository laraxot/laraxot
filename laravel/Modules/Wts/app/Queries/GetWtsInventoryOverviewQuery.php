<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Actions\FetchEcosStockSnapshotAction;
use Modules\Wts\Datas\WtsInventoryRow;
use Modules\Wts\Models\WtsUnit;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

final class GetWtsInventoryOverviewQuery
{
    /** @var array{attempted: bool, error: string|null, usesEcosStock: bool} */
    private array $lastEcosFetch = ['attempted' => false, 'error' => null, 'usesEcosStock' => false];

    public function __construct(
        private readonly FetchEcosStockSnapshotAction $fetchEcosStockSnapshot,
    ) {}

    /**
     * @return array{
     *     rows: SupportCollection<int, WtsInventoryRow>,
     *     stats: array{available:int, consumed:int, total:int},
     *     filters: array{wasteCodes: array<int, string>, zones: array<int, string>},
     *     dataSource: array{label: string, description: string, class: string},
     *     usesEcosStock: bool
     * }
     */
    public function execute(
        ?string $wasteCode = null,
        ?string $zone = null,
        ?string $referenceDate = null,
        string $wasteCodeOperator = 'contains',
        string $zoneOperator = 'contains',
    ): array {
        if ($this->usesEcosStock()) {
            return $this->executeFromEcos($wasteCode, $zone, $referenceDate, $wasteCodeOperator, $zoneOperator);
        }

        return $this->executeFromLocalUnits($wasteCode, $zone, $referenceDate, $wasteCodeOperator, $zoneOperator);
    }

    /**
     * @param  SupportCollection<int, WtsInventoryRow>  $rows
     * @return array{label: string, description: string, class: string}
     */
    public function dataSourceStatus(SupportCollection $rows): array
    {
        if ($this->lastEcosFetch['usesEcosStock'] && $this->lastEcosFetch['error'] === null && $rows->isNotEmpty()) {
            return [
                'label' => 'DATI REALI ECOS',
                'description' => 'La giacenza e ricostruita da ECOS con logica per partita (bspart/bscol) alla data selezionata.',
                'class' => 'border-emerald-300 bg-emerald-50 text-emerald-900 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-100',
            ];
        }

        if ($this->lastEcosFetch['usesEcosStock'] && $this->lastEcosFetch['error'] !== null) {
            return [
                'label' => 'DATI DEMO - ECOS NON RAGGIUNGIBILE',
                'description' => $this->lastEcosFetch['error'].' Nessuna riga giacenza ECOS disponibile.',
                'class' => 'border-rose-300 bg-rose-50 text-rose-900 dark:border-rose-500/40 dark:bg-rose-500/10 dark:text-rose-100',
            ];
        }

        if ($this->lastEcosFetch['usesEcosStock'] && $this->lastEcosFetch['attempted']) {
            return [
                'label' => 'DATI REALI ECOS',
                'description' => 'La query giacenza ECOS e stata eseguita, ma non ha restituito partite aperte per i filtri correnti.',
                'class' => 'border-amber-300 bg-amber-50 text-amber-900 dark:border-amber-500/40 dark:bg-amber-500/10 dark:text-amber-100',
            ];
        }

        return [
            'label' => 'DATI APPLICATIVI LOCALI',
            'description' => 'La giacenza proviene dai colli generati in WTS (wts_units). Abilita WTS_ECOS_SYNC_STOCK per la stampa ECOS per partita.',
            'class' => 'border-slate-300 bg-slate-50 text-slate-900 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100',
        ];
    }

    /**
     * @return array{
     *     rows: SupportCollection<int, WtsInventoryRow>,
     *     stats: array{available:int, consumed:int, total:int},
     *     filters: array{wasteCodes: array<int, string>, zones: array<int, string>},
     *     dataSource: array{label: string, description: string, class: string},
     *     usesEcosStock: bool
     * }
     */
    private function executeFromEcos(?string $wasteCode, ?string $zone, ?string $referenceDate, string $wasteCodeOperator, string $zoneOperator): array
    {
        $this->lastEcosFetch = [
            'attempted' => true,
            'error' => null,
            'usesEcosStock' => true,
        ];

        $snapshot = $this->fetchEcosStockSnapshot->execute($referenceDate);
        $this->lastEcosFetch['error'] = $snapshot['error'];

        /** @var SupportCollection<int, WtsInventoryRow> $allRows */
        $allRows = collect($snapshot['rows']);

        /** @var SupportCollection<int, WtsInventoryRow> $rows */
        $rows = $allRows
            ->filter(fn (WtsInventoryRow $row): bool => $this->matchesTextFilter($row->wasteCode, $wasteCode, $wasteCodeOperator))
            ->filter(fn (WtsInventoryRow $row): bool => $this->matchesTextFilter($row->zoneCode, $zone, $zoneOperator))
            ->values();

        $overview = [
            'rows' => $rows,
            'stats' => [
                'available' => $rows->where('statusCode', 'available')->count(),
                'consumed' => $rows->where('statusCode', 'finished')->count(),
                'total' => $rows->count(),
            ],
            'filters' => [
                'wasteCodes' => $this->wasteCodesFromRows($allRows),
                'zones' => $this->zonesFromRows($allRows),
            ],
            'usesEcosStock' => true,
        ];

        return [
            ...$overview,
            'dataSource' => $this->dataSourceStatus($rows),
        ];
    }

    /**
     * @return array{
     *     rows: SupportCollection<int, WtsInventoryRow>,
     *     stats: array{available:int, consumed:int, total:int},
     *     filters: array{wasteCodes: array<int, string>, zones: array<int, string>},
     *     dataSource: array{label: string, description: string, class: string},
     *     usesEcosStock: bool
     * }
     */
    private function executeFromLocalUnits(?string $wasteCode, ?string $zone, ?string $referenceDate, string $wasteCodeOperator, string $zoneOperator): array
    {
        $this->lastEcosFetch = ['attempted' => false, 'error' => null, 'usesEcosStock' => false];

        $baseQuery = WtsUnit::query()
            ->with(['inboundLot.inboundRecord.technicalSheetSnapshot', 'technicalSheetSnapshot', 'processingSteps'])
            ->latest();

        /** @var Collection<int, WtsUnit> $allUnits */
        $allUnits = $baseQuery->get()
            ->map(function (WtsUnit $unit) use ($referenceDate): WtsUnit {
                return $this->applyEffectiveSnapshotState($unit, $referenceDate);
            })
            ->filter(fn (WtsUnit $unit): bool => $this->isVisibleAtReferenceDate($unit, $referenceDate))
            ->values();

        /** @var Collection<int, WtsUnit> $units */
        $units = $allUnits
            ->filter(function (WtsUnit $unit) use ($wasteCode, $wasteCodeOperator): bool {
                $unitWasteCode = $unit->technicalSheetSnapshot !== null ? $unit->technicalSheetSnapshot->waste_code : null;

                return $this->matchesTextFilter($unitWasteCode, $wasteCode, $wasteCodeOperator);
            })
            ->filter(function (WtsUnit $unit) use ($zone, $zoneOperator): bool {
                $effectiveArea = $unit->getAttribute('effective_area_code');

                return $this->matchesTextFilter(is_string($effectiveArea) ? $effectiveArea : null, $zone, $zoneOperator);
            })
            ->values();

        /** @var SupportCollection<int, WtsInventoryRow> $allRows */
        $allRows = $allUnits->map(fn (WtsUnit $unit): WtsInventoryRow => $this->mapLocalUnit($unit));

        /** @var SupportCollection<int, WtsInventoryRow> $rows */
        $rows = $units->map(fn (WtsUnit $unit): WtsInventoryRow => $this->mapLocalUnit($unit))->values();

        $overview = [
            'rows' => $rows,
            'stats' => [
                'available' => $rows->where('statusCode', 'available')->count(),
                'consumed' => $rows->where('statusCode', 'finished')->count(),
                'total' => $rows->count(),
            ],
            'filters' => [
                'wasteCodes' => $this->wasteCodesFromRows($allRows),
                'zones' => $this->zonesFromRows($allRows),
            ],
            'usesEcosStock' => false,
        ];

        return [
            ...$overview,
            'dataSource' => $this->dataSourceStatus($rows),
        ];
    }

    private function usesEcosStock(): bool
    {
        return (bool) config('wts.ecos.sync_stock');
    }

    private function mapLocalUnit(WtsUnit $unit): WtsInventoryRow
    {
        $lot = $unit->inboundLot;
        $record = $lot?->inboundRecord;
        $sheet = $unit->technicalSheetSnapshot ?? $record?->technicalSheetSnapshot;
        $effectiveArea = $unit->getAttribute('effective_area_code') ?? $unit->current_area_code;
        $effectiveStatus = $unit->getAttribute('effective_status_code') ?? $unit->status_code;

        return new WtsInventoryRow(
            batchCode: (string) data_get($unit->meta, 'batch_code', $lot->lot_code ?? 'Partita assente'),
            unitSubCode: (string) data_get(
                $unit->meta,
                'unit_sub_code',
                str_pad((string) $unit->generated_sequence, 2, '0', STR_PAD_LEFT),
            ),
            unitCode: $unit->unit_code,
            sheetCode: $sheet?->sheet_code,
            sheetDescription: $sheet?->production_description,
            hpCodes: $sheet?->hp_codes,
            physicalStateCode: null,
            physicalStateLabel: null,
            treatmentCode: null,
            treatmentLabel: null,
            wasteCode: $sheet?->waste_code,
            weightKg: $unit->gross_weight !== null ? (float) $unit->gross_weight : null,
            volume: null,
            colliCount: 1,
            zoneCode: is_string($effectiveArea) ? $effectiveArea : null,
            zoneLabel: null,
            productionDescription: $sheet?->production_description,
            packagingDescription: $sheet?->packaging_description,
            note1: $sheet?->note_1,
            note2: $sheet?->note_2,
            formReference: $record?->form_number,
            statusCode: is_string($effectiveStatus) ? $effectiveStatus : 'available',
            source: 'local',
            producerName: $record?->source_company_name,
            treatmentLineCode: $record?->treatment_line_code,
            productionCycleCode: $record?->production_cycle_code,
        );
    }

    /**
     * Filtro testuale con discriminante come ECOS (contiene / inizia con / uguale /
     * diverso da). Valori multipli separati da virgola: la riga passa se ALMENO un
     * termine soddisfa l'operatore (per "diverso da" deve differire da TUTTI).
     */
    private function matchesTextFilter(?string $haystack, ?string $value, string $operator): bool
    {
        $normalizedValue = trim((string) $value);
        if ($normalizedValue === '') {
            return true;
        }

        $terms = array_values(array_filter(
            array_map('trim', explode(',', $normalizedValue)),
            static fn (string $term): bool => $term !== '',
        ));
        if ($terms === []) {
            return true;
        }

        $normalizedHaystack = mb_strtolower(trim((string) $haystack));

        if ($operator === 'not_equals') {
            foreach ($terms as $term) {
                if ($normalizedHaystack === mb_strtolower($term)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($terms as $term) {
            $normalizedTerm = mb_strtolower($term);

            $matches = match ($operator) {
                'starts_with' => str_starts_with($normalizedHaystack, $normalizedTerm),
                'equals' => $normalizedHaystack === $normalizedTerm,
                default => str_contains($normalizedHaystack, $normalizedTerm),
            };

            if ($matches) {
                return true;
            }
        }

        return false;
    }

    private function applyEffectiveSnapshotState(WtsUnit $unit, ?string $referenceDate): WtsUnit
    {
        $referenceMoment = $this->referenceMoment($referenceDate);
        $consumedAt = $unit->processingSteps
            ->pluck('executed_at')
            ->filter()
            ->map(static function (mixed $value): ?CarbonImmutable {
                if ($value instanceof CarbonInterface) {
                    return CarbonImmutable::instance($value);
                }

                return is_scalar($value) && trim((string) $value) !== ''
                    ? CarbonImmutable::parse((string) $value)
                    : null;
            })
            ->filter()
            ->sortDesc()
            ->first();

        $consumedByReferenceDate = $referenceMoment instanceof CarbonImmutable
            ? (
                ($consumedAt instanceof CarbonImmutable && $consumedAt->lessThanOrEqualTo($referenceMoment))
                || ($consumedAt === null && $unit->status_code === 'finished')
            )
            : $unit->status_code === 'finished';

        $effectiveStatusCode = $consumedByReferenceDate ? 'finished' : 'available';
        $effectiveAreaCode = $consumedByReferenceDate
            ? 'finished'
            : ($unit->status_code === 'finished' ? 'to_process' : $unit->current_area_code);

        $unit->setAttribute('effective_status_code', $effectiveStatusCode);
        $unit->setAttribute('effective_area_code', $effectiveAreaCode);

        return $unit;
    }

    private function isVisibleAtReferenceDate(WtsUnit $unit, ?string $referenceDate): bool
    {
        $referenceMoment = $this->referenceMoment($referenceDate);
        if (! $referenceMoment instanceof CarbonImmutable) {
            return true;
        }

        return $unit->created_at instanceof CarbonInterface
            && $unit->created_at->lessThanOrEqualTo($referenceMoment);
    }

    private function referenceMoment(?string $referenceDate): ?CarbonImmutable
    {
        $normalizedReferenceDate = trim((string) $referenceDate);
        if ($normalizedReferenceDate === '') {
            return null;
        }

        return CarbonImmutable::parse($normalizedReferenceDate)->endOfDay();
    }

    /**
     * @param  SupportCollection<int, WtsInventoryRow>  $rows
     * @return array<int, string>
     */
    private function wasteCodesFromRows(SupportCollection $rows): array
    {
        return $rows
            ->map(static fn (WtsInventoryRow $row): ?string => $row->wasteCode)
            ->filter(static fn (?string $wasteCode): bool => is_string($wasteCode) && $wasteCode !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @param  SupportCollection<int, WtsInventoryRow>  $rows
     * @return array<int, string>
     */
    private function zonesFromRows(SupportCollection $rows): array
    {
        return $rows
            ->map(static fn (WtsInventoryRow $row): ?string => $row->zoneCode)
            ->filter(static fn (?string $zoneCode): bool => is_string($zoneCode) && $zoneCode !== '')
            ->unique()
            ->values()
            ->all();
    }
}
