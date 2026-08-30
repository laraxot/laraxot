<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * What can honestly be read out of a STEP file without a geometry kernel: the
 * identity of the part, its bounding box, and how many features of each kind it
 * is made of.
 *
 * The exact volume is deliberately absent. A STEP solid is a boundary
 * representation and computing its volume needs a tessellator; guessing one and
 * presenting it as fact would quote jobs wrong. The estimate derives the weight
 * from this bounding box and an occupancy factor the operator sees and corrects.
 */
final readonly class StepGeometry
{
    public function __construct(
        public string $partCode,
        public string $name,
        public string $sourceFile,
        public ?string $cadSystem,
        public string $lengthUnitCode,
        /** Bounding box, largest dimension first. Null when the file had no 3D points. */
        public ?float $lengthMm,
        public ?float $widthMm,
        public ?float $thicknessMm,
        public int $pointCount,
        public int $faceCount,
        public int $planeCount,
        public int $cylinderCount,
        public int $solidCount,
        public bool $isAssembly,
    ) {}

    public function hasBoundingBox(): bool
    {
        return $this->lengthMm !== null && $this->widthMm !== null && $this->thicknessMm !== null;
    }

    /** Bounding-box volume in cubic millimetres. */
    public function boundingVolumeMm3(): ?float
    {
        return $this->hasBoundingBox()
            ? (float) $this->lengthMm * (float) $this->widthMm * (float) $this->thicknessMm
            : null;
    }

    /** The face the part lies on: the two largest dimensions, in square metres. */
    public function footprintSqm(): ?float
    {
        return $this->hasBoundingBox()
            ? (float) $this->lengthMm / 1000 * ((float) $this->widthMm / 1000)
            : null;
    }

    /** Outer surface of the bounding box, in square metres — the paintable area. */
    public function boundingSurfaceSqm(): ?float
    {
        if (! $this->hasBoundingBox()) {
            return null;
        }

        $l = (float) $this->lengthMm / 1000;
        $w = (float) $this->widthMm / 1000;
        $t = (float) $this->thicknessMm / 1000;

        return 2 * ($l * $w + $l * $t + $w * $t);
    }

    /** Perimeter of the footprint in metres — how far the torch has to travel. */
    public function cutLengthM(): ?float
    {
        return $this->hasBoundingBox()
            ? 2 * ((float) $this->lengthMm + (float) $this->widthMm) / 1000
            : null;
    }

    /**
     * A part thinner than the threshold is sheet metal: it is cut from a blank and
     * bent, not milled from a block, and its weight can be computed exactly from
     * footprint x thickness.
     */
    public function isFlat(?float $thresholdMm = null): bool
    {
        if ($this->thicknessMm === null) {
            return false;
        }

        $threshold = $thresholdMm ?? self::configuredFlatThreshold();

        return $this->thicknessMm <= $threshold;
    }

    /**
     * Holes cannot be counted exactly either: a through hole is one cylindrical
     * surface, a counterbored or split one is two. Halving the cylinder count is
     * the closest honest guess, floored at one hole when any cylinder is present.
     */
    public function estimatedHoleCount(): int
    {
        if ($this->cylinderCount === 0) {
            return 0;
        }

        return max(1, (int) round($this->cylinderCount / 2));
    }

    /**
     * A flat part's bends: a blank has 2 large faces plus 4 edges = 6 planes, so
     * every pair of planes beyond that is a fold. Non-flat parts do not get bent.
     */
    public function estimatedBendCount(): int
    {
        if (! $this->isFlat() || $this->planeCount <= 6) {
            return 0;
        }

        return (int) max(0, round(($this->planeCount - 6) / 2));
    }

    private static function configuredFlatThreshold(): float
    {
        $value = config('metalwork.flat_part_max_thickness_mm', 30.0);

        return is_numeric($value) ? (float) $value : 30.0;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'part_code' => $this->partCode,
            'name' => $this->name,
            'source_file' => $this->sourceFile,
            'cad_system' => $this->cadSystem,
            'length_unit_code' => $this->lengthUnitCode,
            'bbox_length_mm' => $this->lengthMm,
            'bbox_width_mm' => $this->widthMm,
            'bbox_thickness_mm' => $this->thicknessMm,
            'point_count' => $this->pointCount,
            'face_count' => $this->faceCount,
            'plane_count' => $this->planeCount,
            'cylinder_count' => $this->cylinderCount,
            'solid_count' => $this->solidCount,
            'is_assembly' => $this->isAssembly,
        ];
    }
}
