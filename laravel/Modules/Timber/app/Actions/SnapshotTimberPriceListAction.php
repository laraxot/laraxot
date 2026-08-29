<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberPriceListVersion;

/**
 * Salva una versione (storico) del listino con lo stato attuale dei prezzi.
 */
final class SnapshotTimberPriceListAction
{
    public function execute(TimberPriceList $priceList, int|string|null $userId = null, ?string $note = null): TimberPriceListVersion
    {
        $items = $priceList->items()->with('product')->get()
            ->map(static fn ($item): array => [
                'product_id' => (int) $item->product_id,
                'product_code' => (string) ($item->product->code ?? ''),
                'net_price' => (float) $item->net_price,
            ])
            ->all();

        /** @var TimberPriceListVersion $version */
        $version = $priceList->versions()->create([
            'items' => $items,
            'note' => $note,
            'created_by_user_id' => $userId,
        ]);

        return $version;
    }
}
