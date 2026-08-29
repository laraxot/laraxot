<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberProductFactory;

/**
 * @property string|null $code
 * @property string $description
 * @property string $product_type_code
 * @property string|null $category
 * @property string|null $subcategory
 * @property string $unit_of_measure
 * @property array<string, mixed>|null $list_prices
 * @property string|null $notes
 * @property string|null $barcode
 * @property string|null $vat_rate_code
 * @property string|null $commission_class
 * @property string|null $product_url
 * @property string|null $manufacturer
 * @property bool $is_assembled
 * @property bool $publish_on_ecommerce
 * @property string|null $free_1
 * @property string|null $free_2
 * @property string|null $free_3
 * @property string|null $free_4
 * @property int|null $supplier_id
 * @property string|null $supplier_product_code
 * @property string|null $supplier_net_price
 * @property string|null $supply_notes
 * @property string|null $reorder_multiple
 * @property int|null $reorder_lead_days
 * @property string|null $minimum_stock
 * @property string|null $warehouse_location
 * @property string|null $average_cost
 * @property string|null $last_cost
 * @property bool $is_active
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class TimberProduct extends BaseModel
{
    /** @use HasFactory<TimberProductFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'timber_products';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'list_prices' => 'array',
            'is_assembled' => 'bool',
            'publish_on_ecommerce' => 'bool',
            'is_active' => 'bool',
            'supplier_net_price' => 'decimal:4',
            'reorder_multiple' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'average_cost' => 'decimal:4',
            'last_cost' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<TimberSupplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(TimberSupplier::class, 'supplier_id');
    }

    /**
     * Net price configured for a given price list, or null when not set.
     */
    public function priceForList(string $priceListCode): ?float
    {
        $prices = $this->list_prices;

        if (! is_array($prices)) {
            return null;
        }

        foreach ($prices as $row) {
            if (is_array($row)
                && ($row['price_list_code'] ?? null) === $priceListCode
                && isset($row['net_price'])
                && is_numeric($row['net_price'])
            ) {
                return (float) $row['net_price'];
            }
        }

        return null;
    }

    protected static function newFactory(): TimberProductFactory
    {
        return TimberProductFactory::new();
    }
}
