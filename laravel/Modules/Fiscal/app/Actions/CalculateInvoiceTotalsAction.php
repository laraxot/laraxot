<?php

declare(strict_types=1);

namespace Modules\Fiscal\Actions;

use Modules\Fiscal\Datas\FiscalRate;
use Modules\Fiscal\Datas\InvoiceLineData;
use Modules\Fiscal\Datas\InvoiceTotals;
use Modules\Quotation\DTO\Money;
use Spatie\QueueableAction\QueueableAction;

/**
 * Pure fiscal totals calculator for an invoice.
 *
 * Reuses the shared Money value object (Modules\Quotations) but, unlike the
 * quotation calculator, applies a per-line VAT rate and returns the VAT
 * breakdown by rate (required for the Italian "riepilogo IVA").
 */
final class CalculateInvoiceTotalsAction
{
    use QueueableAction;

    /**
     * @param  array<int, InvoiceLineData>  $lines
     */
    public function execute(array $lines): InvoiceTotals
    {
        $taxable = Money::zero();
        $vat = Money::zero();

        /** @var array<int, Money> $lineTotals */
        $lineTotals = [];
        /** @var array<string, Money> $vatByRate */
        $vatByRate = [];
        /** @var array<string, Money> $taxableByRate */
        $taxableByRate = [];

        foreach ($lines as $index => $line) {
            $gross = Money::fromFloat($line->quantity * $line->unitPrice);
            $net = Money::fromFloat($gross->asFloat() * (1 - ($line->discountPercent / 100)));
            $lineVat = Money::fromFloat($net->asFloat() * ($line->vatRatePercent / 100));

            $taxable = $taxable->add($net);
            $vat = $vat->add($lineVat);
            $lineTotals[$index] = $net;

            $rateKey = FiscalRate::key($line->vatRatePercent);
            $vatByRate[$rateKey] = ($vatByRate[$rateKey] ?? Money::zero())->add($lineVat);
            $taxableByRate[$rateKey] = ($taxableByRate[$rateKey] ?? Money::zero())->add($net);
        }

        return new InvoiceTotals(
            taxable: $taxable,
            vat: $vat,
            total: $taxable->add($vat),
            lineTotals: $lineTotals,
            vatByRate: $vatByRate,
            taxableByRate: $taxableByRate,
        );
    }
}
