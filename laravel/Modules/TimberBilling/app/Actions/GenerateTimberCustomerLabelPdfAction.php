<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\TimberBilling\Models\TimberCustomer;

/**
 * Etichetta cliente (indirizzo per spedizione/corrispondenza).
 */
final class GenerateTimberCustomerLabelPdfAction
{
    public function execute(TimberCustomer $customer): string
    {
        return Pdf::loadView('timber.pdf.customer-label', [
            'customer' => $customer,
        ])->setPaper([0, 0, 288, 180])->output();
    }
}
