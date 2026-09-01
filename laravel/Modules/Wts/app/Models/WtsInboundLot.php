<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Wts\Concerns\WtsAuditable;
use Modules\Wts\Database\Factories\WtsInboundLotFactory;

/**
 * @property int $id
 * @property int $inbound_record_id
 * @property string $lot_code
 * @property string $status_code
 * @property string $current_area_code
 * @property int $initial_units_count
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsInboundLot extends BaseModel
{
    /** @use HasFactory<WtsInboundLotFactory> */
    use HasFactory;

    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_inbound_lots';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<WtsInboundRecord, $this> */
    public function inboundRecord(): BelongsTo
    {
        return $this->belongsTo(WtsInboundRecord::class, 'inbound_record_id');
    }

    /** @return HasMany<WtsUnit, $this> */
    public function units(): HasMany
    {
        return $this->hasMany(WtsUnit::class, 'inbound_lot_id');
    }

    /** @return HasMany<WtsNonConformityReport, $this> */
    public function nonConformityReports(): HasMany
    {
        return $this->hasMany(WtsNonConformityReport::class, 'wts_inbound_lot_id');
    }

    protected static function newFactory(): WtsInboundLotFactory
    {
        return WtsInboundLotFactory::new();
    }
}
