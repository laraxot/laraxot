<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\Wts\Concerns\WtsAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsDerivedMaterial extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsDerivedMaterialFactory> */
    use HasFactory;

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

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsDerivedMaterialFactory
    {
        return \Modules\Wts\Database\Factories\WtsDerivedMaterialFactory::new();
    }
}
