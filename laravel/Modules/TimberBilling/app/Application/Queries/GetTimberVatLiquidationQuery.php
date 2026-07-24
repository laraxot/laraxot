<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Modules\Fiscal\Actions\CalculateVatLiquidationAction;
use Modules\Fiscal\Datas\VatableAmount;
use Modules\Fiscal\Datas\VatLiquidation;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberInvoice;

/**
 * Calcola la liquidazione IVA di periodo: raccoglie gli imponibili del registro
 * vendite (fatture Timber) e del registro acquisti (documenti di arrivo merce di
 * app/Timber) e delega l'aggregazione per aliquota e il saldo al calcolatore
 * Fiscal condiviso.
 */
final class GetTimberVatLiquidationQuery
{
    public function __construct(
        private readonly CalculateVatLiquidationAction $calculator,
    ) {}

    public function execute(string $fromDate, string $toDate): VatLiquidation
    {
        return $this->calculator->execute(
            $fromDate,
            $toDate,
            $this->salesAmounts($fromDate, $toDate),
            $this->purchaseAmounts($fromDate, $toDate),
        );
    }

    /**
     * @return array<int, VatableAmount>
     */
    private function salesAmounts(string $fromDate, string $toDate): array
    {
        $kinds = config('timberbilling.vat_register_kinds', []);
        $kinds = is_array($kinds) ? $kinds : [];

        $amounts = [];

        $invoices = TimberInvoice::query()
            ->whereIn('kind', $kinds)
            ->whereIn('status_code', ['issued', 'paid'])
            ->whereBetween('invoice_date', [$fromDate, $toDate])
            ->with('lines')
            ->get();

        foreach ($invoices as $invoice) {
            foreach ($invoice->lines as $line) {
                $amounts[] = new VatableAmount((float) $line->vat_rate, (float) $line->line_total);
            }
        }

        return $amounts;
    }

    /**
     * @return array<int, VatableAmount>
     */
    private function purchaseAmounts(string $fromDate, string $toDate): array
    {
        $types = config('timberbilling.vat_purchase_document_types', []);
        $types = is_array($types) ? $types : [];

        $amounts = [];

        $documents = TimberDocument::query()
            ->whereIn('document_type_code', $types)
            ->whereBetween('document_date', [$fromDate, $toDate])
            ->with('lines.product')
            ->get();

        foreach ($documents as $document) {
            foreach ($document->lines as $line) {
                $product = $line->product;
                $rate = ($product !== null && is_numeric($product->vat_rate_code)) ? (float) $product->vat_rate_code : 0.0;
                $amounts[] = new VatableAmount($rate, (float) $line->line_total);
            }
        }

        return $amounts;
    }
}
