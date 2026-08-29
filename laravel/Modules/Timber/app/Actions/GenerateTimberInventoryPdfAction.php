<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Timber\Queries\GetTimberInventoryOverviewQuery;

final readonly class GenerateTimberInventoryPdfAction
{
    public function __construct(
        private GetTimberInventoryOverviewQuery $inventoryQuery,
    ) {}

    public function execute(?string $woodSpecies, ?string $statusCode): string
    {
        $overview = $this->inventoryQuery->execute($woodSpecies, $statusCode);

        /** @var view-string $view */
        $view = 'timber::pdf.inventory';

        return Pdf::loadView($view, [
            'lots' => $overview['lots'],
            'materials' => $overview['materials'],
            'packagedUnits' => $overview['packagedUnits'],
            'stats' => $overview['stats'],
        ])->output();
    }
}
