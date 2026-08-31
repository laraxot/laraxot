<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberLotFactory;
use Modules\Timber\Support\WoodSpeciesCatalog;

/**
 * @property int $purchase_record_id
 * @property string $lot_code
 * @property string $status_code
 * @property string $current_area_code
 * @property string $quantity_available
 * @property string $unit_of_measure
 * @property string $wood_species
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberLot extends BaseModel
{

    protected $table = 'timber_lots';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity_available' => 'decimal:2',
        ];
    }

    /** @return Attribute<string, never> */
    protected function woodSpeciesName(): Attribute
    {
        return Attribute::get(fn (): string => WoodSpeciesCatalog::name((string) $this->wood_species));
    }

    /** @return BelongsTo<TimberPurchaseRecord, $this> */
    public function purchaseRecord(): BelongsTo
    {
        return $this->belongsTo(TimberPurchaseRecord::class, 'purchase_record_id');
    }

    /** @return MorphToMany<TimberProcessingStep, $this> */
    public function processingSteps(): MorphToMany
    {
        return $this->morphToMany(
            TimberProcessingStep::class,
            'processable',
            'timber_processing_step_processable',
            'processable_id',
            'processing_step_id',
        );
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

    protected static function newFactory(): TimberLotFactory
    {
        return TimberLotFactory::new();
    }
}
