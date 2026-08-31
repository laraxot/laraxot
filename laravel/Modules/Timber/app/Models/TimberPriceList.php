<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberPriceListFactory;

/**
 * @property string $code
 * @property string $name
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberPriceList extends BaseModel
{

    protected $table = 'timber_price_lists';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }

    /** @return HasMany<TimberPriceListItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(TimberPriceListItem::class, 'price_list_id');
    }

    /** @return HasMany<TimberPriceListVersion, $this> */
    public function versions(): HasMany
    {
        return $this->hasMany(TimberPriceListVersion::class, 'price_list_id');
    }

    protected static function newFactory(): TimberPriceListFactory
    {
        return TimberPriceListFactory::new();
    }
}
