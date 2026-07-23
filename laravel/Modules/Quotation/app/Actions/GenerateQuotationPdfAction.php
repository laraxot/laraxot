<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Platform\Company\CompanyProfileProvider;
use Modules\Quotation\Models\Quotation;

final class GenerateQuotationPdfAction
{
    public function __construct(private readonly CompanyProfileProvider $companyProfile) {}

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

        return Pdf::loadView('quotations::pdf.quotation', [
            'quotation' => $quotation,
            'customerName' => $customerName,
            'workOrderCode' => $workOrderCode,
            'supplier' => $this->companyProfile->current()->toSupplierArray(),
            'logo' => $this->companyProfile->logoDataUri(),
        ])->output();
    }
}
