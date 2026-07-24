<?php

declare(strict_types=1);

namespace Modules\Production\Actions;

use Illuminate\Support\Str;
use Modules\Production\Models\ProductionOrder;
use Spatie\QueueableAction\QueueableAction;

final class CreateProductionOrderAction
{
    use QueueableAction;

    public function execute(
        int $productId,
        int $quantity,
        ?int $workOrderId,
        ?string $notes,
    ): ProductionOrder {
        $code = 'PO-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));

        /** @var ProductionOrder $order */
        $order = ProductionOrder::query()->create([
            'code' => $code,
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 'planned',
            'work_order_id' => $workOrderId,
            'notes' => $notes,
        ]);

        return $order;
    }
}
