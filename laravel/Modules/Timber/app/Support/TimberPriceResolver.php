<?php

declare(strict_types=1);

namespace Modules\Timber\Support;

use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberCustomerPrice;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberPriceListItem;
use Modules\Timber\Models\TimberProduct;

/**
 * Risolve il prezzo di vendita di un prodotto per un cliente, in ordine:
 * 1) prezzo personalizzato del cliente, 2) prezzo del listino assegnato al
 * cliente, 3) prezzo legacy nel catalogo prodotto, 4) nessun prezzo.
 */
final class TimberPriceResolver
{
    public function priceFor(TimberCustomer $customer, TimberProduct $product): ?float
    {
        $override = TimberCustomerPrice::query()
            ->where('customer_id', $customer->getKey())
            ->where('product_id', $product->getKey())
            ->first();
        if ($override !== null) {
            return (float) $override->net_price;
        }

        $priceListCode = trim((string) ($customer->price_list_code ?? ''));
        if ($priceListCode !== '') {
            $list = TimberPriceList::query()->where('code', $priceListCode)->first();
            if ($list !== null) {
                $item = TimberPriceListItem::query()
                    ->where('price_list_id', $list->getKey())
                    ->where('product_id', $product->getKey())
                    ->first();
                if ($item !== null) {
                    return (float) $item->net_price;
                }
            }

            $legacy = $product->priceForList($priceListCode);
            if ($legacy !== null) {
                return $legacy;
            }
        }

        return null;
    }
}
