<?php

declare(strict_types=1);

namespace Modules\Bom\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Bom\Database\Factories\BomItemFactory;
use Modules\Catalog\Models\Product;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $bom_id
 * @property int $component_product_id
 * @property numeric-string $quantity
 */
final class BomItem extends XotBaseModel
{
    /** @use HasFactory<BomItemFactory> */
    use HasFactory;

    protected $table = 'bom_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Bom, $this> */
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }
}
