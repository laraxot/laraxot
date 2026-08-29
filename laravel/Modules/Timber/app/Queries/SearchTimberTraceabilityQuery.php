<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Support\Collection;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;
use Modules\Timber\Models\TimberProcessingStep;
use Modules\Timber\Models\TimberPurchaseRecord;

final class SearchTimberTraceabilityQuery
{
    /**
     * @return array{
     *     purchaseRecord: TimberPurchaseRecord|null,
     *     lot: TimberLot|null,
     *     derivedMaterial: TimberDerivedMaterial|null,
     *     packagedUnit: TimberPackagedUnit|null,
     *     processingSteps: Collection<int, TimberProcessingStep>,
     * }
     */
    public function execute(string $search): array
    {
        $term = trim($search);

        /** @var TimberPackagedUnit|null $packagedUnit */
        $packagedUnit = TimberPackagedUnit::query()
            ->with('derivedMaterial.processingStep')
            ->where('package_code', $term)
            ->orWhere('qr_code', $term)
            ->first();

        /** @var TimberDerivedMaterial|null $material */
        $material = ($packagedUnit instanceof TimberPackagedUnit ? $packagedUnit->derivedMaterial : null)
            ?? TimberDerivedMaterial::query()
                ->with('processingStep')
                ->where('output_code', $term)
                ->first();

        /** @var TimberLot|null $lot */
        $lot = TimberLot::query()
            ->with('purchaseRecord.supplier')
            ->where('lot_code', $term)
            ->first();

        if (! $lot instanceof TimberLot && $material instanceof TimberDerivedMaterial) {
            /** @var TimberLot|null $originLot */
            $originLot = $material->processingStep?->inputLots()->with('purchaseRecord.supplier')->first();
            $lot = $originLot;
        }

        /** @var TimberPurchaseRecord|null $record */
        $record = ($lot instanceof TimberLot ? $lot->purchaseRecord : null)
            ?? TimberPurchaseRecord::query()->with('supplier')->where('ddt_number', $term)->first();

        if (! $lot instanceof TimberLot && $record instanceof TimberPurchaseRecord) {
            $lot = $record->lot;
        }

        /** @var Collection<int, TimberProcessingStep> $steps */
        $steps = $lot instanceof TimberLot
            ? $lot->processingSteps()->with('derivedMaterials')->latest('executed_at')->get()
            : new Collection();

        return [
            'purchaseRecord' => $record,
            'lot' => $lot,
            'derivedMaterial' => $material,
            'packagedUnit' => $packagedUnit,
            'processingSteps' => $steps,
        ];
    }
}
