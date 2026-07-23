<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Product extends Model
{
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
