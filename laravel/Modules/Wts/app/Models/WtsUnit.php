<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Wts\Concerns\WtsAuditable;
use Modules\Wts\Database\Factories\WtsUnitFactory;

/**
 * @property int $id
 * @property int $inbound_lot_id
 * @property int|null $technical_sheet_snapshot_id
 * @property string $unit_code
 * @property string $qr_code
 * @property string $unit_type_code
 * @property string $status_code
 * @property string $current_area_code
 * @property string|null $location_label
 * @property string|null $gross_weight
 * @property bool $is_banned
 * @property int $generated_sequence
 * @property array<string, mixed>|null $meta
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsUnit extends BaseModel
{
    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_units';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'gross_weight' => 'decimal:2',
            'is_banned' => 'bool',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<WtsInboundLot, $this> */
    public function inboundLot(): BelongsTo
    {
        return $this->belongsTo(WtsInboundLot::class, 'inbound_lot_id');
    }

    /** @return BelongsTo<WtsTechnicalSheetSnapshot, $this> */
    public function technicalSheetSnapshot(): BelongsTo
    {
        return $this->belongsTo(WtsTechnicalSheetSnapshot::class, 'technical_sheet_snapshot_id');
    }

    /** @return BelongsToMany<WtsProcessingStep, $this> */
    public function processingSteps(): BelongsToMany
    {
        return $this->belongsToMany(WtsProcessingStep::class, 'wts_processing_step_unit', 'wts_unit_id', 'wts_processing_step_id');
    }

    /** @return MorphMany<WtsLabelPrint, $this> */
    public function labelPrints(): MorphMany
    {
        return $this->morphMany(WtsLabelPrint::class, 'labelable');
    }

    protected static function newFactory(): WtsUnitFactory
    {
        return WtsUnitFactory::new();
    }
}
