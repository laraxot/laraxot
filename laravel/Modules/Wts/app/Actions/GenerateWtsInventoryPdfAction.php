<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use Barryvdh\DomPDF\Facade\Pdf;

final readonly class GenerateWtsInventoryPdfAction
{
    public function __construct(
        private GetWtsInventoryOverviewQuery $inventoryQuery,
    ) {}

    public function execute(
        ?string $wasteCode,
        ?string $zone,
        ?string $referenceDate,
        string $wasteCodeOperator = 'contains',
        string $zoneOperator = 'contains',
    ): string {
        $overview = $this->inventoryQuery->execute($wasteCode, $zone, $referenceDate, $wasteCodeOperator, $zoneOperator);

        // Orizzontale: con scheda tecnica, descrizione, HP, stato fisico e
        // trattamento in verticale le colonne non ci stanno e la stampa esplode
        // in decine di pagine.
        return Pdf::loadView('wts::pdf.inventory', [
            'rows' => $overview['rows'],
            'stats' => $overview['stats'],
            'referenceDate' => $referenceDate,
            'dataSource' => $overview['dataSource'],
            'usesEcosStock' => $overview['usesEcosStock'],
        ])->setPaper('a4', 'landscape')->output();
    }
}
