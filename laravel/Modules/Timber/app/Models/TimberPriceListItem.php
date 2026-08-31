<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberPriceListItemFactory;

/**
 * @property int $price_list_id
 * @property int $product_id
 * @property string $net_price
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberPriceListItem extends BaseModel
{

    protected $table = 'timber_price_list_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'net_price' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<TimberPriceList, $this> */
    public function priceList(): BelongsTo
    {
        return $this->belongsTo(TimberPriceList::class, 'price_list_id');
    }

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    protected static function newFactory(): TimberPriceListItemFactory
    {
        return TimberPriceListItemFactory::new();
    }
}
