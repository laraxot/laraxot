<?php

declare(strict_types=1);

namespace Modules\Inventory\Queries;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class GetInventoryOverviewRowsQuery
{
    public function execute(bool $groupByLocation): Builder
    {
        $locationSelect = $groupByLocation
            ? 'to_location_id as location_id'
            : 'NULL as location_id';
        $incoming = DB::table('inventory_movements')
            ->selectRaw(
                "product_id, to_warehouse_id as warehouse_id, {$locationSelect}, quantity as qty_delta, COALESCE(occurred_at, created_at) as movement_at"
            )
            ->whereNotNull('to_warehouse_id');

        $locationSelect = $groupByLocation
            ? 'from_location_id as location_id'
            : 'NULL as location_id';
        $outgoing = DB::table('inventory_movements')
            ->selectRaw(
                "product_id, from_warehouse_id as warehouse_id, {$locationSelect}, -quantity as qty_delta, COALESCE(occurred_at, created_at) as movement_at"
            )
            ->whereNotNull('from_warehouse_id');

        $stockBalance = DB::query()
            ->fromSub($incoming->unionAll($outgoing), 'movement_ledger')
            ->selectRaw('product_id, warehouse_id, location_id, SUM(qty_delta) as aggregate_stock, MAX(movement_at) as last_movement_at')
            ->groupBy('product_id', 'warehouse_id', 'location_id')
            ->havingRaw('SUM(qty_delta) <> 0');

        return DB::query()
            ->fromSub($stockBalance, 'stock_balance')
            ->select([
                DB::raw("CONCAT(stock_balance.product_id, '-', stock_balance.warehouse_id, '-', COALESCE(stock_balance.location_id, 0)) as id"),
                'products.id as product_id',
                'products.name as product_name',
                'products.sku as product_sku',
                'stock_balance.warehouse_id as warehouse_id',
                'warehouses.name as warehouse_name',
                'stock_balance.location_id as location_id',
                'warehouse_locations.code as location_code',
                'stock_balance.aggregate_stock',
                'stock_balance.last_movement_at',
            ])
            ->join('products', 'products.id', '=', 'stock_balance.product_id')
            ->join('warehouses', 'warehouses.id', '=', 'stock_balance.warehouse_id')
            ->leftJoin('warehouse_locations', 'warehouse_locations.id', '=', 'stock_balance.location_id');
    }
}
