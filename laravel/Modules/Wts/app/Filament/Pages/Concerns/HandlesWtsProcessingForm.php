<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages\Concerns;

use Modules\Wts\Actions\CalculateWtsStandardOutputWeightAction;
use Modules\Wts\Actions\RegisterWtsProcessingAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsUnit;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

trait HandlesWtsProcessingForm
{
    public ?int $lotId = null;

    /** @var list<int> */
    public array $selectedUnitIds = [];

    public string $workedWeight = '';

    public int $drumsCount = 0;

    public int $dirtyDrumsCount = 0;

    public int $palletsCount = 0;

    public string $drumsWeightOverride = '';

    public string $dirtyDrumsWeightOverride = '';

    public string $palletsWeightOverride = '';

    public bool $washingEnabled = false;

    public string $deferredIronWeight = '';

    public string $generatedLiquidWeight = '';

    public string $remainderWasteCode = '';

    public string $processingNotes = '';

    public function mount(): void
    {
        $requestedLotId = (int) request()->query('lot', 0);
        $this->lotId = $requestedLotId > 0 ? $requestedLotId : null;

        // Se lo snodo ha già passato i colli scelti (?units=1,2,3) li rispettiamo,
        // altrimenti selezioniamo tutti i colli disponibili della partita.
        $unitsParam = (string) request()->query('units', '');
        $requestedUnitIds = array_values(array_filter(array_map('intval', array_filter(explode(',', $unitsParam), static fn (string $v): bool => $v !== ''))));

        if ($requestedUnitIds !== []) {
            $this->selectedUnitIds = $requestedUnitIds;
        } else {
            $this->selectAllUnits();
        }

        // Precompila il peso lavorato all'apertura (peso ECOS del lotto / colli, sui colli
        // scelti): senza questo il campo resterebbe vuoto finché non si tocca la selezione.
        $this->recalculateWorkedWeight();
    }

    public function selectAllUnits(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            $this->selectedUnitIds = [];
            $this->recalculateWorkedWeight();

            return;
        }

        $this->selectedUnitIds = array_values($lot->units()
            ->where('status_code', 'available')
            ->pluck('id')
            ->map(static fn (mixed $id): int => is_numeric($id) ? (int) $id : 0)
            ->filter(static fn (int $id): bool => $id > 0)
            ->all());

        $this->recalculateWorkedWeight();
    }

    /**
     * Il peso lavorato si ricalcola dalla selezione dei colli (richiesta Valori):
     * tutti i colli selezionati -> peso totale del lotto; selezione parziale ->
     * somma dei pesi dei colli scelti. Se il collo ha un peso proprio si usa
     * quello, altrimenti si ripartisce il peso del lotto sui colli disponibili.
     * Resta comunque modificabile a mano per le eccezioni.
     */
    public function updatedSelectedUnitIds(): void
    {
        $this->recalculateWorkedWeight();
    }

    protected function recalculateWorkedWeight(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            $this->workedWeight = '';

            return;
        }

        /** @var Collection<int, WtsUnit> $availableUnits */
        $availableUnits = $lot->units()->where('status_code', 'available')->get();
        $totalUnits = $availableUnits->count();

        // inboundRecord puo' essere nullo su lotti orfani: senza guardia
        // l'accesso alle proprieta' manda in errore la pagina.
        $record = $lot->inboundRecord;
        $lotWeight = $record !== null
            ? (float) ($record->entry_weight ?? $record->gross_quantity ?? 0)
            : 0.0;
        $perUnitFallback = $totalUnits > 0 ? $lotWeight / $totalUnits : 0.0;

        $selectedIds = array_map(static fn (mixed $id): int => (int) $id, $this->selectedUnitIds);

        $sum = 0.0;
        foreach ($availableUnits as $unit) {
            if (! in_array((int) $unit->id, $selectedIds, true)) {
                continue;
            }

            $sum += $unit->gross_weight !== null ? (float) $unit->gross_weight : $perUnitFallback;
        }

        $this->workedWeight = $sum > 0.0 ? $this->formatWeight($sum) : '';
    }

    private function formatWeight(float $weight): string
    {
        // Formato locale (virgola), senza zeri decimali superflui: 200 / 120,5.
        $formatted = number_format($weight, 2, ',', '');

        return rtrim(rtrim($formatted, '0'), ',');
    }

    public function registerProcessing(): void
    {
        if ($this->selectedUnitIds === []) {
            Notification::make()->title('Seleziona almeno un collo')->warning()->send();

            return;
        }

        try {
            $authId = Auth::id();

            app(RegisterWtsProcessingAction::class)->execute(
                selectedUnitIds: $this->selectedUnitIds,
                stepTypeCode: $this->processingStepType(),
                performedByUserId: is_int($authId) || is_string($authId) ? $authId : null,
                quantityMode: 'units',
                selectedUnitsCount: count($this->selectedUnitIds),
                workedWeight: $this->nullableFloat($this->workedWeight),
                remainderWasteCode: $this->remainderWasteCode !== '' ? $this->remainderWasteCode : $this->selectedLot()?->inboundRecord?->technicalSheetSnapshot?->waste_code,
                notes: $this->notesPayload(),
                outputDefinitions: $this->outputDefinitions(),
                expectedLotId: $this->lotId,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        } catch (\Throwable $exception) {
            // Qualsiasi errore inatteso non deve lasciare il bottone bloccato in
            // loading (l'operatore vedeva solo un cursore "divieto"): mostriamo
            // l'errore reale e sblocchiamo la pagina.
            report($exception);
            Notification::make()
                ->title('Registrazione non riuscita')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->selectAllUnits();
        Notification::make()->title('Lavorazione registrata')->success()->send();
    }

    /**
     * @return array{
     *     lot: WtsInboundLot|null,
     *     units: Collection<int, WtsUnit>,
     *     stockViews: array<string, string>,
     *     outputWeights: list<array{label: string, count: int, weight: float}>,
     *     outputTotalWeight: float,
     *     remainderWeight: float|null
     * }
     */
    protected function getViewData(): array
    {
        $lot = $this->selectedLot();
        $summary = $this->outputWeightSummary();

        return [
            'lot' => $lot,
            'units' => $lot instanceof WtsInboundLot ? $lot->units()->where('status_code', 'available')->get() : WtsUnit::query()->whereRaw('1 = 0')->get(),
            'stockViews' => $this->stockViews(),
            'outputWeights' => $summary['items'],
            'outputTotalWeight' => $summary['total'],
            'remainderWeight' => $summary['remainder'],
        ];
    }

    /**
     * Riepilogo pesi degli output (fusti/fusti sporchi/bancali) che finiranno nel
     * foglio lavoro, e peso che resta sul rifiuto (peso lavorato meno gli output).
     * Serve a mostrarli a video mentre l'operatore compila.
     *
     * @return array{items: list<array{label: string, count: int, weight: float}>, total: float, remainder: float|null}
     */
    protected function outputWeightSummary(): array
    {
        $rows = [
            ['label' => 'Fusti', 'count' => $this->drumsCount, 'param' => 'standard_drum_weight_kg', 'override' => $this->drumsWeightOverride],
            ['label' => 'Fusti sporchi', 'count' => $this->dirtyDrumsCount, 'param' => 'standard_dirty_drum_weight_kg', 'override' => $this->dirtyDrumsWeightOverride],
            ['label' => 'Bancali', 'count' => $this->palletsCount, 'param' => 'standard_pallet_weight_kg', 'override' => $this->palletsWeightOverride],
        ];

        $items = [];
        $total = 0.0;

        foreach ($rows as $row) {
            if ($row['count'] <= 0) {
                continue;
            }

            $weight = $this->resolveOutputWeight($row['param'], $row['count'], $row['override']);
            $items[] = ['label' => $row['label'], 'count' => $row['count'], 'weight' => $weight];
            $total += $weight;
        }

        $worked = $this->nullableFloat($this->workedWeight);
        $remainder = $worked !== null ? max($worked - $total, 0.0) : null;

        return ['items' => $items, 'total' => $total, 'remainder' => $remainder];
    }

    abstract protected function processingStepType(): string;

    private function selectedLot(): ?WtsInboundLot
    {
        if ($this->lotId === null) {
            return null;
        }

        /** @var WtsInboundLot|null $lot */
        $lot = WtsInboundLot::query()
            ->with(['inboundRecord.technicalSheetSnapshot', 'units'])
            ->find($this->lotId);

        return $lot;
    }

    /**
     * @return array<string, string>
     */
    private function stockViews(): array
    {
        $stockViews = config('wts.stock_views', []);

        if (! is_array($stockViews)) {
            return [];
        }

        $normalized = [];

        foreach ($stockViews as $key => $label) {
            if (! is_string($key)) {
                continue;
            }

            $normalized[$key] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }

    /**
     * @return array<int, array{output_type_code:string, container_type_code:string, quantity:int, weight:float|null, technical_sheet_snapshot_id:int|null, is_dirty:bool}>
     */
    private function outputDefinitions(): array
    {
        $snapshotId = $this->selectedLot()?->inboundRecord?->technicalSheetSnapshot?->id;
        $definitions = [];

        if ($this->drumsCount > 0) {
            $definitions[] = $this->outputDefinition('drum', 'drum', $this->drumsCount, $this->resolveOutputWeight('standard_drum_weight_kg', $this->drumsCount, $this->drumsWeightOverride), $snapshotId, false);
        }

        if ($this->dirtyDrumsCount > 0) {
            $definitions[] = $this->outputDefinition('dirty_drum', 'drum', $this->dirtyDrumsCount, $this->resolveOutputWeight('standard_dirty_drum_weight_kg', $this->dirtyDrumsCount, $this->dirtyDrumsWeightOverride), $snapshotId, true);
        }

        if ($this->palletsCount > 0) {
            $definitions[] = $this->outputDefinition('pallet', 'pallet', $this->palletsCount, $this->resolveOutputWeight('standard_pallet_weight_kg', $this->palletsCount, $this->palletsWeightOverride), $snapshotId, false);
        }

        if ($this->nullableFloat($this->deferredIronWeight) !== null) {
            $definitions[] = $this->outputDefinition('deferred_iron', 'bulk', 1, $this->nullableFloat($this->deferredIronWeight), $snapshotId, false);
        }

        if ($this->nullableFloat($this->generatedLiquidWeight) !== null) {
            $definitions[] = $this->outputDefinition('generated_liquid', 'tank', 1, $this->nullableFloat($this->generatedLiquidWeight), $snapshotId, true);
        }

        return $definitions;
    }

    /**
     * @return array{output_type_code:string, container_type_code:string, quantity:int, weight:float|null, technical_sheet_snapshot_id:int|null, is_dirty:bool}
     */
    private function outputDefinition(string $outputTypeCode, string $containerTypeCode, int $quantity, ?float $weight, ?int $snapshotId, bool $isDirty): array
    {
        return [
            'output_type_code' => $outputTypeCode,
            'container_type_code' => $containerTypeCode,
            'quantity' => $quantity,
            'weight' => $weight,
            'technical_sheet_snapshot_id' => $snapshotId,
            'is_dirty' => $isDirty,
        ];
    }

    private function resolveOutputWeight(string $parameterCode, int $quantity, string $manualOverride): float
    {
        $override = $this->nullableFloat($manualOverride);

        return $override !== null && $override > 0 ? $override : $this->standardOutputWeight($parameterCode, $quantity);
    }

    private function standardOutputWeight(string $parameterCode, int $quantity): float
    {
        return app(CalculateWtsStandardOutputWeightAction::class)->execute($parameterCode, $quantity);
    }

    private function notesPayload(): string
    {
        return trim(implode(' | ', array_filter([
            $this->processingNotes,
            $this->washingEnabled ? 'lavaggio on' : null,
        ])));
    }

    private function nullableFloat(string $value): ?float
    {
        $normalizedValue = trim($value);

        return $normalizedValue === '' ? null : (float) str_replace(',', '.', $normalizedValue);
    }
}
