<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Timber\Models\TimberCustomer;

/**
 * Scheda cliente stampabile (anagrafica, contatti, condizioni commerciali, note).
 */
final class GenerateTimberCustomerSheetPdfAction
{
    public function execute(TimberCustomer $customer): string
    {
        /** @var view-string $view */
        $view = 'timber::pdf.customer-sheet';

        return Pdf::loadView($view, [
            'customer' => $customer->loadMissing('agent'),
        ])->output();
    }
}
