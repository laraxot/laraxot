<?php

declare(strict_types=1);

namespace Modules\MetalWork\Support;

use Modules\MetalWork\Datas\StepGeometry;
use RuntimeException;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\fread;
use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\rewind;

/**
 * Reads an ISO-10303-21 (STEP) file as text.
 *
 * There is no STEP kernel in PHP and pulling one in for a quotation screen is
 * not worth it, so this reads what the format states literally: the header, the
 * PRODUCT name, the corners of the solid, and how many entities of each kind the
 * body is made of. Anything that would need real geometry — exact volume, hole
 * diameters, unfolded length — is left to the operator.
 *
 * The bounding box comes from VERTEX_POINT only. Taking every CARTESIAN_POINT
 * instead makes it meaningless: an unbounded LINE stores an origin that can sit
 * hundreds of metres away, which on the customer's own drawings turned a 10-metre
 * machine base into a 188-metre one. Vertices are the actual corners of the
 * B-rep, so the box they span is the part.
 *
 * The file is read in two streaming passes — vertex references first, then the
 * points themselves — because a vertex may reference a point defined later on.
 * Chunked rather than slurped: the customer's assemblies run to several megabytes
 * and 19.000 points, and holding one as a single string to regex over costs more
 * memory than the whole request.
 */
final class StepFileParser
{
    private const CHUNK_BYTES = 262144;

    /** A header longer than this is not a STEP header. */
    private const MAX_HEADER_BYTES = 65536;

    /** Entity keywords counted while streaming, mapped to the metric they feed. */
    private const COUNTED_ENTITIES = [
        'ADVANCED_FACE' => 'face',
        'PLANE' => 'plane',
        'CYLINDRICAL_SURFACE' => 'cylinder',
        'MANIFOLD_SOLID_BREP' => 'solid',
        'NEXT_ASSEMBLY_USAGE_OCCURRENCE' => 'assembly',
    ];

    public function parse(string $path): StepGeometry
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException(sprintf('STEP file not readable: %s', $path));
        }

        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException(sprintf('STEP file not readable: %s', $path));
        }

        try {
            [$header, $remainder] = $this->readHeader($handle);

            if (! str_contains($header, 'ISO-10303-21')) {
                throw new RuntimeException(sprintf('Not a STEP file: %s', basename($path)));
            }

            $vertexIds = $this->scanVertexReferences($handle, $remainder);

            rewind($handle);
            [, $remainder] = $this->readHeader($handle);

            $body = $this->scanBody($handle, $remainder, $vertexIds);
        } finally {
            fclose($handle);
        }

        $identity = $this->readIdentity($header, $body['products'], basename($path));
        $scale = $this->unitScaleToMm($body['unit']);
        $dimensions = $this->sortedDimensions($body['min'], $body['max'], $scale);

        return new StepGeometry(
            partCode: $identity['code'],
            name: $identity['name'],
            sourceFile: basename($path),
            cadSystem: $identity['cad'],
            lengthUnitCode: 'mm',
            lengthMm: $dimensions[0],
            widthMm: $dimensions[1],
            thicknessMm: $dimensions[2],
            pointCount: $body['points'],
            faceCount: $body['face'],
            planeCount: $body['plane'],
            cylinderCount: $body['cylinder'],
            solidCount: $body['solid'],
            isAssembly: $body['assembly'] > 0,
        );
    }

    /**
     * Reads up to the end of the HEADER section. Whatever was read past it is
     * handed back so the body scan starts where the header stopped instead of
     * losing the rest of the chunk.
     *
     * @param  resource  $handle
     * @return array{string, string}
     */
    private function readHeader($handle): array
    {
        $buffer = '';

        while (! feof($handle)) {
            $chunk = fread($handle, self::CHUNK_BYTES);

            if ($chunk === '') {
                break;
            }

            $buffer .= $chunk;
            $end = strpos($buffer, 'ENDSEC;');

            if ($end !== false) {
                return [substr($buffer, 0, $end), substr($buffer, $end + 7)];
            }

            if (strlen($buffer) > self::MAX_HEADER_BYTES) {
                break;
            }
        }

        return [$buffer, ''];
    }

    /**
     * Streams the DATA section, accumulating the bounding box and the entity
     * counts. Chunks are cut at the last complete statement, so an entity split
     * across a read boundary is still matched exactly once.
     *
     * @param  resource  $handle
     * @param  array<int, true>  $vertexIds  point ids that are corners of the solid
     * @return array{
     *     min: array{float, float, float}|null,
     *     max: array{float, float, float}|null,
     *     points: int, face: int, plane: int, cylinder: int, solid: int,
     *     assembly: int, products: list<string>, unit: string|null
     * }
     */
    private function scanBody($handle, string $remainder, array $vertexIds): array
    {
        $min = null;
        $max = null;
        $points = 0;
        $counts = ['face' => 0, 'plane' => 0, 'cylinder' => 0, 'solid' => 0, 'assembly' => 0];
        $products = [];
        $unit = null;
        $carry = $remainder;

        while (true) {
            if (! feof($handle)) {
                $chunk = fread($handle, self::CHUNK_BYTES);
                $carry .= $chunk;
            }

            // Statements end with ';'. Everything after the last one is carried
            // over, so no entity is ever cut in half.
            $lastStatement = strrpos($carry, ';');

            if ($lastStatement !== false) {
                $this->scanSegment(substr($carry, 0, $lastStatement + 1), $vertexIds, $min, $max, $points, $counts, $products, $unit);
                $carry = substr($carry, $lastStatement + 1);
            }

            if (feof($handle)) {
                break;
            }
        }

        if ($carry !== '') {
            $this->scanSegment($carry, $vertexIds, $min, $max, $points, $counts, $products, $unit);
        }

        return [
            'min' => $min,
            'max' => $max,
            'points' => $points,
            'face' => $counts['face'],
            'plane' => $counts['plane'],
            'cylinder' => $counts['cylinder'],
            'solid' => $counts['solid'],
            'assembly' => $counts['assembly'],
            'products' => $products,
            'unit' => $unit,
        ];
    }

    /**
     * @param  array<int, true>  $vertexIds
     * @param  array{float, float, float}|null  $min
     * @param  array{float, float, float}|null  $max
     * @param  array<string, int>  $counts
     * @param  list<string>  $products
     */
    private function scanSegment(
        string $segment,
        array $vertexIds,
        ?array &$min,
        ?array &$max,
        int &$points,
        array &$counts,
        array &$products,
        ?string &$unit,
    ): void {
        // Newlines inside an entity are legal and NX emits plenty of them.
        $flat = str_replace(["\r", "\n"], '', $segment);

        if (preg_match_all("/(?<![A-Z_])PRODUCT\(\s*'([^']*)'/", $flat, $productMatches) > 0) {
            foreach ($productMatches[1] as $name) {
                $products[] = $name;
            }
        }

        if ($unit === null) {
            $unit = $this->matchLengthUnit($flat);
        }

        foreach (self::COUNTED_ENTITIES as $keyword => $metric) {
            $counts[$metric] += preg_match_all('/(?<![A-Z_])'.$keyword.'\(/', $flat);
        }

        // Only three-coordinate points can bound anything; the two-coordinate ones
        // live in the parameter space of trimmed curves and mean nothing in
        // millimetres.
        $matched = preg_match_all(
            '/#([0-9]+)\s*=\s*CARTESIAN_POINT\(\s*\'[^\']*\'\s*,\s*\(\s*([-0-9.E+]+)\s*,\s*([-0-9.E+]+)\s*,\s*([-0-9.E+]+)\s*\)/i',
            $flat,
            $matches,
            PREG_SET_ORDER,
        );

        if ($matched === 0) {
            return;
        }

        $points += $matched;

        foreach ($matches as $point) {
            if (! isset($vertexIds[(int) $point[1]])) {
                continue;
            }

            $coordinates = [(float) $point[2], (float) $point[3], (float) $point[4]];

            if ($min === null || $max === null) {
                $min = $coordinates;
                $max = $coordinates;

                continue;
            }

            for ($axis = 0; $axis < 3; $axis++) {
                $min[$axis] = min($min[$axis], $coordinates[$axis]);
                $max[$axis] = max($max[$axis], $coordinates[$axis]);
            }
        }
    }

    /**
     * First pass: the ids of the points the solid's vertices point at.
     *
     * @param  resource  $handle
     * @return array<int, true>
     */
    private function scanVertexReferences($handle, string $remainder): array
    {
        $ids = [];
        $carry = $remainder;

        while (true) {
            if (! feof($handle)) {
                $chunk = fread($handle, self::CHUNK_BYTES);
                $carry .= $chunk;
            }

            $lastStatement = strrpos($carry, ';');

            if ($lastStatement !== false) {
                $this->collectVertexIds(substr($carry, 0, $lastStatement + 1), $ids);
                $carry = substr($carry, $lastStatement + 1);
            }

            if (feof($handle)) {
                break;
            }
        }

        if ($carry !== '') {
            $this->collectVertexIds($carry, $ids);
        }

        return $ids;
    }

    /**
     * @param  array<int, true>  $ids
     */
    private function collectVertexIds(string $segment, array &$ids): void
    {
        $flat = str_replace(["\r", "\n"], '', $segment);

        if (preg_match_all("/VERTEX_POINT\(\s*'[^']*'\s*,\s*#([0-9]+)\s*\)/i", $flat, $matches) === 0) {
            return;
        }

        foreach ($matches[1] as $id) {
            $ids[(int) $id] = true;
        }
    }

    /**
     * The part code and name as the customer writes them: NX exports
     * `353300964_3-CARRO DUPLEX T4R P.245 LAVORATO`, where the leading token is
     * the drawing number and the rest the description.
     *
     * An assembly names every part it contains, the sub-parts first, so the one
     * matching the file name is picked: the file is named after the part it
     * delivers. Falls back to the first PRODUCT, then to the file name, which
     * carries the same drawing number.
     *
     * @param  list<string>  $products
     * @return array{code: string, name: string, cad: string|null}
     */
    private function readIdentity(string $header, array $products, string $fileName): array
    {
        $cad = preg_match("/originating_system[^']*'([^']+)'/i", $header, $match) === 1
            ? trim(($match[1] ?? ''))
            : null;

        $stem = pathinfo($fileName, PATHINFO_FILENAME);
        $expected = $this->normalizeCode((string) preg_replace('/[_-]stp[0-9]*$/i', '', $stem));

        $source = $stem;

        foreach ($products as $index => $product) {
            $identity = $this->splitProductName($product);

            if ($identity !== null && $identity['code'] === $expected) {
                $source = $product;
                break;
            }

            if ($index === 0) {
                $source = $product;
            }
        }

        $identity = $this->splitProductName($source);

        if ($identity !== null) {
            return ['code' => $identity['code'], 'name' => $identity['name'], 'cad' => $cad];
        }

        // No description to separate: use the drawing number as both, dropping the
        // exporter's `_stp1` suffix so the code matches the paper drawing.
        return ['code' => $expected, 'name' => $expected, 'cad' => $cad];
    }

    /**
     * @return array{code: string, name: string}|null
     */
    private function splitProductName(string $product): ?array
    {
        return preg_match('/^([0-9]{5,}[_-][0-9A-Za-z]+)\s*-\s*(\S.*)$/u', trim($product), $match) === 1
            ? ['code' => $this->normalizeCode(($match[1] ?? '')), 'name' => trim(($match[2] ?? ''))]
            : null;
    }

    /** NX writes the revision separator as an underscore, the drawing as a dash. */
    private function normalizeCode(string $code): string
    {
        return str_replace('_', '-', trim($code));
    }

    /**
     * The length unit as the file declares it, e.g. `MILLI` for
     * `(LENGTH_UNIT()NAMED_UNIT(*)SI_UNIT(.MILLI.,.METRE.))`, or `INCH` for an
     * imperial export. Null when the file does not say.
     */
    private function matchLengthUnit(string $flat): ?string
    {
        if (preg_match("/CONVERSION_BASED_UNIT\(\s*'(INCH|FOOT)'/i", $flat, $match) === 1) {
            return mb_strtoupper(($match[1] ?? ''));
        }

        if (preg_match('/LENGTH_UNIT\(\)[^;]{0,200}?SI_UNIT\(\s*(\$|\.[A-Z]+\.)\s*,\s*\.METRE\./i', $flat, $match) === 1) {
            return ($match[1] ?? '') === '$' ? 'METRE' : mb_strtoupper(trim(($match[1] ?? ''), '.'));
        }

        return null;
    }

    /**
     * STEP states its own length unit. NX writes millimetres, but a metre- or
     * inch-based export would otherwise be quoted orders of magnitude too light.
     * An undeclared unit is treated as millimetres, which is what every CAD system
     * the customer receives files from writes.
     */
    private function unitScaleToMm(?string $unit): float
    {
        return match ($unit) {
            'METRE' => 1000.0,
            'DECI' => 100.0,
            'CENTI' => 10.0,
            'INCH' => 25.4,
            'FOOT' => 304.8,
            default => 1.0,
        };
    }

    /**
     * @param  array{float, float, float}|null  $min
     * @param  array{float, float, float}|null  $max
     * @return array{float|null, float|null, float|null}
     */
    private function sortedDimensions(?array $min, ?array $max, float $scale): array
    {
        if ($min === null || $max === null) {
            return [null, null, null];
        }

        $dimensions = [
            round(($max[0] - $min[0]) * $scale, 2),
            round(($max[1] - $min[1]) * $scale, 2),
            round(($max[2] - $min[2]) * $scale, 2),
        ];

        rsort($dimensions);

        return [$dimensions[0], $dimensions[1], $dimensions[2]];
    }
}
