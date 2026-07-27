<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\TimberBilling\Models\TimberCustomer;

/**
 * Etichetta cliente (indirizzo per spedizione/corrispondenza).
 */
final class GenerateTimberCustomerLabelPdfAction
{
    public function execute(TimberCustomer $customer): string
    {
        /** @var view-string $view */
        $view = 'timber.pdf.customer-label';

        $dompdf = new Dompdf(new Options());
        $dompdf->loadHtml(view($view, ['customer' => $customer])->render());
        $dompdf->setPaper([0, 0, 288, 180]);
        $dompdf->render();

        return (string) $dompdf->output();
    }
}
