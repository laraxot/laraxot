<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Modules\Timber\Models\TimberPriceList;

/**
 * Esporta un listino in CSV (apribile in Excel): codice prodotto, descrizione, prezzo.
 */
final class ExportTimberPriceListCsvAction
{
    public function execute(TimberPriceList $priceList): string
    {
        $rows = [['Codice prodotto', 'Descrizione', 'Prezzo netto']];

        foreach ($priceList->items()->with('product')->get() as $item) {
            $product = $item->product;
            $rows[] = [
                $product !== null ? (string) ($product->code ?? '') : '',
                $product !== null ? (string) $product->description : '',
                number_format((float) $item->net_price, 4, '.', ''),
            ];
        }

        return implode("\n", array_map(
            static fn (array $row): string => implode(',', array_map([self::class, 'field'], $row)),
            $rows,
        ))."\n";
    }

    private static function field(string $value): string
    {
        if (str_contains($value, ',') || str_contains($value, '"') || str_contains($value, "\n")) {
            return '"'.str_replace('"', '""', $value).'"';
        }

        return $value;
    }
}
