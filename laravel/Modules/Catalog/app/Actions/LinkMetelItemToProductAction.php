<?php

declare(strict_types=1);

namespace Modules\Catalog\Actions;

use Modules\Catalog\Models\MetelCatalogItem;
use Modules\Catalog\Models\Product;
use Spatie\QueueableAction\QueueableAction;

/**
 * Promotes a METEL catalog item into the shared products catalog so it can be
 * referenced (and price-matched) by quotations. Matches an existing product by
 * SKU (= metel_code) to avoid duplicates, and links the item back to it.
 */
final class LinkMetelItemToProductAction
{
    use QueueableAction;

    public function execute(MetelCatalogItem $item): Product
    {
        $price = $item->net_price !== null ? (float) $item->net_price : (float) $item->gross_price;
        $description = (string) $item->description;

        /** @var Product $product */
        $product = Product::query()->updateOrCreate(
            ['sku' => $item->metel_code],
            [
                'name' => $description !== '' ? $description : (string) $item->metel_code,
                'description' => $description !== '' ? $description : null,
                'unit_price' => $price,
                'is_active' => true,
            ],
        );

        $item->forceFill(['product_id' => $product->getKey()])->save();

        return $product;
    }
}
