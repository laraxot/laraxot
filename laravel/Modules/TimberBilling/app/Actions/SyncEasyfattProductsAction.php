<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Models\Product;
use Modules\TimberBilling\Legacy\EasyfattGateway;

final readonly class SyncEasyfattProductsAction
{
    public function __construct(
        private EasyfattGateway $gateway,
    ) {}

    /**
     * @return array{created: int, updated: int}
     */
    public function execute(): array
    {
        $created = 0;
        $updated = 0;

        DB::transaction(function () use (&$created, &$updated): void {
            foreach ($this->gateway->fetchProducts() as $productData) {
                if ($productData->productName === '' || $productData->productCode === '') {
                    continue;
                }

                $product = Product::query()->firstOrNew([
                    'sku' => $productData->productCode,
                ]);

                $wasRecentlyCreated = ! $product->exists;

                $product->fill([
                    'name' => $productData->productName,
                    'sku' => $productData->productCode,
                    'unit_price' => $productData->unitPrice,
                    'is_active' => true,
                ])->save();

                $wasRecentlyCreated ? $created++ : $updated++;
            }
        });

        return ['created' => $created, 'updated' => $updated];
    }
}
