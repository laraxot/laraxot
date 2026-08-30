<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Facades\DB;
use Modules\MetalWork\Datas\StepSolid;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalDrawingComponent;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Support\StepSolidExtractor;

/**
 * Turns a drawing into a bill of materials.
 *
 * Every solid in the file is measured on its own and identical ones are collapsed
 * into a single row with a quantity, which is what makes a 228-solid machine base
 * readable as 77 lines. The rows are ordered by how much steel they carry, so the
 * operator checks the pieces that decide the price first.
 *
 * Re-running on the same drawing replaces the bill of materials — except for the
 * materials the operator has confirmed, which are carried over: re-importing a
 * drawing must not throw away the work of matching it.
 */
final class BuildDrawingBillOfMaterialsAction
{
    public function __construct(
        private readonly StepSolidExtractor $extractor = new StepSolidExtractor(),
        private readonly MatchRawMaterialToSolidAction $matcher = new MatchRawMaterialToSolidAction(),
    ) {}

    /**
     * @return int the number of bill-of-materials rows written
     */
    public function execute(MetalDrawing $drawing, string $path): int
    {
        $solids = $this->extractor->extract($path);

        if ($solids === []) {
            return 0;
        }

        $rows = $this->group($solids);
        $materials = MetalRawMaterial::query()->where('is_active', true)->get();

        return DB::transaction(function () use ($drawing, $rows, $materials): int {
            $confirmed = $this->confirmedMaterials($drawing);

            $drawing->components()->delete();

            $position = 0;

            foreach ($rows as $signature => $row) {
                $solid = $row['solid'];
                $material = $confirmed[$signature] ?? $this->matcher->execute($solid, $materials);

                MetalDrawingComponent::query()->create([
                    'drawing_id' => $drawing->id,
                    'position' => $position++,
                    'quantity' => $row['quantity'],
                    'length_mm' => $solid->lengthMm,
                    'width_mm' => $solid->widthMm,
                    'thickness_mm' => $solid->thicknessMm,
                    'shape_code' => $solid->shapeCode(),
                    'plane_count' => $solid->planeCount,
                    'cylinder_count' => $solid->cylinderCount,
                    'hole_count' => $solid->estimatedHoleCount(),
                    'has_profiled_cut' => $solid->hasProfiledCut(),
                    'raw_material_id' => $material?->id,
                    'is_material_confirmed' => array_key_exists($signature, $confirmed),
                ]);
            }

            return $position;
        });
    }

    /**
     * Materials the operator already confirmed, keyed by the piece they belong to,
     * so a re-import keeps them.
     *
     * @return array<string, MetalRawMaterial|null>
     */
    private function confirmedMaterials(MetalDrawing $drawing): array
    {
        $confirmed = [];

        foreach ($drawing->components()->where('is_material_confirmed', true)->with('rawMaterial')->get() as $component) {
            $confirmed[$component->solid()->signature()] = $component->rawMaterial;
        }

        return $confirmed;
    }

    /**
     * Collapses identical solids into one row each, heaviest first.
     *
     * @param  list<StepSolid>  $solids
     * @return array<string, array{solid: StepSolid, quantity: int}>
     */
    private function group(array $solids): array
    {
        $rows = [];

        foreach ($solids as $solid) {
            $signature = $solid->signature();
            $seen = $rows[$signature] ?? null;

            $rows[$signature] = [
                'solid' => $solid,
                'quantity' => ($seen['quantity'] ?? 0) + 1,
            ];
        }

        uasort($rows, static function (array $a, array $b): int {
            return $b['solid']->boundingVolumeMm3() * $b['quantity']
                <=> $a['solid']->boundingVolumeMm3() * $a['quantity'];
        });

        return $rows;
    }
}
