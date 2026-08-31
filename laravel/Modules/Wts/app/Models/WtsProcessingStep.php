<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\User\Models\User;
use Modules\Wts\Concerns\WtsAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $work_sheet_code
 * @property string $step_type_code
 * @property string $quantity_mode
 * @property int $selected_units_count
 * @property string|null $input_weight
 * @property string|null $output_weight
 * @property string|null $remainder_weight
 * @property string|null $remainder_waste_code
 * @property int|null $technical_sheet_snapshot_id
 * @property string|null $executed_by_user_id
 * @property \Carbon\CarbonInterface $executed_at
 * @property string|null $notes
 * @property array<string, mixed>|null $meta
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsProcessingStep extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsProcessingStepFactory> */
    use HasFactory;

    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_processing_steps';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'input_weight' => 'decimal:2',
            'output_weight' => 'decimal:2',
            'remainder_weight' => 'decimal:2',
            'executed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function executedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by_user_id');
    }

    /** @return BelongsTo<WtsTechnicalSheetSnapshot, $this> */
    public function technicalSheetSnapshot(): BelongsTo
    {
        return $this->belongsTo(WtsTechnicalSheetSnapshot::class, 'technical_sheet_snapshot_id');
    }

    /** @return BelongsToMany<WtsUnit, $this> */
    public function selectedUnits(): BelongsToMany
    {
        return $this->belongsToMany(WtsUnit::class, 'wts_processing_step_unit', 'wts_processing_step_id', 'wts_unit_id');
    }

    /** @return HasMany<WtsDerivedMaterial, $this> */
    public function derivedMaterials(): HasMany
    {
        return $this->hasMany(WtsDerivedMaterial::class, 'processing_step_id');
    }

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsProcessingStepFactory
    {
        return \Modules\Wts\Database\Factories\WtsProcessingStepFactory::new();
    }
}
