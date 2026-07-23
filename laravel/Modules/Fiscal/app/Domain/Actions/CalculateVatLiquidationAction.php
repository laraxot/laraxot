<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\Actions;

use Modules\Fiscal\Domain\DTO\VatableAmount;
use Modules\Fiscal\Domain\DTO\VatLiquidation;
use Modules\Fiscal\Domain\DTO\VatSummaryRow;
use Modules\Fiscal\Domain\ValueObjects\FiscalRate;
use Modules\Quotation\Domain\ValueObjects\Money;

/**
 * Pure VAT liquidation calculator for a period: groups taxable amounts by rate
 * for the sales register (IVA a debito) and the purchase register (IVA a
 * credito) and returns the period balance. It never queries persistence: each
 * vertical fetches its own sales/purchase amounts and feeds them here.
 */
final class CalculateVatLiquidationAction
{
    /**
     * @param  array<int, VatableAmount>  $sales  Taxable amounts from the sales register.
     * @param  array<int, VatableAmount>  $purchases  Taxable amounts from the purchase register.
     */
    public function execute(string $fromDate, string $toDate, array $sales, array $purchases): VatLiquidation
    {
        $salesRows = $this->rows($sales);
        $purchaseRows = $this->rows($purchases);

        $totalDebit = $this->sumVat($salesRows);
        $totalCredit = $this->sumVat($purchaseRows);

        return new VatLiquidation(
            fromDate: $fromDate,
            toDate: $toDate,
            salesRows: $salesRows,
            purchaseRows: $purchaseRows,
            totalDebit: $totalDebit,
            totalCredit: $totalCredit,
            balance: $totalDebit->sub($totalCredit),
        );
    }

    /**
     * @param  array<int, VatableAmount>  $amounts
     * @return array<int, VatSummaryRow>
     */
    private function rows(array $amounts): array
    {
        /** @var array<string, array{taxable: Money, vat: Money}> $buckets */
        $buckets = [];

        foreach ($amounts as $amount) {
            $key = FiscalRate::key($amount->ratePercent);
            $vat = $amount->taxable * ($amount->ratePercent / 100);

            if (! isset($buckets[$key])) {
                $buckets[$key] = ['taxable' => Money::zero(), 'vat' => Money::zero()];
            }

            $buckets[$key]['taxable'] = $buckets[$key]['taxable']->add(Money::fromFloat($amount->taxable));
            $buckets[$key]['vat'] = $buckets[$key]['vat']->add(Money::fromFloat($vat));
        }

        krsort($buckets);

        $rows = [];
        foreach ($buckets as $rate => $bucket) {
            $rows[] = new VatSummaryRow((string) $rate, $bucket['taxable'], $bucket['vat']);
        }

        return $rows;
    }

    /**
     * @param  array<int, VatSummaryRow>  $rows
     */
    private function sumVat(array $rows): Money
    {
        $sum = Money::zero();
        foreach ($rows as $row) {
            $sum = $sum->add($row->vat);
        }

        return $sum;
    }
}
