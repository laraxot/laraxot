<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationTemplate;
use Spatie\QueueableAction\QueueableAction;

final class ApplyQuotationTemplateToQuotationAction
{
    use QueueableAction;

    public function execute(Quotation $quotation, QuotationTemplate $template): Quotation
    {
        foreach ($template->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            $quotation->items()->create([
                'product_id' => $item->product_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount_percent' => $item->discount_percent,
                'line_total' => 0,
                'sort_order' => $item->sort_order,
            ]);
        }

        return app(RecalculateQuotationTotalsAction::class)->execute($quotation->refresh());
    }
}
