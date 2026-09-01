<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignPriceListItemFactory;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $category_code
 * @property string|null $work_center_code
 * @property string|null $supplier_name
 * @property string|null $supplier_code
 * @property string $unit_code
 * @property numeric-string $unit_cost
 * @property string $currency
 * @property string|null $notes
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignPriceListItem extends BaseModel
{

    protected $table = 'sign_price_list_items';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<SignComponent, $this> */
    public function components(): HasMany
    {
        return $this->hasMany(SignComponent::class, 'price_list_item_id');
    }

    /** @return HasMany<SignSupplierPriceEntry, $this> */
    public function priceEntries(): HasMany
    {
        return $this->hasMany(SignSupplierPriceEntry::class, 'sign_price_list_item_id');
    }

    protected static function newFactory(): SignPriceListItemFactory
    {
        return SignPriceListItemFactory::new();
    }
}
