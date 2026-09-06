<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Wts\Concerns\WtsAuditable;
use Modules\Wts\Database\Factories\WtsDerivedMaterialFactory;

/**
 * @property int $id
 * @property int $processing_step_id
 * @property int|null $technical_sheet_snapshot_id
 * @property string $output_code
 * @property string $output_type_code
 * @property string $container_type_code
 * @property int $quantity
 * @property string|null $weight
 * @property bool $is_dirty
 * @property array<string, mixed>|null $meta
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsDerivedMaterial extends BaseModel
{
    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_derived_materials';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'is_dirty' => 'bool',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<WtsProcessingStep, $this> */
    public function processingStep(): BelongsTo
    {
        return $this->belongsTo(WtsProcessingStep::class, 'processing_step_id');
    }

    /** @return BelongsTo<WtsTechnicalSheetSnapshot, $this> */
    public function technicalSheetSnapshot(): BelongsTo
    {
        return $this->belongsTo(WtsTechnicalSheetSnapshot::class, 'technical_sheet_snapshot_id');
    }

    /** @return MorphMany<WtsLabelPrint, $this> */
    public function labelPrints(): MorphMany
    {
        return $this->morphMany(WtsLabelPrint::class, 'labelable');
    }

    protected static function newFactory(): WtsDerivedMaterialFactory
    {
        return WtsDerivedMaterialFactory::new();
    }
}
