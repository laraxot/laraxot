<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Illuminate\Support\Collection;
use Modules\Customer\Models\Customer;
use Modules\Quotation\DTO\Money;
use Modules\TimberBilling\Domain\DTO\CustomerSalesRow;
use Modules\TimberBilling\Domain\DTO\TimberAnalyticsSummary;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\TimberBilling\Models\TimberPaymentInstallment;

/**
 * Analisi vendite / acquisti / flussi di cassa per periodo, con classifica dei
 * migliori clienti per fatturato emesso.
 */
final class GetTimberBusinessAnalyticsQuery
{
    public function execute(string $fromDate, string $toDate, int $topCustomers = 5): TimberAnalyticsSummary
    {
        $issuedInvoices = TimberInvoice::query()
            ->whereIn('status_code', ['issued', 'paid'])
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->get(['id', 'customer_id', 'total_amount']);

        $salesTotal = Money::fromFloat((float) $issuedInvoices->sum(fn (TimberInvoice $invoice): float => (float) $invoice->total_amount));

        $purchasesTotal = Money::fromFloat((float) TimberDocument::query()
            ->whereIn('document_type_code', $this->purchaseTypes())
            ->whereBetween('document_date', [$fromDate, $toDate])
            ->sum('total_amount'));

        $collectedTotal = Money::fromFloat((float) TimberPaymentInstallment::query()
            ->where('status_code', 'saldato')
            ->whereBetween('due_date', [$fromDate, $toDate])
            ->sum('amount'));

        $outstandingTotal = Money::fromFloat((float) TimberPaymentInstallment::query()
            ->where('status_code', '!=', 'saldato')
            ->whereDate('due_date', '<=', $toDate)
            ->sum('amount'));

        return new TimberAnalyticsSummary(
            fromDate: $fromDate,
            toDate: $toDate,
            salesTotal: $salesTotal,
            purchasesTotal: $purchasesTotal,
            collectedTotal: $collectedTotal,
            outstandingTotal: $outstandingTotal,
            topCustomers: $this->topCustomers($issuedInvoices, $topCustomers),
        );
    }

    /**
     * @param  Collection<int, TimberInvoice>  $invoices
     * @return array<int, CustomerSalesRow>
     */
    private function topCustomers($invoices, int $limit): array
    {
        $byCustomer = $invoices
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->map(fn ($group): float => (float) $group->sum(fn (TimberInvoice $invoice): float => (float) $invoice->total_amount))
            ->sortDesc()
            ->take($limit);

        if ($byCustomer->isEmpty()) {
            return [];
        }

        $names = Customer::query()
            ->whereIn('id', $byCustomer->keys()->all())
            ->pluck('business_name', 'id');

        $rows = [];
        foreach ($byCustomer as $customerId => $total) {
            $id = (int) $customerId;
            $name = $names[$id] ?? null;
            $rows[] = new CustomerSalesRow(
                customerName: is_string($name) ? $name : 'Cliente #'.$id,
                total: Money::fromFloat($total),
            );
        }

        return $rows;
    }

    /**
     * @return array<int, string>
     */
    private function purchaseTypes(): array
    {
        $types = config('timberbilling.vat_purchase_document_types', []);

        return is_array($types) ? array_values(array_filter($types, 'is_string')) : [];
    }
}
