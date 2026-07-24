<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Illuminate\Support\Carbon;
use Modules\TimberBilling\Data\TimberTraceabilityRow;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPackagedUnit;

/**
 * Costruisce il registro tracciabilita seguendo la catena
 * lotto ingresso -> semilavorato -> confezione.
 *
 * Una riga per gruppo di confezionamento (stesso codice tracciabile), piu' una
 * riga per i lotti non ancora confezionati (ingresso ed eventuale lavorazione).
 */
final class GetTimberTraceabilityRegisterQuery
{
    /**
     * @return array<int, TimberTraceabilityRow>
     */
    public function execute(): array
    {
        $rows = [];
        $packagedLotIds = [];

        $units = TimberPackagedUnit::query()
            ->with(['derivedMaterial.rootLot.purchaseRecord.supplier', 'derivedMaterial.processingStep'])
            ->whereNotNull('traceable_code')
            ->get()
            ->groupBy('traceable_code');

        foreach ($units as $traceableCode => $group) {
            /** @var TimberPackagedUnit $first */
            $first = $group->first();
            $material = $first->derivedMaterial;
            $lot = $material !== null ? $material->rootLot : null;

            if ($lot !== null) {
                $packagedLotIds[] = $lot->id;
            }

            $packagingDate = $first->created_at;

            $rows[] = $this->buildRow(
                lot: $lot,
                material: $material,
                traceableCode: (string) $traceableCode,
                articleCode: $first->article_code,
                packagingDate: $packagingDate instanceof Carbon ? $packagingDate->format('Y-m-d') : null,
                bancali: $group->count(),
            );
        }

        $lots = TimberLot::query()
            ->with('purchaseRecord.supplier')
            ->whereNotIn('id', array_values(array_unique($packagedLotIds)))
            ->get();

        foreach ($lots as $lot) {
            $material = TimberDerivedMaterial::query()
                ->with('processingStep')
                ->where('root_lot_id', $lot->id)
                ->latest('id')
                ->first();

            $rows[] = $this->buildRow(
                lot: $lot,
                material: $material,
                traceableCode: $material !== null ? (string) ($material->traceable_code ?? $lot->lot_code) : (string) $lot->lot_code,
                articleCode: null,
                packagingDate: null,
                bancali: 0,
            );
        }

        usort($rows, static fn (TimberTraceabilityRow $a, TimberTraceabilityRow $b): int => [$b->ingressYear, $b->ddtNumber] <=> [$a->ingressYear, $a->ddtNumber]);

        return $rows;
    }

    private function buildRow(
        ?TimberLot $lot,
        ?TimberDerivedMaterial $material,
        string $traceableCode,
        ?string $articleCode,
        ?string $packagingDate,
        int $bancali,
    ): TimberTraceabilityRow {
        $purchase = $lot !== null ? $lot->purchaseRecord : null;
        $supplier = $purchase !== null ? $purchase->supplier : null;

        $ingressDate = null;
        if ($purchase !== null) {
            $ingressDate = $purchase->ddt_date ?? $purchase->received_at;
        }
        $ingressYear = $ingressDate !== null ? Carbon::parse($ingressDate)->format('Y') : '';

        $workStart = null;
        $step = $material !== null ? $material->processingStep : null;
        if ($step !== null && $step->executed_at !== null) {
            $workStart = Carbon::parse($step->executed_at)->format('Y-m-d');
        }

        return new TimberTraceabilityRow(
            supplierName: $supplier !== null ? (string) $supplier->legal_name : '',
            supplierCode: $supplier !== null ? (string) ($supplier->vat_number ?? '') : '',
            ingressYear: $ingressYear,
            ddtNumber: $purchase !== null ? (string) ($purchase->ddt_number ?? '') : '',
            quantity: $purchase !== null ? (float) $purchase->quantity : 0.0,
            unit: $purchase !== null ? (string) $purchase->unit_of_measure : '',
            workStartDate: $workStart,
            articleCode: $articleCode,
            packagingDate: $packagingDate,
            bancali: $bancali,
            traceableCode: $traceableCode,
        );
    }
}
