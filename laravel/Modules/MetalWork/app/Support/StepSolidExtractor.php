<?php

declare(strict_types=1);

namespace Modules\MetalWork\Support;

use Modules\MetalWork\Datas\StepSolid;
use RuntimeException;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\fread;
use function Safe\preg_match;
use function Safe\preg_match_all;

/**
 * Measures every solid of a STEP file separately, which is what turns a drawing
 * into a bill of materials.
 *
 * The customer's exports are flattened — one PRODUCT holding N solids, no
 * assembly relations, no component names — so the pieces have to be recovered
 * from the topology: every MANIFOLD_SOLID_BREP is walked down its own shell, to
 * its faces, loops, edges and finally its vertices, and the box those vertices
 * span is that piece.
 *
 * Unlike {@see StepFileParser}, which streams, this holds a reference graph in
 * memory. Only the entities on that path are kept — the curves, directions and
 * placements that make up most of the file are skipped — which on the customer's
 * largest assembly is some 55.000 rows instead of 200.000.
 */
final class StepSolidExtractor
{
    /** @var int */
    private const int CHUNK_BYTES = 262144;

    /**
     * Entities on the path from a solid down to its vertices. Everything else in
     * the file is irrelevant here and is dropped as it is read.
     */
    /** @var list<string> */
    private const array TOPOLOGY_ENTITIES = [
        'MANIFOLD_SOLID_BREP',
        'BREP_WITH_VOIDS',
        'CLOSED_SHELL',
        'OPEN_SHELL',
        'ADVANCED_FACE',
        'FACE_SURFACE',
        'FACE_OUTER_BOUND',
        'FACE_BOUND',
        'EDGE_LOOP',
        'ORIENTED_EDGE',
        'EDGE_CURVE',
        'VERTEX_LOOP',
    ];

    /** Surface types worth telling apart when describing a piece. */
    /** @var array<string, string> */
    private const array SURFACE_ENTITIES = [
        'PLANE' => 'plane',
        'CYLINDRICAL_SURFACE' => 'cylinder',
    ];

    /**
     * @return list<StepSolid> one entry per solid, largest first
     */
    public function extract(string $path, float $scaleToMm = 1.0): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException(sprintf('STEP file not readable: %s', $path));
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException(sprintf('STEP file not readable: %s', $path));
        }

        try {
            $graph = $this->readGraph($handle);
        } finally {
            fclose($handle);
        }

        $solids = [];

        foreach ($graph['solids'] as $solidId) {
            $solid = $this->measure($solidId, $graph, $scaleToMm);

            if ($solid !== null) {
                $solids[] = $solid;
            }
        }

        // Largest first: the operator checks the pieces that carry the weight.
        usort($solids, static fn (StepSolid $a, StepSolid $b): int => $b->boundingVolumeMm3() <=> $a->boundingVolumeMm3());

        return $solids;
    }

    /**
     * @param  resource  $handle
     * @return array{
     *     refs: array<int, list<int>>,
     *     points: array<int, array{float, float, float}>,
     *     vertices: array<int, int>,
     *     surfaces: array<int, string>,
     *     solids: list<int>
     * }
     */
    private function readGraph($handle): array
    {
        $refs = [];
        $points = [];
        $vertices = [];
        $surfaces = [];
        $solids = [];
        $carry = '';

        while (true) {
            if (! feof($handle)) {
                $chunk = fread($handle, self::CHUNK_BYTES);
                $carry .= $chunk;
            }

            $lastStatement = strrpos($carry, ';');

            if ($lastStatement !== false) {
                $this->readStatements(
                    substr($carry, 0, $lastStatement + 1),
                    $refs,
                    $points,
                    $vertices,
                    $surfaces,
                    $solids,
                );
                $carry = substr($carry, $lastStatement + 1);
            }

            if (feof($handle)) {
                break;
            }
        }

        return [
            'refs' => $refs,
            'points' => $points,
            'vertices' => $vertices,
            'surfaces' => $surfaces,
            'solids' => $solids,
        ];
    }

    /**
     * @param  array<int, list<int>>  $refs
     * @param  array<int, array{float, float, float}>  $points
     * @param  array<int, int>  $vertices
     * @param  array<int, string>  $surfaces
     * @param  list<int>  $solids
     */
    private function readStatements(
        string $segment,
        array &$refs,
        array &$points,
        array &$vertices,
        array &$surfaces,
        array &$solids,
    ): void {
        // Newlines inside an entity are legal and NX emits plenty of them.
        $flat = str_replace(["\r", "\n"], '', $segment);

        $matched = preg_match_all('/#(\d+)\s*=\s*([A-Z_0-9]*)\s*\(([^;]*)\);/', $flat, $matches, PREG_SET_ORDER);

        if ($matched === 0) {
            return;
        }

        foreach ($matches as $entity) {
            $id = (int) $entity[1];
            $keyword = $entity[2];
            $body = $entity[3];

            if ($keyword === 'CARTESIAN_POINT') {
                $this->readPoint($id, $body, $points);

                continue;
            }

            if ($keyword === 'VERTEX_POINT') {
                if (preg_match('/#(\d+)/', $body, $reference) === 1) {
                    $vertices[$id] = (int) ($reference[1] ?? '');
                }

                continue;
            }

            if (array_key_exists($keyword, self::SURFACE_ENTITIES)) {
                $surfaces[$id] = self::SURFACE_ENTITIES[$keyword];

                continue;
            }

            if (! in_array($keyword, self::TOPOLOGY_ENTITIES, true)) {
                continue;
            }

            if ($keyword === 'MANIFOLD_SOLID_BREP' || $keyword === 'BREP_WITH_VOIDS') {
                $solids[] = $id;
            }

            $children = [];

            if (preg_match_all('/#(\d+)/', $body, $references) > 0) {
                foreach ($references[1] as $childId) {
                    $children[] = (int) $childId;
                }
            }

            $refs[$id] = $children;
        }
    }

    /**
     * @param  array<int, array{float, float, float}>  $points
     */
    private function readPoint(int $id, string $body, array &$points): void
    {
        // Two-coordinate points live in the parameter space of trimmed curves and
        // mean nothing in millimetres.
        $matched = preg_match(
            '/\(\s*([-0-9.E+]+)\s*,\s*([-0-9.E+]+)\s*,\s*([-0-9.E+]+)\s*\)/i',
            $body,
            $coordinates,
        );

        if ($matched === 1) {
            $points[$id] = [(float) $coordinates[1], (float) $coordinates[2], (float) $coordinates[3]];
        }
    }

    /**
     * Walks one solid down to its vertices and boxes them.
     *
     * @param  array{
     *     refs: array<int, list<int>>,
     *     points: array<int, array{float, float, float}>,
     *     vertices: array<int, int>,
     *     surfaces: array<int, string>,
     *     solids: list<int>
     * }  $graph
     */
    private function measure(int $solidId, array $graph, float $scaleToMm): ?StepSolid
    {
        $min = null;
        $max = null;
        $vertexCount = 0;
        $planeCount = 0;
        $cylinderCount = 0;

        $stack = [$solidId];
        $seen = [];

        while ($stack !== []) {
            $node = array_pop($stack);

            if (isset($seen[$node])) {
                continue;
            }

            $seen[$node] = true;

            if (isset($graph['vertices'][$node])) {
                $vertexCount++;
                $point = $graph['points'][$graph['vertices'][$node]] ?? null;

                if ($point !== null) {
                    $min = $min === null ? $point : $this->corner($min, $point, true);
                    $max = $max === null ? $point : $this->corner($max, $point, false);
                }

                continue;
            }

            if (isset($graph['surfaces'][$node])) {
                $planeCount += $graph['surfaces'][$node] === 'plane' ? 1 : 0;
                $cylinderCount += $graph['surfaces'][$node] === 'cylinder' ? 1 : 0;

                continue;
            }

            $children = $graph['refs'][$node] ?? null;

            if ($children === null) {
                continue;
            }

            foreach ($children as $child) {
                $stack[] = $child;
            }
        }

        if ($min === null || $max === null) {
            return null;
        }

        $dimensions = [
            round(($max[0] - $min[0]) * $scaleToMm, 2),
            round(($max[1] - $min[1]) * $scaleToMm, 2),
            round(($max[2] - $min[2]) * $scaleToMm, 2),
        ];
        rsort($dimensions);

        return new StepSolid(
            lengthMm: $dimensions[0],
            widthMm: $dimensions[1],
            thicknessMm: $dimensions[2],
            vertexCount: $vertexCount,
            faceCount: $planeCount + $cylinderCount,
            planeCount: $planeCount,
            cylinderCount: $cylinderCount,
        );
    }

    /**
     * One corner of the box, moved to take in a new point.
     *
     * @param  array{float, float, float}  $corner
     * @param  array{float, float, float}  $point
     * @return array{float, float, float}
     */
    private function corner(array $corner, array $point, bool $lower): array
    {
        return [
            $lower ? min($corner[0], $point[0]) : max($corner[0], $point[0]),
            $lower ? min($corner[1], $point[1]) : max($corner[1], $point[1]),
            $lower ? min($corner[2], $point[2]) : max($corner[2], $point[2]),
        ];
    }
}
