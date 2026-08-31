<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsProcessingStep;
use Barryvdh\DomPDF\Facade\Pdf;

final class GenerateWtsProcessingSheetPdfAction
{
    public function execute(WtsProcessingStep $step): string
    {
        $step->loadMissing(['selectedUnits.inboundLot.inboundRecord.technicalSheetSnapshot', 'derivedMaterials.technicalSheetSnapshot']);

        return Pdf::loadView('wts::pdf.processing-sheet', [
            'step' => $step,
            'selectedUnits' => $step->selectedUnits,
            'derivedMaterials' => $step->derivedMaterials,
        ])->output();
    }
}
