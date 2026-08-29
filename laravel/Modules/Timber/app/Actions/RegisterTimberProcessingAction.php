<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberProcessingStep;
use Modules\Timber\Support\TimberTraceCode;

final class RegisterTimberProcessingAction
{
    /**
     * @param  array<int, int>  $selectedLotIds
     * @param  array<int, int>  $selectedDerivedMaterialIds
     * @param  array<int, array{wood_species:string, quantity:float, unit_of_measure?:string|null, is_waste?:bool}>  $outputDefinitions
     */
    public function execute(
        array $selectedLotIds,
        array $selectedDerivedMaterialIds,
        string $stepTypeCode,
        ?int $performedByUserId,
        ?float $recoverableQuantity,
        string $unitOfMeasure,
        ?string $notes,
        array $outputDefinitions,
    ): TimberProcessingStep {
        if ($selectedLotIds === [] && $selectedDerivedMaterialIds === []) {
            throw new InvalidArgumentException('Selezionare almeno un lotto o materiale da lavorare.');
        }

        $normalizedStepType = trim($stepTypeCode);
        $configuredSteps = config('timber.timber.processing_step_types', []);
        $allowedSteps = is_array($configuredSteps) ? array_keys($configuredSteps) : [];
        if ($normalizedStepType === '' || ! in_array($normalizedStepType, $allowedSteps, true)) {
            throw new InvalidArgumentException('Tipo di lavorazione non valido.');
        }

        if ($recoverableQuantity !== null && $recoverableQuantity < 0) {
            throw new InvalidArgumentException('Il quantitativo recuperabile non puo essere negativo.');
        }

        if ($outputDefinitions === []) {
            throw new InvalidArgumentException('Indicare almeno un output della lavorazione.');
        }

        foreach ($outputDefinitions as $definition) {
            if ((float) ($definition['quantity'] ?? 0) <= 0) {
                throw new InvalidArgumentException('Ogni output deve avere una quantita positiva.');
            }
        }

        return DB::transaction(function () use (
            $selectedLotIds,
            $selectedDerivedMaterialIds,
            $normalizedStepType,
            $performedByUserId,
            $recoverableQuantity,
            $unitOfMeasure,
            $notes,
            $outputDefinitions,
        ): TimberProcessingStep {
            /** @var Collection<int, TimberLot> $lots */
            $lots = TimberLot::query()->whereIn('id', $selectedLotIds)->get();
            if ($lots->count() !== count($selectedLotIds)) {
                throw new InvalidArgumentException('Uno o piu lotti selezionati non esistono.');
            }

            if ($lots->contains(static fn (TimberLot $lot): bool => $lot->status_code !== 'available')) {
                throw new InvalidArgumentException('Solo i lotti disponibili possono essere lavorati.');
            }

            /** @var Collection<int, TimberDerivedMaterial> $materials */
            $materials = TimberDerivedMaterial::query()->whereIn('id', $selectedDerivedMaterialIds)->get();
            if ($materials->count() !== count($selectedDerivedMaterialIds)) {
                throw new InvalidArgumentException('Uno o piu materiali selezionati non esistono.');
            }

            if ($materials->contains(static fn (TimberDerivedMaterial $material): bool => (float) $material->quantity <= 0)) {
                throw new InvalidArgumentException('Solo i materiali con quantita disponibile possono essere lavorati.');
            }

            $inputQuantity = $lots->sum(static fn (TimberLot $lot): float => (float) $lot->quantity_available)
                + $materials->sum(static fn (TimberDerivedMaterial $material): float => (float) $material->quantity);

            $outputQuantity = collect($outputDefinitions)
                ->filter(static fn (array $definition): bool => ! (bool) ($definition['is_waste'] ?? false))
                ->sum(static fn (array $definition): float => (float) $definition['quantity']);

            $wasteQuantity = collect($outputDefinitions)
                ->filter(static fn (array $definition): bool => (bool) ($definition['is_waste'] ?? false))
                ->sum(static fn (array $definition): float => (float) $definition['quantity']);

            /** @var TimberProcessingStep $step */
            $step = TimberProcessingStep::query()->create([
                'work_sheet_code' => $this->buildWorksheetCode($normalizedStepType),
                'step_type_code' => $normalizedStepType,
                'input_quantity' => $inputQuantity,
                'output_quantity' => $outputQuantity > 0 ? $outputQuantity : null,
                'waste_quantity' => $wasteQuantity > 0 ? $wasteQuantity : null,
                'recoverable_quantity' => $recoverableQuantity,
                'unit_of_measure' => $unitOfMeasure,
                'executed_by_user_id' => $performedByUserId,
                'executed_at' => now(),
                'notes' => $notes,
            ]);

            if ($lots->isNotEmpty()) {
                $step->inputLots()->sync($lots->pluck('id')->all());
            }

            if ($materials->isNotEmpty()) {
                $step->inputDerivedMaterials()->sync($materials->pluck('id')->all());
            }

            TimberLot::query()->whereIn('id', $lots->pluck('id')->all())->update([
                'quantity_available' => 0,
                'status_code' => 'esaurito',
            ]);

            TimberDerivedMaterial::query()->whereIn('id', $materials->pluck('id')->all())->update([
                'quantity' => 0,
            ]);

            // Contesto di tracciabilita: radice, specie (ereditata dall'input) e
            // codice tracciabile che si arricchisce di un segmento per questa tappa.
            $firstLot = $lots->first();
            $firstMaterial = $materials->first();

            $rootLotId = null;
            $baseCode = '';
            $inputSpecies = '';
            $baseHistory = [];

            if ($firstLot instanceof TimberLot) {
                $rootLotId = $firstLot->id;
                $baseCode = (string) $firstLot->lot_code;
                $inputSpecies = (string) $firstLot->wood_species;
            } elseif ($firstMaterial instanceof TimberDerivedMaterial) {
                $rootLotId = $firstMaterial->root_lot_id;
                $baseCode = (string) ($firstMaterial->traceable_code ?? '');
                $inputSpecies = (string) $firstMaterial->wood_species;
                $baseHistory = is_array($firstMaterial->stage_history) ? $firstMaterial->stage_history : [];
            }

            $segment = TimberTraceCode::segment($normalizedStepType, now());
            $traceableCode = TimberTraceCode::append($baseCode, $segment);
            $stageHistory = [...$baseHistory, [
                'stage' => $normalizedStepType,
                'segment' => $segment,
                'code' => $traceableCode,
                'date' => now()->toDateString(),
            ]];

            $qrPayloadAction = app(BuildTimberQrPayloadAction::class);

            foreach ($outputDefinitions as $index => $definition) {
                $isWaste = (bool) ($definition['is_waste'] ?? false);
                $outputSpecies = $inputSpecies !== '' ? $inputSpecies : trim((string) ($definition['wood_species'] ?? ''));

                /** @var TimberDerivedMaterial $output */
                $output = $step->derivedMaterials()->create([
                    'output_code' => $this->buildOutputCode($step->work_sheet_code, $index + 1),
                    'root_lot_id' => $rootLotId,
                    'traceable_code' => $isWaste ? null : $traceableCode,
                    'stage_type_code' => $normalizedStepType,
                    'stage_history' => $isWaste ? null : $stageHistory,
                    'wood_species' => $outputSpecies,
                    'quantity' => $definition['quantity'],
                    'unit_of_measure' => $definition['unit_of_measure'] ?? $unitOfMeasure,
                    'status_code' => $isWaste ? 'scarto' : 'da_lavorare',
                    'current_area_code' => $isWaste ? 'area_scarti' : 'zona_taglio',
                    'is_waste' => $isWaste,
                ]);

                $output->labelPrints()->create([
                    'label_code' => $isWaste ? $output->output_code : $traceableCode,
                    'qr_payload' => $qrPayloadAction->execute($output),
                    'print_reason_code' => 'processing_output',
                    'printed_by_user_id' => $performedByUserId,
                    'printed_at' => now(),
                ]);
            }

            return $step->load(['inputLots', 'inputDerivedMaterials', 'derivedMaterials']);
        });
    }

    private function buildWorksheetCode(string $stepTypeCode): string
    {
        return sprintf('WS-%s-%s', Str::upper(Str::slug($stepTypeCode, '')), now()->format('YmdHisv'));
    }

    private function buildOutputCode(string $worksheetCode, int $sequence): string
    {
        return sprintf('%s-OUT-%02d', $worksheetCode, $sequence);
    }
}
