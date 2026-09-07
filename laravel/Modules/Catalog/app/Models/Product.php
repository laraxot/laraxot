<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Catalog\Database\Factories\ProductFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string|null $description
 * @property float|string $unit_price
 * @property bool $is_active
 * @property int|null $catalog_model_id
 * @property int|null $catalog_part_type_id
 * @property int|null $catalog_measure_unit_id
 * @property int|null $catalog_frequency_id
 */
final class Product extends XotBaseModel
{
    use HasXotFactory;
    use SoftDeletes;

    protected $table = 'products';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<CatalogModel, $this> */
    public function model(): BelongsTo
    {
        return $this->belongsTo(CatalogModel::class, 'catalog_model_id');
    }

    /** @return BelongsTo<CatalogPartType, $this> */
    public function partType(): BelongsTo
    {
        return $this->belongsTo(CatalogPartType::class, 'catalog_part_type_id');
    }

    /** @return BelongsTo<CatalogMeasureUnit, $this> */
    public function measureUnit(): BelongsTo
    {
        return $this->belongsTo(CatalogMeasureUnit::class, 'catalog_measure_unit_id');
    }

    /** @return BelongsTo<CatalogFrequency, $this> */
    public function frequency(): BelongsTo
    {
        return $this->belongsTo(CatalogFrequency::class, 'catalog_frequency_id');
    }
}
