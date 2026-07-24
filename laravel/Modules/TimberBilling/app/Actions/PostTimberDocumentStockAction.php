<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\TimberBilling\Models\TimberDocument;
use RuntimeException;

final class PostTimberDocumentStockAction
{
    public function __construct(
        private readonly RegisterTimberStockMovementAction $registerStockMovement,
    ) {}

    /**
     * Post a document to the warehouse, generating one stock movement per line.
     * DDTs unload stock; inbound goods documents load it. Idempotent guard.
     */
    public function execute(TimberDocument $document, ?int $userId = null): TimberDocument
    {
        if (! $document->hasStockImpact()) {
            throw new RuntimeException('Questo tipo di documento non genera movimenti di magazzino.');
        }

        if ($document->stock_posted_at !== null) {
            throw new RuntimeException('Il documento e gia stato registrato a magazzino.');
        }

        $directions = config('timberbilling.timber.document_stock_directions', []);
        $direction = is_array($directions) ? ($directions[$document->document_type_code] ?? null) : null;

        if (! is_string($direction)) {
            throw new RuntimeException('Direzione di magazzino non configurata per questo documento.');
        }

        return DB::transaction(function () use ($document, $direction, $userId): TimberDocument {
            $customer = $document->customer;
            $movementDate = Carbon::parse($document->document_date);

            foreach ($document->lines()->with('product')->get() as $line) {
                $product = $line->product;

                if ($product === null) {
                    continue;
                }

                $this->registerStockMovement->execute(
                    product: $product,
                    directionCode: $direction,
                    quantity: (float) $line->quantity,
                    unitOfMeasure: (string) $line->unit_of_measure,
                    userId: $userId,
                    customer: $customer,
                    documentTypeCode: $document->document_type_code,
                    documentReference: $document->number,
                    lotCode: $line->lot_code,
                    movementDate: $movementDate,
                    unitCost: $direction === 'load' ? (float) $line->unit_price : null,
                );
            }

            $document->forceFill([
                'status_code' => 'delivered',
                'stock_posted_at' => now(),
            ])->save();

            return $document->refresh();
        });
    }
}
