<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsProcessingStep;
use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateWtsProcessingReportPdfAction
{
    public function execute(): string
    {
        $steps = WtsProcessingStep::query()
            ->with(['selectedUnits.inboundLot.inboundRecord.technicalSheetSnapshot', 'derivedMaterials'])
            ->latest('executed_at')
            ->get();

        return Pdf::loadView('wts::pdf.processing-report', [
            'steps' => $steps,
        ])->output();
    }
}
