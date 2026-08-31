<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;

final class GetWtsReportOverviewQuery
{
    /**
     * @return array{
     *     stats: array<string, int|float>,
     *     steps: \Illuminate\Database\Eloquent\Collection<int, WtsProcessingStep>,
     *     materials: \Illuminate\Database\Eloquent\Collection<int, WtsDerivedMaterial>
     * }
     */
    public function execute(): array
    {
        $steps = WtsProcessingStep::query()
            ->with(['selectedUnits.inboundLot.inboundRecord', 'derivedMaterials', 'executedBy'])
            ->latest('executed_at')
            ->get();

        $materials = WtsDerivedMaterial::query()
            ->with(['processingStep', 'technicalSheetSnapshot'])
            ->latest()
            ->get();

        return [
            'stats' => [
                'inbound_records' => (int) WtsInboundRecord::query()->count(),
                'processing_steps' => (int) WtsProcessingStep::query()->count(),
                'derived_materials' => (int) WtsDerivedMaterial::query()->count(),
                'available_units' => (int) WtsUnit::query()->where('status_code', 'available')->count(),
            ],
            'steps' => $steps,
            'materials' => $materials,
        ];
    }
}
