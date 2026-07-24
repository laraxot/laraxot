<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberDryingCycle;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberProcessingStep;
use Modules\TimberBilling\Models\TimberPurchaseRecord;

final class GetTimberDashboardStatsQuery
{
    /**
     * @return array{purchases_today:int, lots_available:int, lots_stuck:int, materials_ready_to_sell:int, drying_in_progress:int, yield_percent:float|null, total_waste_quantity:float, estimated_stock_value:float}
     */
    public function execute(): array
    {
        $configuredStuckDays = config('timber.operational_parameters.material_stuck_alert_days.value', 30);
        $stuckDays = is_numeric($configuredStuckDays) ? (int) $configuredStuckDays : 30;

        $processedInput = TimberProcessingStep::query()->get()->sum(static fn (TimberProcessingStep $step): float => (float) $step->input_quantity);
        $processedOutput = TimberProcessingStep::query()->get()->sum(static fn (TimberProcessingStep $step): float => (float) $step->output_quantity);

        $estimatedStockValueRow = TimberLot::query()
            ->where('timber_lots.status_code', 'available')
            ->join('timber_purchase_records', 'timber_purchase_records.id', '=', 'timber_lots.purchase_record_id')
            ->toBase()
            ->selectRaw('COALESCE(SUM(timber_lots.quantity_available * timber_purchase_records.unit_purchase_price), 0) as value')
            ->value('value');
        $estimatedStockValue = is_numeric($estimatedStockValueRow) ? (float) $estimatedStockValueRow : 0.0;

        return [
            'purchases_today' => TimberPurchaseRecord::query()->whereDate('received_at', today())->count(),
            'lots_available' => TimberLot::query()->where('status_code', 'available')->count(),
            'lots_stuck' => TimberLot::query()
                ->where('status_code', 'available')
                ->where('updated_at', '<=', now()->subDays($stuckDays))
                ->count(),
            'materials_ready_to_sell' => TimberDerivedMaterial::query()->where('status_code', 'pronto_per_vendita')->count(),
            'drying_in_progress' => TimberDryingCycle::query()->where('status_code', 'in_progress')->count(),
            'yield_percent' => $processedInput > 0 ? round(($processedOutput / $processedInput) * 100, 2) : null,
            'total_waste_quantity' => TimberDerivedMaterial::query()->where('is_waste', true)->get()->sum(static fn (TimberDerivedMaterial $material): float => (float) $material->quantity),
            'estimated_stock_value' => $estimatedStockValue,
        ];
    }
}
