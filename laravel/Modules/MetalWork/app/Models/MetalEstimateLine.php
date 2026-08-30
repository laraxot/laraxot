<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $part_estimate_id
 * @property int $position
 * @property string $work_center_code
 * @property string $description
 * @property string $unit_code
 * @property numeric-string $quantity
 * @property numeric-string $unit_cost
 * @property numeric-string $line_cost
 * @property numeric-string $markup_percent
 * @property numeric-string $line_price
 * @property string $markup_source_code
 * @property string|null $driver_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalEstimateLine extends XotBaseModel
{
    protected $table = 'metalwork_estimate_lines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'quantity' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'line_cost' => 'decimal:2',
            'markup_percent' => 'decimal:2',
            'line_price' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<MetalPartEstimate, $this> */
    public function partEstimate(): BelongsTo
    {
        return $this->belongsTo(MetalPartEstimate::class, 'part_estimate_id');
    }
}
