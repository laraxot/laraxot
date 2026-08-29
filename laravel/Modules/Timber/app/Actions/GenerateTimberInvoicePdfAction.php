<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Fiscal\Actions\CalculateInvoiceTotalsAction;
use Modules\Fiscal\Datas\InvoiceLineData;
use Modules\Timber\Models\TimberInvoice;
use Spatie\QueueableAction\QueueableAction;

/**
 * Genera il PDF (anteprima/stampa) di un documento fiscale Timber
 * (preventivo/fattura/...), con righe e riepilogo IVA.
 */
final class GenerateTimberInvoicePdfAction
{
    use QueueableAction;

    public function __construct(
        private readonly CalculateInvoiceTotalsAction $calculator,
    ) {}

    public function execute(TimberInvoice $invoice): string
    {
        $invoice->loadMissing(['lines', 'customer']);

        $totals = $this->calculator->execute(
            $invoice->lines
                ->map(fn ($line): InvoiceLineData => new InvoiceLineData(
                    quantity: (float) $line->quantity,
                    unitPrice: (float) $line->unit_price,
                    discountPercent: (float) $line->discount_percent,
                    vatRatePercent: (float) $line->vat_rate,
                ))
                ->all(),
        );

        $company = config('timber.company', []);
        $kinds = config('timber.invoice_kinds', []);

        return Pdf::loadView('timber::pdf.invoice', [
            'invoice' => $invoice,
            'totals' => $totals,
            'company' => is_array($company) ? $company : [],
            'kindLabel' => is_array($kinds) && isset($kinds[$invoice->kind]) && is_string($kinds[$invoice->kind])
                ? $kinds[$invoice->kind]
                : (string) $invoice->kind,
        ])->output();
    }
}
