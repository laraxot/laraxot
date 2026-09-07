<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * One closed solid found inside a STEP file, measured on its own.
 *
 * The customer's drawings are exported flattened: a 228-piece machine base
 * arrives as a single PRODUCT holding 228 separate solids, with no assembly
 * relations and no part names. The names are therefore gone for good, but the
 * pieces are not — each solid still carries its own geometry, and that is what a
 * bill of materials is made of.
 *
 * The bounding box is read as the stock the piece is cut from, not as the
 * finished piece: a shaped plate is still cut out of the rectangle that contains
 * it, and the offcut is paid for. That is how a subcontractor quotes, so the box
 * is the right number even when the part inside it is not a box.
 */
final readonly class StepSolid
{
    /** Below this ratio of thickness to width the piece is cut from plate. */
    /** @var float */
    private const float PLATE_RATIO = 0.25;

    /** From this ratio of length to width the piece is cut from a profile. */
    /** @var float */
    private const float BAR_RATIO = 3.0;

    /**
     * A box has 6 planar faces; chamfers and steps add a few more. Past this the
     * outline is a profile, which has to be cut by torch or laser rather than
     * sawn square.
     */
    /** @var int */
    private const int SQUARE_CUT_PLANE_LIMIT = 12;

    public function __construct(
        /** Dimensions sorted largest first, in millimetres. */
        public float $lengthMm,
        public float $widthMm,
        public float $thicknessMm,
        public int $vertexCount,
        public int $faceCount,
        public int $planeCount,
        public int $cylinderCount,
    ) {}

    /**
     * How the piece was most likely obtained, which decides what it is costed
     * against: plate is cut from sheet, bar from a profile bought by the metre,
     * block from solid stock or an oxy-cut billet.
     */
    public function shapeCode(): string
    {
        if ($this->widthMm > 0.0 && $this->thicknessMm / $this->widthMm <= self::PLATE_RATIO) {
            return 'plate';
        }

        if ($this->widthMm > 0.0 && $this->lengthMm / $this->widthMm >= self::BAR_RATIO) {
            return 'bar';
        }

        return 'block';
    }

    /**
     * Whether the outline has to be followed by a torch or a laser. A piece
     * bounded by a handful of planes is a square offcut and comes off a saw,
     * which is both faster and cheaper; anything more is a profiled cut.
     */
    public function hasProfiledCut(): bool
    {
        return $this->planeCount > self::SQUARE_CUT_PLANE_LIMIT;
    }

    /**
     * Holes cannot be counted exactly: a through hole is one cylindrical surface,
     * a counterbored or split one is two. Halving is the closest honest guess.
     */
    public function estimatedHoleCount(): int
    {
        return $this->cylinderCount === 0 ? 0 : max(1, (int) round($this->cylinderCount / 2));
    }

    /** Outer surface of the piece, in square metres — the paintable area. */
    public function boundingSurfaceSqm(): float
    {
        $l = $this->lengthMm / 1000;
        $w = $this->widthMm / 1000;
        $t = $this->thicknessMm / 1000;

        return 2 * ($l * $w + $l * $t + $w * $t);
    }

    /** Perimeter of the face being cut, in metres — how far the torch travels. */
    public function cutLengthM(): float
    {
        return 2 * ($this->lengthMm + $this->widthMm) / 1000;
    }

    public function boundingVolumeMm3(): float
    {
        return $this->lengthMm * $this->widthMm * $this->thicknessMm;
    }

    /** The cross-section a profile would be bought by: the two smaller sides. */
    public function sectionMm(): string
    {
        return $this->formatDimension($this->widthMm).'x'.$this->formatDimension($this->thicknessMm);
    }

    /** The overall size as the operator reads it off the drawing. */
    public function dimensionsMm(): string
    {
        return $this->formatDimension($this->lengthMm)
            .'x'.$this->formatDimension($this->widthMm)
            .'x'.$this->formatDimension($this->thicknessMm);
    }

    /**
     * Solids of the same size are the same piece: this is what groups 228 solids
     * into 77 bill-of-materials rows. Rounded to a tenth of a millimetre so that
     * modelling noise does not split a row in two.
     */
    public function signature(): string
    {
        return $this->dimensionsMm().'|'.$this->shapeCode();
    }

    private function formatDimension(float $value): string
    {
        return rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'length_mm' => $this->lengthMm,
            'width_mm' => $this->widthMm,
            'thickness_mm' => $this->thicknessMm,
            'vertex_count' => $this->vertexCount,
            'face_count' => $this->faceCount,
            'plane_count' => $this->planeCount,
            'cylinder_count' => $this->cylinderCount,
            'shape_code' => $this->shapeCode(),
            'has_profiled_cut' => $this->hasProfiledCut(),
            'hole_count' => $this->estimatedHoleCount(),
        ];
    }
}
