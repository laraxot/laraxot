<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages\Concerns;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\TimberBilling\Actions\RegisterTimberProcessingAction;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Support\WoodSpeciesCatalog;

trait HandlesTimberProcessingForm
{
    use NormalizesTimberConfigMaps;

    public ?int $lotId = null;

    public ?int $materialId = null;

    public string $stepTypeCode = 'taglio';

    public string $unitOfMeasure = 'kg';

    /** @var array<int, string> */
    public array $outputQuantities = ['', '', ''];

    /** @var array<int, bool> */
    public array $outputIsWaste = [false, false, false];

    public string $recoverableQuantity = '';

    public string $notes = '';

    public function mount(): void
    {
        $requestedLotId = (int) request()->query('lot', 0);
        $this->lotId = $requestedLotId > 0 ? $requestedLotId : null;

        $requestedMaterialId = (int) request()->query('material', 0);
        $this->materialId = $requestedMaterialId > 0 ? $requestedMaterialId : null;

        $source = $this->selectedLot() ?? $this->selectedMaterial();
        if ($source !== null) {
            $this->unitOfMeasure = $source->unit_of_measure;
        }
    }

    public function registerProcessing(): void
    {
        try {
            $authId = Auth::id();

            app(RegisterTimberProcessingAction::class)->execute(
                selectedLotIds: $this->lotId !== null ? [$this->lotId] : [],
                selectedDerivedMaterialIds: $this->materialId !== null ? [$this->materialId] : [],
                stepTypeCode: $this->processingStepType(),
                performedByUserId: is_int($authId) ? $authId : null,
                recoverableQuantity: $this->nullableFloat($this->recoverableQuantity),
                unitOfMeasure: $this->unitOfMeasure,
                notes: trim($this->notes) !== '' ? trim($this->notes) : null,
                outputDefinitions: $this->outputDefinitions(),
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        $this->outputQuantities = ['', '', ''];
        $this->outputIsWaste = [false, false, false];
        $this->recoverableQuantity = '';
        $this->notes = '';
        $this->lotId = null;
        $this->materialId = null;

        Notification::make()->title('Lavorazione registrata')->success()->send();
    }

    /**
     * @return array{lot: TimberLot|null, material: TimberDerivedMaterial|null, lots: Collection<int, TimberLot>, materials: Collection<int, TimberDerivedMaterial>, woodSpecies: array<string, string>, stepTypes: array<string, string>}
     */
    protected function getViewData(): array
    {
        return [
            'lot' => $this->selectedLot(),
            'material' => $this->selectedMaterial(),
            'lots' => TimberLot::query()->where('status_code', 'available')->latest()->get(),
            'materials' => TimberDerivedMaterial::query()->where('quantity', '>', 0)->where('is_waste', false)->latest()->get(),
            'inputWoodSpeciesName' => WoodSpeciesCatalog::name($this->inputWoodSpecies()),
            'stepTypes' => $this->configMap('timber.processing_step_types'),
        ];
    }

    protected function processingStepType(): string
    {
        return $this->stepTypeCode;
    }

    /**
     * @return array<int, array{wood_species:string, quantity:float, unit_of_measure?:string, is_waste?:bool}>
     */
    private function outputDefinitions(): array
    {
        // The output species always matches the input being processed (cutting
        // cherry yields cherry), so it is inherited rather than picked by hand.
        $species = $this->inputWoodSpecies();
        $definitions = [];

        foreach ($this->outputQuantities as $index => $rawQuantity) {
            $quantity = $this->nullableFloat($rawQuantity ?? '');
            if ($quantity === null || $quantity <= 0) {
                continue;
            }

            $definitions[] = [
                'wood_species' => $species,
                'quantity' => $quantity,
                'unit_of_measure' => $this->unitOfMeasure,
                'is_waste' => (bool) ($this->outputIsWaste[$index] ?? false),
            ];
        }

        return $definitions;
    }

    private function inputWoodSpecies(): string
    {
        $source = $this->selectedLot() ?? $this->selectedMaterial();

        return $source !== null ? trim((string) $source->wood_species) : '';
    }

    private function selectedLot(): ?TimberLot
    {
        if ($this->lotId === null) {
            return null;
        }

        return TimberLot::query()->with('purchaseRecord.supplier')->find($this->lotId);
    }

    private function selectedMaterial(): ?TimberDerivedMaterial
    {
        if ($this->materialId === null) {
            return null;
        }

        return TimberDerivedMaterial::query()->with('processingStep')->find($this->materialId);
    }

    private function nullableFloat(string $value): ?float
    {
        $normalized = trim(str_replace(',', '.', $value));

        return $normalized === '' ? null : (float) $normalized;
    }
}
