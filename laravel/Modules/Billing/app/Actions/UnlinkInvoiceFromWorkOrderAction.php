<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\InvoiceWorkOrderLink;

final class UnlinkInvoiceFromWorkOrderAction
{
    public function execute(Invoice $invoice, int $workOrderId): void
    {
        InvoiceWorkOrderLink::query()
            ->where('invoice_id', $invoice->id)
            ->where('work_order_id', $workOrderId)
            ->delete();
    }
}

