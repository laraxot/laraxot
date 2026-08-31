<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class RegisterWtsProcessingAction
{
    /**
     * @param  list<int>  $selectedUnitIds
     * @param array<int, array{
     *     output_type_code:string,
     *     container_type_code:string,
     *     quantity:int,
     *     weight:float|null,
     *     technical_sheet_snapshot_id:int|null,
     *     is_dirty:bool
     * }> $outputDefinitions
     */
    public function execute(
        array $selectedUnitIds,
        string $stepTypeCode,
        int|string|null $performedByUserId,
        string $quantityMode,
        int $selectedUnitsCount,
        ?float $workedWeight,
        ?string $remainderWasteCode,
        ?string $notes,
        array $outputDefinitions,
        ?int $expectedLotId = null,
    ): WtsProcessingStep {
        if ($selectedUnitIds === []) {
            throw new InvalidArgumentException('At least one unit must be selected.');
        }

        if ($selectedUnitsCount !== count($selectedUnitIds)) {
            throw new InvalidArgumentException('The selected units count must match the provided unit ids.');
        }

        if ($workedWeight !== null && $workedWeight < 0) {
            throw new InvalidArgumentException('The worked weight cannot be negative.');
        }

        return DB::transaction(function () use (
            $selectedUnitIds,
            $stepTypeCode,
            $performedByUserId,
            $quantityMode,
            $selectedUnitsCount,
            $workedWeight,
            $remainderWasteCode,
            $notes,
            $outputDefinitions,
            $expectedLotId,
        ): WtsProcessingStep {
            /** @var Collection<int, WtsUnit> $selectedUnits */
            $selectedUnits = WtsUnit::query()
                ->whereIn('id', $selectedUnitIds)
                ->with(['technicalSheetSnapshot', 'inboundLot'])
                ->get();

            if ($selectedUnits->count() !== count($selectedUnitIds)) {
                throw new InvalidArgumentException('One or more selected units are missing.');
            }

            if ($selectedUnits->contains(static fn (WtsUnit $unit): bool => $unit->status_code !== 'available')) {
                throw new InvalidArgumentException('Only available units can be processed.');
            }

            if ($expectedLotId !== null && $selectedUnits->contains(static fn (WtsUnit $unit): bool => $unit->inbound_lot_id !== $expectedLotId)) {
                throw new InvalidArgumentException('All selected units must belong to the expected lot.');
            }

            foreach ($outputDefinitions as $definition) {
                if (trim((string) ($definition['output_type_code'] ?? '')) === '' || trim((string) ($definition['container_type_code'] ?? '')) === '') {
                    throw new InvalidArgumentException('Each output definition must include output and container type codes.');
                }

                if ((int) ($definition['quantity'] ?? 0) <= 0) {
                    throw new InvalidArgumentException('Each output definition must include a positive quantity.');
                }

                if ($definition['weight'] !== null && $definition['weight'] < 0) {
                    throw new InvalidArgumentException('Output weights cannot be negative.');
                }
            }

            $snapshotId = $selectedUnits->first()?->technical_sheet_snapshot_id;
            $outputWeight = collect($outputDefinitions)
                ->sum(static fn (array $definition): float => (float) ($definition['weight'] ?? 0.0));

            // Bilancio di massa: ingressi - uscite deve fare 0. Le uscite sono gli
            // output piu' la rimanenza (= peso lavorato - output). Se gli output
            // superano il peso lavorato la rimanenza sarebbe negativa e il bilancio
            // non torna: si blocca la registrazione (richiesta Valori).
            if ($workedWeight !== null && $outputWeight > $workedWeight + 0.001) {
                throw new InvalidArgumentException(sprintf(
                    'Bilancio di massa non valido: gli output (%s kg) superano il peso lavorato (%s kg). Ingressi meno uscite deve fare 0.',
                    number_format($outputWeight, 2, ',', '.'),
                    number_format($workedWeight, 2, ',', '.'),
                ));
            }

            /** @var WtsProcessingStep $step */
            $step = WtsProcessingStep::query()->create([
                'work_sheet_code' => $this->buildWorksheetCode($stepTypeCode),
                'step_type_code' => trim($stepTypeCode),
                'quantity_mode' => trim($quantityMode),
                'selected_units_count' => $selectedUnitsCount,
                'input_weight' => $workedWeight,
                'output_weight' => $outputWeight > 0 ? $outputWeight : null,
                'remainder_weight' => $workedWeight !== null && $outputWeight > 0 ? max($workedWeight - $outputWeight, 0) : null,
                'remainder_waste_code' => $remainderWasteCode,
                'technical_sheet_snapshot_id' => $snapshotId,
                'executed_by_user_id' => $performedByUserId,
                'executed_at' => now(),
                'notes' => $notes,
            ]);

            $step->selectedUnits()->sync($selectedUnits->pluck('id')->all());

            WtsUnit::query()
                ->whereIn('id', $selectedUnits->pluck('id')->all())
                ->update([
                    'status_code' => 'finished',
                    'current_area_code' => 'finished',
                ]);

            foreach ($outputDefinitions as $index => $definition) {
                /** @var WtsDerivedMaterial $material */
                $material = $step->derivedMaterials()->create([
                    'technical_sheet_snapshot_id' => $definition['technical_sheet_snapshot_id'],
                    'output_code' => $this->buildOutputCode($step->work_sheet_code, $index + 1),
                    'output_type_code' => $definition['output_type_code'],
                    'container_type_code' => $definition['container_type_code'],
                    'quantity' => $definition['quantity'],
                    'weight' => $definition['weight'],
                    'is_dirty' => $definition['is_dirty'],
                ]);

                $material->labelPrints()->create([
                    'label_code' => $material->output_code,
                    'qr_payload' => 'WTS-QR-'.Str::upper(Str::slug($material->output_code, '')),
                    'print_reason_code' => 'processing_output',
                    'printed_by_user_id' => $performedByUserId,
                    'printed_at' => now(),
                ]);
            }

            return $step->load(['selectedUnits', 'derivedMaterials']);
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
