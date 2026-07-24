<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;

final readonly class GenerateTimberLabelPdfAction
{
    public function __construct(
        private BuildTimberQrPayloadAction $payloadAction,
        private RenderTimberQrSvgAction $qrSvgAction,
    ) {}

    public function execute(Model $labelable): string
    {
        $payload = $this->payloadAction->execute($labelable);

        return Pdf::loadView('timber.pdf.label', [
            'labelable' => $labelable,
            'payload' => $payload,
            'qrSvgDataUri' => $this->qrSvgAction->dataUri($payload),
        ])->output();
    }
}
