<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Database\Eloquent\Collection;
use Modules\Timber\Models\TimberStockMovement;

final class GetTimberStockMovementsQuery
{
    /**
     * @return Collection<int, TimberStockMovement>
     */
    public function execute(
        ?string $directionCode = null,
        ?int $productId = null,
        ?int $customerId = null,
        ?string $lotCode = null,
        ?string $fromDate = null,
        ?string $toDate = null,
    ): Collection {
        return TimberStockMovement::query()
            ->with(['product', 'customer'])
            ->when($directionCode !== null && $directionCode !== '', fn ($query) => $query->where('direction_code', $directionCode))
            ->when($productId !== null, fn ($query) => $query->where('product_id', $productId))
            ->when($customerId !== null, fn ($query) => $query->where('customer_id', $customerId))
            ->when($lotCode !== null && $lotCode !== '', fn ($query) => $query->where('lot_code', 'like', "%{$lotCode}%"))
            ->when($fromDate !== null && $fromDate !== '', fn ($query) => $query->whereDate('movement_date', '>=', $fromDate))
            ->when($toDate !== null && $toDate !== '', fn ($query) => $query->whereDate('movement_date', '<=', $toDate))
            ->orderByDesc('movement_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Current on-hand stock for a product, optionally excluding one movement id
     * (used to reconstruct the state before a movement being registered).
     */
    public function stockForProduct(int $productId, ?int $excludeMovementId = null): float
    {
        $movements = TimberStockMovement::query()
            ->where('product_id', $productId)
            ->when($excludeMovementId !== null, fn ($query) => $query->where('id', '!=', $excludeMovementId))
            ->get(['direction_code', 'quantity']);

        $stock = 0.0;
        foreach ($movements as $movement) {
            $stock += $movement->signedQuantity();
        }

        return round($stock, 3);
    }
}
