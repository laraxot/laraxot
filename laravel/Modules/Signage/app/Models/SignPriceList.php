<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignPriceListFactory;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $brand
 * @property int|null $customer_id
 * @property numeric-string|null $default_markup_percent
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignPriceList extends BaseModel
{

    protected $table = 'sign_price_lists';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'default_markup_percent' => 'decimal:2',
        ];
    }

    /** @return HasMany<SignCatalogItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SignCatalogItem::class, 'sign_price_list_id');
    }

    /** @return SignPriceListFactory */
    public static function factory(): SignPriceListFactory
    {
        return SignPriceListFactory::new();
    }

    protected static function newFactory(): SignPriceListFactory
    {
        return SignPriceListFactory::new();
    }
}
