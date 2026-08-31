<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Database\Eloquent\Collection;

final class SearchWtsAuditQuery
{
    /**
     * @return array{record: WtsInboundRecord|null, lot: WtsInboundLot|null, unit: WtsUnit|null, derivedMaterial: WtsDerivedMaterial|null, processingSteps: Collection<int, WtsProcessingStep>}
     */
    public function execute(string $search): array
    {
        $term = trim($search);

        /** @var WtsDerivedMaterial|null $material */
        $material = WtsDerivedMaterial::query()
            ->with('processingStep.selectedUnits.inboundLot.inboundRecord.technicalSheetSnapshot')
            ->where('output_code', $term)
            ->first();

        /** @var WtsUnit|null $unit */
        $unit = $material?->processingStep?->selectedUnits->first()
            ?? WtsUnit::query()->with(['inboundLot.inboundRecord.technicalSheetSnapshot', 'processingSteps.derivedMaterials'])
                ->where('unit_code', $term)
                ->orWhere('qr_code', $term)
                ->first();

        /** @var WtsInboundLot|null $lot */
        $lot = ($unit instanceof WtsUnit ? $unit->inboundLot : null)
            ?? WtsInboundLot::query()->with('inboundRecord.technicalSheetSnapshot')->where('lot_code', $term)->first();

        /** @var WtsInboundRecord|null $record */
        $record = ($lot instanceof WtsInboundLot ? $lot->inboundRecord : null)
            ?? WtsInboundRecord::query()->with('technicalSheetSnapshot')->where('form_number', $term)->first();

        if (! $unit instanceof WtsUnit && $lot instanceof WtsInboundLot) {
            $unit = $lot->units()->with(['inboundLot.inboundRecord.technicalSheetSnapshot', 'processingSteps.derivedMaterials'])->first();
        }

        if (! $lot instanceof WtsInboundLot && $record instanceof WtsInboundRecord) {
            $lot = $record->lots()->with('inboundRecord.technicalSheetSnapshot')->first();
        }

        /** @var Collection<int, WtsProcessingStep> $steps */
        $steps = $unit instanceof WtsUnit
            ? $unit->processingSteps()->with('derivedMaterials')->latest('executed_at')->get()
            : new Collection;

        return [
            'record' => $record,
            'lot' => $lot,
            'unit' => $unit,
            'derivedMaterial' => $material,
            'processingSteps' => $steps,
        ];
    }
}
