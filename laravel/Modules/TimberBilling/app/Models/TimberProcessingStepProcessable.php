<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\TimberBilling\Database\Factories\TimberProcessingStepProcessableFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * Pivot polimorfica: TimberProcessingStep ↔ TimberLot|TimberDerivedMaterial.
 *
 * @property int $processing_step_id
 * @property string $processable_type
 * @property int $processable_id
 */
final class TimberProcessingStepProcessable extends XotBaseModel
{
    /** @use HasFactory<TimberProcessingStepProcessableFactory> */
    use HasFactory;

    protected $table = 'timber_processing_step_processable';

    /** @var array<int, string> */
    protected $guarded = [];

    public $timestamps = false;

    /** @return BelongsTo<TimberProcessingStep, $this> */
    public function processingStep(): BelongsTo
    {
        return $this->belongsTo(TimberProcessingStep::class, 'processing_step_id');
    }

    /** @return MorphTo<\Illuminate\Database\Eloquent\Model, $this> */
    public function processable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function newFactory(): TimberProcessingStepProcessableFactory
    {
        return TimberProcessingStepProcessableFactory::new();
    }
}
