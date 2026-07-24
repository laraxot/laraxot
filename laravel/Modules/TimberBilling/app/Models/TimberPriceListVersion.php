<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $price_list_id
 * @property array<int, mixed> $items
 * @property string|null $note
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberPriceListVersion extends Model
{
    protected $table = 'timber_price_list_versions';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }

    /** @return BelongsTo<TimberPriceList, $this> */
    public function priceList(): BelongsTo
    {
        return $this->belongsTo(TimberPriceList::class, 'price_list_id');
    }
}
