<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\TimberBilling\Queries\GetTimberInventoryOverviewQuery;

final readonly class GenerateTimberInventoryPdfAction
{
    public function __construct(
        private GetTimberInventoryOverviewQuery $inventoryQuery,
    ) {}

    public function execute(?string $woodSpecies, ?string $statusCode): string
    {
        $overview = $this->inventoryQuery->execute($woodSpecies, $statusCode);

        return Pdf::loadView('timber.pdf.inventory', [
            'lots' => $overview['lots'],
            'materials' => $overview['materials'],
            'packagedUnits' => $overview['packagedUnits'],
            'stats' => $overview['stats'],
        ])->output();
    }
}
