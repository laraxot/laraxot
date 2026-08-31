<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Datas\WtsInventoryRow;
use Modules\Wts\Queries\GetWtsInventoryOverviewQuery;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use function Safe\tempnam;

/**
 * Esporta la giacenza WTS in un vero file .xlsx (Excel), con le stesse colonne
 * e gli stessi filtri della stampa PDF. Ritorna il percorso del file temporaneo
 * generato: chi chiama lo streamma e poi lo cancella.
 */
final readonly class GenerateWtsInventoryExcelAction
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

        /** @var \Illuminate\Support\Collection<int, WtsInventoryRow> $rows */
        $rows = $overview['rows'];

        // Safe\tempnam solleva gia' l'eccezione al posto del false storico.
        $path = tempnam(sys_get_temp_dir(), 'wts-giacenza-');

        $writer = new Writer;
        $writer->openToFile($path);

        $writer->addRow(Row::fromValues([
            'Partita', 'Sub', 'Formulario', 'Scheda tecnica', 'Descrizione scheda',
            'Produttore', 'HP', 'Stato fisico', 'Trattamento', 'Linea', 'Ciclo',
            'CER', 'Colli', 'Peso (kg)', 'Zona', 'Nota 1', 'Nota 2', 'Stato',
        ]));

        foreach ($rows as $row) {
            $writer->addRow(Row::fromValues([
                $row->batchCode,
                $row->unitSubCode,
                $row->formReference ?? '',
                $row->sheetCode ?? '',
                $row->sheetDescription ?? $row->productionDescription ?? $row->packagingDescription ?? '',
                $row->producerName ?? '',
                $row->hpCodes ?? '',
                trim(($row->physicalStateCode ?? '').' '.($row->physicalStateLabel ?? '')),
                trim(($row->treatmentCode ?? '').' '.($row->treatmentLabel ?? '')),
                $row->treatmentLineCode ?? '',
                $row->productionCycleCode ?? '',
                $row->wasteCode ?? '',
                $row->colliCount ?? 0,
                $row->weightKg ?? 0.0,
                $row->zoneLabel ?? $row->zoneCode ?? '',
                $row->note1 ?? '',
                $row->note2 ?? '',
                $row->isAvailable() ? 'Disponibile' : 'Gia lavorato',
            ]));
        }

        $totalColli = (int) $rows->sum(static fn (WtsInventoryRow $row): int => $row->colliCount ?? 0);
        $totalWeight = (float) $rows->sum(static fn (WtsInventoryRow $row): float => $row->weightKg ?? 0.0);

        $writer->addRow(Row::fromValues([
            'TOTALE', '', '', '', '', '', '', '', '', '', '',
            $rows->count().' partite', $totalColli, $totalWeight, '', '', '', '',
        ]));

        $writer->close();

        return $path;
    }
}
