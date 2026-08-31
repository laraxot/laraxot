<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Modules\User\Models\User;
use Modules\Wts\Concerns\WtsAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string|null $legacy_reference
 * @property string|null $form_number
 * @property string $source_company_name
 * @property string|null $vehicle_reference
 * @property string $gross_quantity
 * @property string|null $entry_weight
 * @property string $status_code
 * @property int|null $technical_sheet_snapshot_id
 * @property \Carbon\CarbonInterface|null $received_at
 * @property string|null $created_by_user_id
 * @property string|null $notes
 * @property string|null $tare_weight
 * @property string|null $net_weight
 * @property string|null $acceptance_status
 * @property \Carbon\CarbonInterface|null $taken_charge_at
 * @property string|null $commercial_company_name
 * @property string|null $batch_number
 * @property int|null $ecos_units_count
 * @property bool|null $is_weight_closed
 * @property string|null $treatment_line_code
 * @property string|null $production_cycle_code
 * @property array<string, mixed>|null $legacy_payload
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Carbon\CarbonInterface|null $deleted_at
 * @property string|null $deleted_by
 */
final class WtsInboundRecord extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsInboundRecordFactory> */
    use HasFactory;

    use SoftDeletes;
    use WtsAuditable;

    protected $table = 'wts_inbound_records';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'gross_quantity' => 'decimal:2',
            'tare_weight' => 'decimal:2',
            'net_weight' => 'decimal:2',
            'entry_weight' => 'decimal:2',
            'received_at' => 'datetime',
            'taken_charge_at' => 'datetime',
            'legacy_payload' => 'array',
        ];
    }

    /** @return BelongsTo<WtsTechnicalSheetSnapshot, $this> */
    public function technicalSheetSnapshot(): BelongsTo
    {
        return $this->belongsTo(WtsTechnicalSheetSnapshot::class, 'technical_sheet_snapshot_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return HasMany<WtsInboundLot, $this> */
    public function lots(): HasMany
    {
        return $this->hasMany(WtsInboundLot::class, 'inbound_record_id');
    }

    /** @return HasMany<WtsNonConformityReport, $this> */
    public function nonConformityReports(): HasMany
    {
        return $this->hasMany(WtsNonConformityReport::class, 'wts_inbound_record_id');
    }

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsInboundRecordFactory
    {
        return \Modules\Wts\Database\Factories\WtsInboundRecordFactory::new();
    }
}
