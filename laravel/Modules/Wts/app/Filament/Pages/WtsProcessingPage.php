<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Wts\Actions\CalculateWtsStandardOutputWeightAction;
use Modules\Wts\Actions\RegisterWtsProcessingAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\GetWtsOperationalParametersQuery;

final class WtsProcessingPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'UI-07 - Snodo lavorazioni';

    protected static ?string $title = 'UI-07 - Snodo lavorazioni WTS';

    protected static ?string $slug = 'processing';

    protected static ?int $navigationSort = 7;

    protected string $view = 'wts::filament.pages.processing';

    public int|string|null $lotId = null;

    /** @var list<int|string> */
    public array $selectedUnitIds = [];

    public string $stepTypeCode = 'unpackaging';

    public string $quantityMode = 'units';

    public ?float $workedWeight = null;

    public int $drumsCount = 0;

    public int $dirtyDrumsCount = 0;

    public int $palletsCount = 0;

    public ?float $drumsWeightOverride = null;

    public ?float $dirtyDrumsWeightOverride = null;

    public ?float $palletsWeightOverride = null;

    public bool $washingEnabled = false;

    public ?float $deferredIronWeight = null;

    public ?float $generatedLiquidWeight = null;

    public string $remainderWasteCode = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedLotId = (int) request()->query('lot', 0);
        $this->lotId = $requestedLotId > 0 ? $requestedLotId : null;
        $this->selectAllUnits();
    }

    public function updatedLotId(): void
    {
        $this->selectAllUnits();
    }

    public function selectAllUnits(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            $this->selectedUnitIds = [];

            return;
        }

        $this->selectedUnitIds = array_values($lot->units()
            ->where('status_code', 'available')
            ->pluck('id')
            ->map(static fn (mixed $id): int => is_numeric($id) ? (int) $id : 0)
            ->filter(static fn (int|string $id): bool => $id > 0)
            ->all());
    }

    public function deselectUnit(int|string $unitId): void
    {
        $this->selectedUnitIds = array_values(array_filter(
            $this->selectedUnitIds,
            static fn (int|string $selectedUnitId): bool => $selectedUnitId !== $unitId,
        ));
    }

    /**
     * Seleziona/deseleziona un collo col tap (usato dalla vista Brondi dell'operatore).
     */
    public function toggleUnit(int|string $unitId): void
    {
        if (in_array($unitId, $this->selectedUnitIds, true)) {
            $this->deselectUnit($unitId);

            return;
        }

        $this->selectedUnitIds[] = $unitId;
        $this->selectedUnitIds = array_values(array_unique($this->selectedUnitIds));
    }

    public function processSelection(): void
    {
        if ($this->selectedUnitIds === []) {
            Notification::make()
                ->title('Seleziona almeno un collo')
                ->warning()
                ->send();

            return;
        }

        try {
            $authId = Auth::id();

            app(RegisterWtsProcessingAction::class)->execute(
                selectedUnitIds: $this->selectedUnitIds,
                stepTypeCode: $this->stepTypeCode,
                performedByUserId: is_int($authId) || is_string($authId) ? $authId : null,
                quantityMode: $this->quantityMode,
                selectedUnitsCount: count($this->selectedUnitIds),
                workedWeight: $this->workedWeight,
                remainderWasteCode: $this->remainderWasteCode !== '' ? $this->remainderWasteCode : $this->selectedLot()?->inboundRecord?->technicalSheetSnapshot?->waste_code,
                notes: $this->processingNotes(),
                outputDefinitions: $this->outputDefinitions(),
                expectedLotId: $this->selectedLot()?->id,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        $this->drumsCount = 0;
        $this->dirtyDrumsCount = 0;
        $this->palletsCount = 0;
        $this->drumsWeightOverride = null;
        $this->dirtyDrumsWeightOverride = null;
        $this->palletsWeightOverride = null;
        $this->deferredIronWeight = null;
        $this->generatedLiquidWeight = null;
        $this->workedWeight = null;
        $this->selectAllUnits();

        Notification::make()
            ->title('Lavorazione registrata')
            ->success()
            ->send();
    }

    /**
     * @return array{selectedLot: WtsInboundLot|null, lots: Collection<int, WtsInboundLot>, selectedUnits: Collection<int, WtsUnit>, stepLabels: array<string, string>, operationalParameters: array<string, array<string, string>>}
     */
    protected function getViewData(): array
    {
        $lots = WtsInboundLot::query()
            ->with(['inboundRecord.technicalSheetSnapshot', 'units'])
            ->latest()
            ->get();

        $selectedLot = $this->selectedLot($lots);
        $selectedUnits = $selectedLot instanceof WtsInboundLot
            ? $selectedLot->units()->where('status_code', 'available')->get()
            : WtsUnit::query()->whereRaw('1 = 0')->get();

        return [
            'selectedLot' => $selectedLot,
            'lots' => $lots,
            'selectedUnits' => $selectedUnits,
            'stepLabels' => $this->stepLabels(),
            'operationalParameters' => $this->operationalParameters(),
        ];
    }

    /**
     * @return array<int, array{
     *     output_type_code:string,
     *     container_type_code:string,
     *     quantity:int,
     *     weight:float|null,
     *     technical_sheet_snapshot_id:int|string|null,
     *     is_dirty:bool
     * }>
     */
    private function outputDefinitions(): array
    {
        $snapshotId = $this->selectedLot()?->inboundRecord?->technicalSheetSnapshot?->id;
        $definitions = [];

        if ($this->drumsCount > 0) {
            $definitions[] = [
                'output_type_code' => 'drum',
                'container_type_code' => 'drum',
                'quantity' => $this->drumsCount,
                'weight' => $this->resolveOutputWeight('standard_drum_weight_kg', $this->drumsCount, $this->drumsWeightOverride),
                'technical_sheet_snapshot_id' => $snapshotId,
                'is_dirty' => false,
            ];
        }

        if ($this->dirtyDrumsCount > 0) {
            $definitions[] = [
                'output_type_code' => 'dirty_drum',
                'container_type_code' => 'drum',
                'quantity' => $this->dirtyDrumsCount,
                'weight' => $this->resolveOutputWeight('standard_dirty_drum_weight_kg', $this->dirtyDrumsCount, $this->dirtyDrumsWeightOverride),
                'technical_sheet_snapshot_id' => $snapshotId,
                'is_dirty' => true,
            ];
        }

        if ($this->palletsCount > 0) {
            $definitions[] = [
                'output_type_code' => 'pallet',
                'container_type_code' => 'pallet',
                'quantity' => $this->palletsCount,
                'weight' => $this->resolveOutputWeight('standard_pallet_weight_kg', $this->palletsCount, $this->palletsWeightOverride),
                'technical_sheet_snapshot_id' => $snapshotId,
                'is_dirty' => false,
            ];
        }

        if ($this->deferredIronWeight !== null && $this->deferredIronWeight > 0) {
            $definitions[] = [
                'output_type_code' => 'deferred_iron',
                'container_type_code' => 'bulk',
                'quantity' => 1,
                'weight' => $this->deferredIronWeight,
                'technical_sheet_snapshot_id' => $snapshotId,
                'is_dirty' => false,
            ];
        }

        if ($this->generatedLiquidWeight !== null && $this->generatedLiquidWeight > 0) {
            $definitions[] = [
                'output_type_code' => 'generated_liquid',
                'container_type_code' => 'tank',
                'quantity' => 1,
                'weight' => $this->generatedLiquidWeight,
                'technical_sheet_snapshot_id' => $snapshotId,
                'is_dirty' => true,
            ];
        }

        return $definitions;
    }

    private function resolveOutputWeight(string $parameterCode, int $quantity, ?float $manualOverride): float
    {
        return $manualOverride !== null && $manualOverride > 0 ? $manualOverride : $this->standardOutputWeight($parameterCode, $quantity);
    }

    private function standardOutputWeight(string $parameterCode, int $quantity): float
    {
        return app(CalculateWtsStandardOutputWeightAction::class)->execute($parameterCode, $quantity);
    }

    private function processingNotes(): string
    {
        return collect([
            'lavaggio' => $this->washingEnabled ? 'lavaggio on' : 'lavaggio off',
            'ferro_deferizzato_kg' => $this->deferredIronWeight,
            'liquido_generato_kg' => $this->generatedLiquidWeight,
        ])
            ->filter(static fn (mixed $value): bool => $value !== null)
            ->map(static fn (mixed $value, string $key): string => $key.': '.(string) $value)
            ->implode(' | ');
    }

    /**
     * @param  Collection<int, WtsInboundLot>|null  $lots
     */
    private function selectedLot($lots = null): ?WtsInboundLot
    {
        if ($lots instanceof Collection) {
            /** @var WtsInboundLot|null $lot */
            $lot = $lots->firstWhere('id', $this->lotId);

            return $lot;
        }

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
    private function stepLabels(): array
    {
        $stepLabels = config('wts.processing_step_types', []);

        if (! is_array($stepLabels)) {
            return [];
        }

        $normalized = [];

        foreach ($stepLabels as $code => $label) {
            if (! is_string($code)) {
                continue;
            }

            $normalized[$code] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function operationalParameters(): array
    {
        $normalized = collect(app(GetWtsOperationalParametersQuery::class)->execute())
            ->mapWithKeys(static fn (WtsOperationalParameter $parameter, string $code): array => [
                $code => [
                    'label' => $parameter->label,
                    'value' => $parameter->value,
                    'unit' => $parameter->unit ?? '',
                ],
            ])
            ->all();

        return $normalized;
    }
}
