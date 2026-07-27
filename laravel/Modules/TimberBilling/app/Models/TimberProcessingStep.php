<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberProcessingStepFactory;
use Modules\User\Models\User;

/**
 * @property string $work_sheet_code
 * @property string $step_type_code
 * @property string|null $input_quantity
 * @property string|null $output_quantity
 * @property string|null $waste_quantity
 * @property string|null $recoverable_quantity
 * @property string $unit_of_measure
 * @property int|null $executed_by_user_id
 * @property Carbon $executed_at
 * @property string|null $notes
 * @property array<string, mixed>|null $meta
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberProcessingStep extends BaseModel
{
    /** @use HasFactory<TimberProcessingStepFactory> */
    use HasFactory;

    protected $table = 'timber_processing_steps';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'input_quantity' => 'decimal:2',
            'output_quantity' => 'decimal:2',
            'waste_quantity' => 'decimal:2',
            'recoverable_quantity' => 'decimal:2',
            'executed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function executedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by_user_id');
    }

    /** @return HasMany<TimberDerivedMaterial, $this> */
    public function derivedMaterials(): HasMany
    {
        return $this->hasMany(TimberDerivedMaterial::class, 'processing_step_id');
    }

    /** @return MorphToMany<TimberLot, $this> */
    public function inputLots(): MorphToMany
    {
        return $this->morphedByMany(
            TimberLot::class,
            'processable',
            'timber_processing_step_processable',
            'processing_step_id',
            'processable_id',
        );
    }

    /** @return MorphToMany<TimberDerivedMaterial, $this> */
    public function inputDerivedMaterials(): MorphToMany
    {
        return $this->morphedByMany(
            TimberDerivedMaterial::class,
            'processable',
            'timber_processing_step_processable',
            'processing_step_id',
            'processable_id',
        );
    }

    protected static function newFactory(): TimberProcessingStepFactory
    {
        return TimberProcessingStepFactory::new();
    }
}
