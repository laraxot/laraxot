<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Models\Invoice;
use Modules\Platform\Company\CompanyProfileProvider;

final class GenerateInvoicePdfAction
{
    public function __construct(private readonly CompanyProfileProvider $companyProfile) {}

    public function execute(Invoice $invoice): string
    {
        $customer = DB::table('customers')
            ->where('id', (int) $invoice->customer_id)
            ->select([
                'id',
                'legal_name',
                'vat_number',
                'tax_code',
                'email',
                'phone',
                'address_line1',
                'address_line2',
                'postal_code',
                'city',
                'province',
                'country_code',
            ])
            ->first();

        $customerName = '#'.(int) $invoice->customer_id;
        if ($customer !== null && is_string($customer->legal_name) && trim($customer->legal_name) !== '') {
            $customerName = $customer->legal_name;
        }

        $workOrderCode = null;
        if ($invoice->work_order_id !== null) {
            $workOrderCode = DB::table('work_orders')
                ->where('id', (int) $invoice->work_order_id)
                ->value('code');
        }

        $linkedWorkOrders = DB::table('invoice_work_order_links')
            ->where('invoice_id', (int) $invoice->id)
            ->join('work_orders', 'work_orders.id', '=', 'invoice_work_order_links.work_order_id')
            ->orderByDesc('work_orders.id')
            ->pluck('work_orders.code')
            ->filter(fn ($v) => is_string($v) && trim($v) !== '')
            ->values()
            ->all();

        /** @var array<int, string> $linkedWorkOrders */
        $linkedWorkOrders = array_values(array_unique($linkedWorkOrders));
        if (is_string($workOrderCode) && trim($workOrderCode) !== '') {
            array_unshift($linkedWorkOrders, $workOrderCode);
            $linkedWorkOrders = array_values(array_unique($linkedWorkOrders));
        }

        $supplier = $this->companyProfile->current()->toSupplierArray();

        return Pdf::loadView('billing::pdf.invoice', [
            'invoice' => $invoice,
            'customerName' => $customerName,
            'customer' => $customer,
            'supplier' => $supplier,
            'logo' => $this->companyProfile->logoDataUri(),
            'workOrderCode' => $workOrderCode,
            'linkedWorkOrders' => $linkedWorkOrders,
        ])->output();
    }
}
