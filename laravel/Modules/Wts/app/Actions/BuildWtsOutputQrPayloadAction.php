<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Carbon\CarbonInterface;

final class BuildWtsOutputQrPayloadAction
{
    public function execute(WtsDerivedMaterial $material): string
    {
        $material->loadMissing(['processingStep', 'technicalSheetSnapshot']);

        $step = $material->processingStep;
        $sheet = $material->technicalSheetSnapshot;
        $createdAt = $material->created_at;
        $date = $createdAt instanceof CarbonInterface ? $createdAt->toDateString() : now()->toDateString();

        return implode('|', [
            'WTS',
            'OUTPUT='.$material->output_code,
            'SHEET='.($step instanceof WtsProcessingStep ? $step->work_sheet_code : 'N/D'),
            'CER='.($sheet instanceof WtsTechnicalSheetSnapshot ? $sheet->waste_code : 'N/D'),
            'DATA='.$date,
        ]);
    }
}
