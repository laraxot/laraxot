<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\TimberBilling\Models\TimberCustomer;

/**
 * Scheda cliente stampabile (anagrafica, contatti, condizioni commerciali, note).
 */
final class GenerateTimberCustomerSheetPdfAction
{
    public function execute(TimberCustomer $customer): string
    {
        return Pdf::loadView('timber.pdf.customer-sheet', [
            'customer' => $customer->loadMissing('agent'),
        ])->output();
    }
}
