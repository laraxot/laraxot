<?php

declare(strict_types=1);

namespace Modules\Signage\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Signage\Database\Factories\SignCatalogItemComponentFactory;

/**
 * @property int $id
 * @property int $sign_catalog_item_id
 * @property int $position
 * @property string $work_center_code
 * @property int|null $sign_price_list_item_id
 * @property string $description
 * @property string $unit_code
 * @property numeric-string $quantity
 * @property numeric-string|null $width_mm
 * @property numeric-string|null $height_mm
 * @property numeric-string $unit_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SignCatalogItemComponent extends BaseModel
{

    protected $table = 'sign_catalog_item_components';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'width_mm' => 'decimal:2',
            'height_mm' => 'decimal:2',
            'unit_cost' => 'decimal:4',
        ];
    }

    /**
     * Surface rows are billed on the area of material actually consumed, typed
     * as B x H in millimetres; every other unit uses the quantity as written.
     */
    public function effectiveQuantity(): float
    {
        $quantity = (float) $this->quantity;

        if (! $this->isSurfaceRow()) {
            return $quantity;
        }

        $areaSqm = ((float) $this->width_mm / 1000) * ((float) $this->height_mm / 1000);

        return $quantity * $areaSqm;
    }

    public function lineCost(): float
    {
        return round((float) $this->unit_cost * $this->effectiveQuantity(), 2);
    }

    public function isSurfaceRow(): bool
    {
        if ($this->width_mm === null || $this->height_mm === null) {
            return false;
        }

        $surfaceUnits = config('signage.surface_unit_codes', ['mq']);
        if (! is_array($surfaceUnits)) {
            return false;
        }

        $codes = array_map(static fn (mixed $unit): string => is_scalar($unit) ? (string) $unit : '', $surfaceUnits);

        return in_array((string) $this->unit_code, $codes, true);
    }

    /** @return BelongsTo<SignCatalogItem, $this> */
    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(SignCatalogItem::class, 'sign_catalog_item_id');
    }

    /** @return BelongsTo<SignPriceListItem, $this> */
    public function supplierItem(): BelongsTo
    {
        return $this->belongsTo(SignPriceListItem::class, 'sign_price_list_item_id');
    }

    protected static function newFactory(): SignCatalogItemComponentFactory
    {
        return SignCatalogItemComponentFactory::new();
    }
}
