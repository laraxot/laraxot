<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Spatie\QueueableAction\QueueableAction;

/**
 * Persists a reviewed {@see QuotationDraft} as a brand-new quotation for the given
 * customer, copying the (catalog-matched) line items and recomputing totals. This
 * is the final step of the "create a similar quotation by voice" flow.
 */
final class CreateQuotationFromDraftAction
{
    use QueueableAction;

    /** @var float */
    private const DEFAULT_TAX_PERCENT = 22.0;

    public function __construct(
        private readonly RecalculateQuotationTotalsAction $recalculate,
    ) {}

    public function execute(QuotationDraft $draft, int $customerId): Quotation
    {
        return DB::transaction(function () use ($draft, $customerId): Quotation {
            /** @var Quotation $quotation */
            $quotation = Quotation::query()->create([
                'customer_id' => $customerId,
                'work_order_id' => null,
                'status_code' => 'draft',
                'title' => $draft->title,
                'notes' => $draft->notes,
                'technical_params' => $draft->technicalParams !== [] ? $draft->technicalParams : null,
                'tax_percent' => $draft->taxPercent ?? self::DEFAULT_TAX_PERCENT,
                'issued_at' => now(),
            ]);

            $sortOrder = 0;
            foreach ($draft->items as $item) {
                /** @var DictatedQuotationItem $item */
                $quotation->items()->create([
                    'product_id' => $item->matchedProductId,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unitPrice ?? 0.0,
                    'discount_percent' => 0,
                    'line_total' => 0,
                    'sort_order' => $sortOrder++,
                ]);
            }

            return $this->recalculate->execute($quotation);
        });
    }
}
