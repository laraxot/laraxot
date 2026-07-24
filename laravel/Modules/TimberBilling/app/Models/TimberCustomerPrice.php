<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberCustomerPriceFactory;

/**
 * @property int $customer_id
 * @property int $product_id
 * @property string $net_price
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberCustomerPrice extends Model
{
    /** @use HasFactory<TimberCustomerPriceFactory> */
    use HasFactory;

    protected $table = 'timber_customer_prices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'net_price' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<TimberCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(TimberCustomer::class, 'customer_id');
    }

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    protected static function newFactory(): TimberCustomerPriceFactory
    {
        return TimberCustomerPriceFactory::new();
    }
}
