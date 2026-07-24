<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $metel_catalog_id
 * @property int|null $product_id
 * @property string $metel_code
 * @property string|null $barcode
 * @property string|null $etim_code
 * @property string $description
 * @property string|null $unit_of_measure
 * @property string|null $packaging
 * @property float $gross_price
 * @property float|null $net_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetelCatalogItem extends XotBaseModel
{
    protected $table = 'metel_catalog_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'gross_price' => 'decimal:4',
            'net_price' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<MetelCatalog, $this> */
    public function catalog(): BelongsTo
    {
        return $this->belongsTo(MetelCatalog::class, 'metel_catalog_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
