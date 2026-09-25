<?php

declare(strict_types=1);

namespace Modules\Quotation\Import;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

final class QuotationPdfRenderer
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function render(string $view, array $data): string
    {
        self::assertViewExists($view);
        $rendered = ViewFacade::make($view, $data);
        if (! $rendered instanceof View) {
            throw new Html2PdfException('Unable to render PDF view ['.$view.'].');
        }

        $html = $rendered->render();
        $html2pdf = new Html2Pdf('P', 'A4', 'it', true, 'UTF-8', [5, 5, 5, 8]);
        $html2pdf->writeHTML($html);

        return (string) $html2pdf->output('quotation.pdf', 'S');
    }

    /**
     * @phpstan-assert view-string $view
     */
    private static function assertViewExists(string $view): void
    {
        if (! view()->exists($view)) {
            throw new Html2PdfException('Unable to render PDF view ['.$view.'].');
        }
    }
}
