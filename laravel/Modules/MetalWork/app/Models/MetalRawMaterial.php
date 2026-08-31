<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\MetalWork\Database\Factories\MetalRawMaterialFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $code
 * @property string $description
 * @property string|null $supplier_name
 * @property string $family_code
 * @property string $unit_code
 * @property string|null $steel_grade
 * @property numeric-string|null $thickness_mm
 * @property numeric-string|null $width_mm
 * @property numeric-string|null $height_mm
 * @property numeric-string|null $length_mm
 * @property numeric-string|null $density_kg_dm3
 * @property numeric-string|null $weight_per_meter_kg
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalRawMaterial extends XotBaseModel
{

    protected $table = 'metalwork_raw_materials';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'thickness_mm' => 'decimal:2',
            'width_mm' => 'decimal:2',
            'height_mm' => 'decimal:2',
            'length_mm' => 'decimal:2',
            'density_kg_dm3' => 'decimal:3',
            'weight_per_meter_kg' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<MetalSupplierPrice, $this> */
    public function supplierPrices(): HasMany
    {
        return $this->hasMany(MetalSupplierPrice::class, 'raw_material_id');
    }

    /**
     * What the material costs today: the most recently invoiced price wins, so
     * re-importing an invoice is enough to update the estimate. Falls back to
     * null rather than to zero — a material never bought must not silently quote
     * as free.
     */
    public function currentUnitPrice(): ?float
    {
        $latest = $this->supplierPrices()
            ->orderByDesc('recorded_on')
            ->orderByDesc('id')
            ->first();

        return $latest === null ? null : (float) $latest->unit_price;
    }

    public function density(): float
    {
        if ($this->density_kg_dm3 !== null) {
            return (float) $this->density_kg_dm3;
        }

        $default = config('metalwork.default_density_kg_dm3', 7.85);

        return is_numeric($default) ? (float) $default : 7.85;
    }

    protected static function newFactory(): MetalRawMaterialFactory
    {
        return MetalRawMaterialFactory::new();
    }
}
