<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\InvoiceWorkOrderLink;
use Spatie\QueueableAction\QueueableAction;

final class LinkInvoiceToWorkOrderAction
{
    use QueueableAction;

    public function execute(Invoice $invoice, int $workOrderId): InvoiceWorkOrderLink
    {
        /** @var InvoiceWorkOrderLink $link */
        $link = InvoiceWorkOrderLink::query()->firstOrCreate(
            [
                'invoice_id' => $invoice->id,
                'work_order_id' => $workOrderId,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        return $link;
    }
}
