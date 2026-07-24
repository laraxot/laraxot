<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Import\QuotationPdfRenderer;
use Modules\Quotation\Models\Quotation;
use Spatie\QueueableAction\QueueableAction;

final class GenerateConfirmedQuotationPdfAction
{
    use QueueableAction;

    public function __construct(private readonly QuotationPdfRenderer $pdfRenderer) {}

    public function execute(Quotation $quotation): string
    {
        $quotation->loadMissing(['items']);

        /** @var Customer $customer */
        $customer = Customer::query()->findOrFail((int) $quotation->customer_id);
        $customerName = (string) $customer->legal_name;

        $workOrderCode = null;
        if ($quotation->work_order_id !== null) {
            $workOrderCode = DB::table('work_orders')->where('id', $quotation->work_order_id)->value('code');
        }

        return $this->pdfRenderer->render('quotation::pdf.confirmed-quotation', [
            'quotation' => $quotation,
            'customerName' => $customerName,
            'workOrderCode' => $workOrderCode,
        ]);
    }
}
