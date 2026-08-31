<?php

declare(strict_types=1);

namespace Modules\Bom\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Bom\Database\Factories\BomFactory;
use Modules\Catalog\Models\Product;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property bool $is_active
 */
final class Bom extends XotBaseModel
{

    protected $table = 'boms';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** @return HasMany<BomItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }
}
