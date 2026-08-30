<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\MetalWork\Datas\StepSolid;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $drawing_id
 * @property int $position
 * @property int $quantity
 * @property numeric-string $length_mm
 * @property numeric-string $width_mm
 * @property numeric-string $thickness_mm
 * @property string $shape_code
 * @property int $plane_count
 * @property int $cylinder_count
 * @property int $hole_count
 * @property bool $has_profiled_cut
 * @property int|null $raw_material_id
 * @property bool $is_material_confirmed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalDrawingComponent extends XotBaseModel
{
    protected $table = 'metalwork_drawing_components';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'quantity' => 'integer',
            'length_mm' => 'decimal:2',
            'width_mm' => 'decimal:2',
            'thickness_mm' => 'decimal:2',
            'plane_count' => 'integer',
            'cylinder_count' => 'integer',
            'hole_count' => 'integer',
            'has_profiled_cut' => 'boolean',
            'is_material_confirmed' => 'boolean',
        ];
    }

    /** @return BelongsTo<MetalDrawing, $this> */
    public function drawing(): BelongsTo
    {
        return $this->belongsTo(MetalDrawing::class, 'drawing_id');
    }

    /** @return BelongsTo<MetalRawMaterial, $this> */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(MetalRawMaterial::class, 'raw_material_id');
    }

    /** The stored row read back as the geometry it was measured from. */
    public function solid(): StepSolid
    {
        return new StepSolid(
            lengthMm: (float) $this->length_mm,
            widthMm: (float) $this->width_mm,
            thicknessMm: (float) $this->thickness_mm,
            vertexCount: 0,
            faceCount: (int) $this->plane_count + (int) $this->cylinder_count,
            planeCount: (int) $this->plane_count,
            cylinderCount: (int) $this->cylinder_count,
        );
    }

    /** Volume of the stock this row consumes, in cubic millimetres. */
    public function stockVolumeMm3(): float
    {
        return (float) $this->length_mm * (float) $this->width_mm * (float) $this->thickness_mm * $this->quantity;
    }

    public function label(): string
    {
        $format = static fn (float $v): string => rtrim(rtrim(number_format($v, 1, ',', '.'), '0'), ',');

        return sprintf(
            '%s × %s × %s mm',
            $format((float) $this->length_mm),
            $format((float) $this->width_mm),
            $format((float) $this->thickness_mm),
        );
    }
}
