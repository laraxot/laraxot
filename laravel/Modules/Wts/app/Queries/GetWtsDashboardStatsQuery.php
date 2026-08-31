<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;

final class GetWtsDashboardStatsQuery
{
    /**
     * @return array{ingresses_today:int, units_to_process:int, processings_today:int}
     */
    public function execute(): array
    {
        return [
            'ingresses_today' => WtsInboundRecord::query()->whereDate('received_at', today())->count(),
            'units_to_process' => WtsUnit::query()
                ->where('status_code', 'available')
                ->where('current_area_code', 'to_process')
                ->count(),
            'processings_today' => WtsProcessingStep::query()->whereDate('executed_at', today())->count(),
        ];
    }
}
