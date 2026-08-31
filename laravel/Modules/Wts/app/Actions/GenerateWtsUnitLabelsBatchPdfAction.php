<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

final readonly class GenerateWtsUnitLabelsBatchPdfAction
{
    public function __construct(
        private BuildWtsQrPayloadAction $payloadAction,
        private RenderWtsQrSvgAction $qrSvgAction,
    ) {}

    /**
     * @param  Collection<int, WtsUnit>  $units
     */
    public function execute(Collection $units): string
    {
        $labels = $units->map(function (WtsUnit $unit): array {
            $unit->loadMissing('inboundLot.inboundRecord.technicalSheetSnapshot');

            $payload = $this->payloadAction->execute($unit);

            return [
                'unit' => $unit,
                'lot' => $unit->inboundLot,
                'record' => $unit->inboundLot?->inboundRecord,
                'sheet' => $unit->inboundLot?->inboundRecord?->technicalSheetSnapshot,
                'payload' => $payload,
                'qrSvgDataUri' => $this->qrSvgAction->dataUri($payload),
            ];
        });

        $mmToPt = static fn (mixed $mm): float => (is_numeric($mm) ? (float) $mm : 0.0) * 72 / 25.4;
        $paper = [0.0, 0.0, $mmToPt(config('wts.label_pdf.width_mm', 100)), $mmToPt(config('wts.label_pdf.height_mm', 150))];

        return Pdf::loadView('wts::pdf.unit-labels-batch', [
            'labels' => $labels,
        ])->setPaper($paper)->output();
    }
}
