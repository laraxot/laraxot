<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Support\Carbon;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPurchaseRecord;

final class GetTimberDashboardChartsQuery
{
    /**
     * @return array{
     *     materialsByStatus: array<string, float>,
     *     stockBySpecies: array<string, float>,
     *     purchasesTrend: array<int, array{weekStart: string, weekEnd: string, quantity: float}>,
     *     topSuppliers: array<int, array{supplierName: string, quantity: float}>
     * }
     */
    public function execute(): array
    {
        /** @var array<string, float> $materialsByStatus */
        $materialsByStatus = TimberDerivedMaterial::query()
            ->where('quantity', '>', 0)
            ->toBase()
            ->selectRaw('status_code as label, SUM(quantity) as total')
            ->groupBy('status_code')
            ->pluck('total', 'label')
            ->map(static fn (mixed $value): float => is_numeric($value) ? (float) $value : 0.0)
            ->all();

        /** @var array<string, float> $stockBySpecies */
        $stockBySpecies = TimberLot::query()
            ->where('status_code', 'available')
            ->toBase()
            ->selectRaw('wood_species as label, SUM(quantity_available) as total')
            ->groupBy('wood_species')
            ->pluck('total', 'label')
            ->map(static fn (mixed $value): float => is_numeric($value) ? (float) $value : 0.0)
            ->all();

        return [
            'materialsByStatus' => $materialsByStatus,
            'stockBySpecies' => $stockBySpecies,
            'purchasesTrend' => $this->purchasesTrend(),
            'topSuppliers' => $this->topSuppliers(),
        ];
    }

    /**
     * @return array<int, array{weekStart: string, weekEnd: string, quantity: float}>
     */
    private function purchasesTrend(): array
    {
        $weeks = [];
        $firstWeekStart = Carbon::now()->startOfWeek()->subWeeks(7);

        for ($i = 0; $i < 8; $i++) {
            $weekStart = $firstWeekStart->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $quantityRaw = TimberPurchaseRecord::query()
                ->whereBetween('received_at', [$weekStart, $weekEnd])
                ->sum('quantity');

            $weeks[] = [
                'weekStart' => $weekStart->toDateString(),
                'weekEnd' => $weekEnd->toDateString(),
                'quantity' => is_numeric($quantityRaw) ? (float) $quantityRaw : 0.0,
            ];
        }

        return $weeks;
    }

    /**
     * @return array<int, array{supplierName: string, quantity: float}>
     */
    private function topSuppliers(): array
    {
        $rows = TimberPurchaseRecord::query()
            ->join('timber_suppliers', 'timber_suppliers.id', '=', 'timber_purchase_records.supplier_id')
            ->toBase()
            ->selectRaw('timber_suppliers.legal_name as supplier_name, SUM(timber_purchase_records.quantity) as total')
            ->groupBy('timber_suppliers.legal_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return $rows->map(static fn (object $row): array => [
            'supplierName' => is_string($row->supplier_name ?? null) ? $row->supplier_name : '',
            'quantity' => isset($row->total) && is_numeric($row->total) ? (float) $row->total : 0.0,
        ])->all();
    }
}
