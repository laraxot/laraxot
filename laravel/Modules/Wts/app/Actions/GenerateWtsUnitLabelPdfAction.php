<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsUnit;
use Barryvdh\DomPDF\Facade\Pdf;

final readonly class GenerateWtsUnitLabelPdfAction
{
    public function __construct(
        private BuildWtsQrPayloadAction $payloadAction,
        private RenderWtsQrSvgAction $qrSvgAction,
    ) {}

    public function execute(WtsUnit $unit): string
    {
        $unit->loadMissing('inboundLot.inboundRecord.technicalSheetSnapshot');

        $payload = $this->payloadAction->execute($unit);

        return Pdf::loadView('wts::pdf.unit-label', [
            'unit' => $unit,
            'lot' => $unit->inboundLot,
            'record' => $unit->inboundLot?->inboundRecord,
            'sheet' => $unit->inboundLot?->inboundRecord?->technicalSheetSnapshot,
            'payload' => $payload,
            'qrSvgDataUri' => $this->qrSvgAction->dataUri($payload),
        ])->setPaper($this->labelPaper())->output();
    }

    /**
     * Dimensioni del foglio etichetta in punti (mm -> pt), da config/wts.php.
     *
     * @return array{0: float, 1: float, 2: float, 3: float}
     */
    private function labelPaper(): array
    {
        $mmToPt = static fn (mixed $mm): float => (is_numeric($mm) ? (float) $mm : 0.0) * 72 / 25.4;

        return [0.0, 0.0, $mmToPt(config('wts.label_pdf.width_mm', 100)), $mmToPt(config('wts.label_pdf.height_mm', 150))];
    }
}
