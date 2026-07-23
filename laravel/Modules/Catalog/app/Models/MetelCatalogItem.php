<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MetelCatalogItem extends Model
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
