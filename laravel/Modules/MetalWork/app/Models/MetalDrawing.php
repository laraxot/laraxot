<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\MetalWork\Database\Factories\MetalDrawingFactory;
use Modules\MetalWork\Datas\StepGeometry;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $part_code
 * @property string|null $revision
 * @property string $name
 * @property string $source_file
 * @property string|null $storage_path
 * @property string|null $content_hash
 * @property string|null $cad_system
 * @property string $length_unit_code
 * @property numeric-string|null $bbox_length_mm
 * @property numeric-string|null $bbox_width_mm
 * @property numeric-string|null $bbox_thickness_mm
 * @property int $point_count
 * @property int $face_count
 * @property int $plane_count
 * @property int $cylinder_count
 * @property int $solid_count
 * @property bool $is_assembly
 * @property int|null $imported_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalDrawing extends XotBaseModel
{
    /** @use HasFactory<MetalDrawingFactory> */
    use HasFactory;

    protected $table = 'metalwork_drawings';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'bbox_length_mm' => 'decimal:2',
            'bbox_width_mm' => 'decimal:2',
            'bbox_thickness_mm' => 'decimal:2',
            'point_count' => 'integer',
            'face_count' => 'integer',
            'plane_count' => 'integer',
            'cylinder_count' => 'integer',
            'solid_count' => 'integer',
            'is_assembly' => 'boolean',
        ];
    }

    /** @return HasMany<MetalDrawingComponent, $this> */
    public function components(): HasMany
    {
        return $this->hasMany(MetalDrawingComponent::class, 'drawing_id')->orderBy('position');
    }

    /** @return HasMany<MetalPartEstimate, $this> */
    public function estimates(): HasMany
    {
        return $this->hasMany(MetalPartEstimate::class, 'drawing_id');
    }

    /**
     * The stored metrics read back as the geometry DTO, so the estimate works
     * against the same shape whether the file was just parsed or is being
     * re-opened from the archive.
     */
    public function geometry(): StepGeometry
    {
        return new StepGeometry(
            partCode: (string) $this->part_code,
            name: (string) $this->name,
            sourceFile: (string) $this->source_file,
            cadSystem: $this->cad_system,
            lengthUnitCode: (string) $this->length_unit_code,
            lengthMm: $this->bbox_length_mm === null ? null : (float) $this->bbox_length_mm,
            widthMm: $this->bbox_width_mm === null ? null : (float) $this->bbox_width_mm,
            thicknessMm: $this->bbox_thickness_mm === null ? null : (float) $this->bbox_thickness_mm,
            pointCount: (int) $this->point_count,
            faceCount: (int) $this->face_count,
            planeCount: (int) $this->plane_count,
            cylinderCount: (int) $this->cylinder_count,
            solidCount: (int) $this->solid_count,
            isAssembly: (bool) $this->is_assembly,
        );
    }

    protected static function newFactory(): MetalDrawingFactory
    {
        return MetalDrawingFactory::new();
    }
}
