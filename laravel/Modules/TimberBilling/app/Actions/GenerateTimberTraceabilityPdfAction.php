<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\TimberBilling\Queries\SearchTimberTraceabilityQuery;

final readonly class GenerateTimberTraceabilityPdfAction
{
    public function __construct(
        private SearchTimberTraceabilityQuery $traceabilityQuery,
    ) {}

    public function execute(string $search): string
    {
        $result = $this->traceabilityQuery->execute($search);

        /** @var view-string $view */
        $view = 'timber.pdf.traceability';

        return Pdf::loadView($view, [
            'purchaseRecord' => $result['purchaseRecord'],
            'lot' => $result['lot'],
            'derivedMaterial' => $result['derivedMaterial'],
            'packagedUnit' => $result['packagedUnit'],
            'processingSteps' => $result['processingSteps'],
        ])->output();
    }
}
