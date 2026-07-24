<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Modules\TimberBilling\Models\TimberStockMovement;

/**
 * Giacenza a magazzino di un prodotto, suddivisa per lotto/seriale, ricostruita
 * dai movimenti (carico - scarico). Usata dal DDT "intelligente" per proporre
 * all'operatore da quale lotto scaricare.
 */
final class GetTimberProductStockByLotQuery
{
    /**
     * @return array<int, array{lot_code: string, available: float}>
     */
    public function execute(int $productId): array
    {
        $movements = TimberStockMovement::query()
            ->where('product_id', $productId)
            ->whereNotNull('lot_code')
            ->orderBy('movement_date')
            ->orderBy('id')
            ->get(['lot_code', 'direction_code', 'quantity']);

        /** @var array<string, float> $byLot */
        $byLot = [];
        /** @var array<string, int> $order */
        $order = [];
        $seq = 0;

        foreach ($movements as $movement) {
            $lot = (string) $movement->lot_code;
            if (! array_key_exists($lot, $byLot)) {
                $byLot[$lot] = 0.0;
                $order[$lot] = $seq++;
            }
            $byLot[$lot] += $movement->signedQuantity();
        }

        $rows = [];
        foreach ($byLot as $lot => $available) {
            if (round($available, 3) > 0) {
                $rows[] = ['lot_code' => $lot, 'available' => round($available, 3), '_order' => $order[$lot]];
            }
        }

        // FIFO: lotto entrato prima proposto per primo.
        usort($rows, static fn (array $a, array $b): int => $a['_order'] <=> $b['_order']);

        return array_map(
            static fn (array $row): array => ['lot_code' => $row['lot_code'], 'available' => $row['available']],
            $rows,
        );
    }

    public function totalAvailable(int $productId): float
    {
        $total = 0.0;
        foreach ($this->execute($productId) as $row) {
            $total += $row['available'];
        }

        return round($total, 3);
    }
}
