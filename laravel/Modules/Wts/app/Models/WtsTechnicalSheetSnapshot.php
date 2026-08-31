<?php

declare(strict_types=1);

namespace Modules\Wts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $legacy_system
 * @property string $sheet_code
 * @property string $producer_name
 * @property string $waste_code
 * @property string|null $hp_codes
 * @property string|null $packaging_description
 * @property string|null $production_description
 * @property string|null $note_1
 * @property string|null $note_2
 * @property array<string, mixed>|null $legacy_payload
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 */
final class WtsTechnicalSheetSnapshot extends Model
{
    /** @use HasFactory<\Modules\Wts\Database\Factories\WtsTechnicalSheetSnapshotFactory> */
    use HasFactory;

    protected $table = 'wts_technical_sheet_snapshots';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'legacy_payload' => 'array',
        ];
    }

    /** @return HasMany<WtsInboundRecord, $this> */
    public function inboundRecords(): HasMany
    {
        return $this->hasMany(WtsInboundRecord::class, 'technical_sheet_snapshot_id');
    }

    /** @return HasMany<WtsUnit, $this> */
    public function units(): HasMany
    {
        return $this->hasMany(WtsUnit::class, 'technical_sheet_snapshot_id');
    }

    protected static function newFactory(): \Modules\Wts\Database\Factories\WtsTechnicalSheetSnapshotFactory
    {
        return \Modules\Wts\Database\Factories\WtsTechnicalSheetSnapshotFactory::new();
    }
}
