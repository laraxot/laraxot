<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\MetalWork\Database\Factories\MetalPartEstimateFactory;
use Modules\Quotation\Models\Quotation;

/**
 * @property int $id
 * @property int|null $drawing_id
 * @property int|null $raw_material_id
 * @property int|null $quotation_id
 * @property string $part_code
 * @property string $name
 * @property int $quantity
 * @property numeric-string|null $thickness_mm
 * @property numeric-string|null $occupancy_percent
 * @property numeric-string|null $scrap_percent
 * @property numeric-string $net_weight_kg
 * @property numeric-string $gross_weight_kg
 * @property numeric-string $total_cost
 * @property numeric-string $sale_price
 * @property numeric-string|null $manual_sale_price
 * @property numeric-string|null $markup_percent
 * @property string|null $notes
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalPartEstimate extends BaseModel
{

    protected $table = 'metalwork_part_estimates';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'thickness_mm' => 'decimal:2',
            'occupancy_percent' => 'decimal:2',
            'scrap_percent' => 'decimal:2',
            'net_weight_kg' => 'decimal:3',
            'gross_weight_kg' => 'decimal:3',
            'total_cost' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'manual_sale_price' => 'decimal:2',
            'markup_percent' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<MetalDrawing, $this> */
    public function drawing(): BelongsTo
    {
        return $this->belongsTo(MetalDrawing::class, 'drawing_id');
    }

    /** @return BelongsTo<MetalRawMaterial, $this> */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(MetalRawMaterial::class, 'raw_material_id');
    }

    /** @return BelongsTo<Quotation, $this> */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /** @return HasMany<MetalEstimateLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(MetalEstimateLine::class, 'part_estimate_id')->orderBy('position');
    }

    protected static function newFactory(): MetalPartEstimateFactory
    {
        return MetalPartEstimateFactory::new();
    }
}
