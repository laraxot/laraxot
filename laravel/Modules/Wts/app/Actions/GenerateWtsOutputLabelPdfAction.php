<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsDerivedMaterial;
use Barryvdh\DomPDF\Facade\Pdf;

final readonly class GenerateWtsOutputLabelPdfAction
{
    public function __construct(
        private BuildWtsOutputQrPayloadAction $payloadAction,
        private RenderWtsQrSvgAction $qrSvgAction,
    ) {}

    public function execute(WtsDerivedMaterial $material): string
    {
        $material->loadMissing(['processingStep', 'technicalSheetSnapshot']);

        $payload = $this->payloadAction->execute($material);

        return Pdf::loadView('wts::pdf.output-label', [
            'material' => $material,
            'processingStep' => $material->processingStep,
            'sheet' => $material->technicalSheetSnapshot,
            'payload' => $payload,
            'qrSvgDataUri' => $this->qrSvgAction->dataUri($payload),
        ])->output();
    }
}
