<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberDerivedMaterialFactory;
use Modules\TimberBilling\Support\WoodSpeciesCatalog;

/**
 * @property int $processing_step_id
 * @property string $output_code
 * @property string $wood_species
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $status_code
 * @property string $current_area_code
 * @property bool $is_waste
 * @property array<string, mixed>|null $meta
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $root_lot_id
 * @property string|null $traceable_code
 * @property string|null $stage_type_code
 * @property array<int, mixed>|null $stage_history
 */
final class TimberDerivedMaterial extends BaseModel
{
    /** @use HasFactory<TimberDerivedMaterialFactory> */
    use HasFactory;

    protected $table = 'timber_derived_materials';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'is_waste' => 'bool',
            'meta' => 'array',
            'stage_history' => 'array',
        ];
    }

    /** @return Attribute<string, never> */
    protected function woodSpeciesName(): Attribute
    {
        return Attribute::get(fn (): string => WoodSpeciesCatalog::name((string) $this->wood_species));
    }

    /** @return BelongsTo<TimberProcessingStep, $this> */
    public function processingStep(): BelongsTo
    {
        return $this->belongsTo(TimberProcessingStep::class, 'processing_step_id');
    }

    /** @return BelongsTo<TimberLot, $this> */
    public function rootLot(): BelongsTo
    {
        return $this->belongsTo(TimberLot::class, 'root_lot_id');
    }

    /** @return MorphToMany<TimberProcessingStep, $this> */
    public function consumingProcessingSteps(): MorphToMany
    {
        return $this->morphToMany(
            TimberProcessingStep::class,
            'processable',
            'timber_processing_step_processable',
            'processable_id',
            'processing_step_id',
        );
    }

    /** @return HasMany<TimberPackagedUnit, $this> */
    public function packagedUnits(): HasMany
    {
        return $this->hasMany(TimberPackagedUnit::class, 'derived_material_id');
    }

    /** @return MorphMany<TimberMovement, $this> */
    public function movements(): MorphMany
    {
        return $this->morphMany(TimberMovement::class, 'movable');
    }

    /** @return MorphMany<TimberDryingCycle, $this> */
    public function dryingCycles(): MorphMany
    {
        return $this->morphMany(TimberDryingCycle::class, 'dryable');
    }

    /** @return MorphMany<TimberLabelPrint, $this> */
    public function labelPrints(): MorphMany
    {
        return $this->morphMany(TimberLabelPrint::class, 'labelable');
    }

    protected static function newFactory(): TimberDerivedMaterialFactory
    {
        return TimberDerivedMaterialFactory::new();
    }
}
