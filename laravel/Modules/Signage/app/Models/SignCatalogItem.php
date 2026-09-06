<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignCatalogItemFactory;

/**
 * @property int $id
 * @property int $sign_price_list_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $notes
 * @property string|null $family_code
 * @property string|null $color_code
 * @property numeric-string|null $thickness_mm
 * @property int|null $letter_height_min_mm
 * @property int|null $letter_height_max_mm
 * @property string|null $led_temperature_code
 * @property string $unit_code
 * @property numeric-string|null $markup_percent
 * @property numeric-string|null $manual_sale_price
 * @property int $sort_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignCatalogItem extends BaseModel
{

    protected $table = 'sign_catalog_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'thickness_mm' => 'decimal:2',
            'markup_percent' => 'decimal:2',
            'manual_sale_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<SignPriceList, $this> */
    public function priceList(): BelongsTo
    {
        return $this->belongsTo(SignPriceList::class, 'sign_price_list_id');
    }

    /** @return HasMany<SignCatalogItemComponent, $this> */
    public function components(): HasMany
    {
        return $this->hasMany(SignCatalogItemComponent::class, 'sign_catalog_item_id')->orderBy('position');
    }

    /** @return SignCatalogItemFactory */
    public static function factory(): SignCatalogItemFactory
    {
        return SignCatalogItemFactory::new();
    }

    protected static function newFactory(): SignCatalogItemFactory
    {
        return SignCatalogItemFactory::new();
    }
}
