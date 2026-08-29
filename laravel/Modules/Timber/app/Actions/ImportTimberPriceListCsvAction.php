<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberPriceListItem;
use Modules\Timber\Models\TimberProduct;

use function Safe\preg_split;

/**
 * Importa/aggiorna i prezzi di un listino da un CSV (codice prodotto, [descrizione,] prezzo).
 * L'ultima colonna numerica e' il prezzo; il prodotto e' abbinato per codice.
 * Salva una versione del listino a fine import.
 */
final class ImportTimberPriceListCsvAction
{
    public function __construct(
        private readonly SnapshotTimberPriceListAction $snapshot,
    ) {}

    /**
     * @return array{imported: int, skipped: int}
     */
    public function execute(TimberPriceList $priceList, string $csvContent, ?int $userId = null): array
    {
        $imported = 0;
        $skipped = 0;

        /** @var list<string> $lines */
        $lines = preg_split('/\r\n|\r|\n/', trim($csvContent));

        DB::transaction(function () use ($priceList, $lines, &$imported, &$skipped): void {
            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }

                // Excel italiano usa ';' come separatore (cosi la virgola decimale
                // resta nel prezzo); altrimenti il classico ','.
                $delimiter = str_contains($line, ';') ? ';' : ',';
                $columns = str_getcsv($line, $delimiter);
                $code = trim((string) ($columns[0] ?? ''));
                $priceRaw = trim((string) ($columns[count($columns) - 1] ?? ''));
                $priceRaw = str_replace(',', '.', $priceRaw);

                // Salta intestazione o righe senza prezzo numerico.
                if ($code === '' || ! is_numeric($priceRaw)) {
                    $skipped++;

                    continue;
                }

                $product = TimberProduct::query()->where('code', $code)->first();
                if ($product === null) {
                    $skipped++;

                    continue;
                }

                TimberPriceListItem::query()->updateOrCreate(
                    ['price_list_id' => $priceList->getKey(), 'product_id' => $product->getKey()],
                    ['net_price' => (float) $priceRaw],
                );
                $imported++;
            }
        });

        $this->snapshot->execute($priceList, $userId, 'Import CSV');

        return ['imported' => $imported, 'skipped' => $skipped];
    }
}
