<?php

declare(strict_types=1);

namespace Modules\MetalWork\Support;

use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;

/**
 * Reads the size of a stock item out of the supplier's own article code.
 *
 * The codes are descriptive and, unlike the descriptions on the invoice, they
 * survive the PDF intact — the printed text breaks numbers apart ("FERRO PIATTO
 * 150X3 0"), the code does not:
 *
 *   FP150X30        flat bar 150 x 30
 *   TQ60XX3         square tube 60 x 60, wall 3
 *   TR150X100X6     rectangular tube 150 x 100, wall 6
 *   FL100X75X8      angle 100 x 75 x 8
 *   FTLC40 / TF250  round bar, diameter
 *   FQLC15 / Q100   square bar
 *   HEA200FE510     HE-A 200 beam
 *   LN1,5H1000      black sheet 1,5 mm, coil width 1000
 *
 * Only the numbers are read, deliberately: guessing what each one means per
 * family would break on the next code the supplier invents, whereas comparing
 * the set of numbers against a solid's own dimensions degrades gracefully.
 */
final class RawMaterialDimensionReader
{
    /**
     * The section of the stock item, largest first, in millimetres. The trailing
     * H-number of a sheet code is the coil width, not a section, and is dropped.
     *
     * @return list<float>
     */
    public function read(string $code, string $familyCode): array
    {
        $normalized = mb_strtoupper(str_replace(',', '.', $code));

        // Sheet is bought by thickness; the H-suffix is the width of the coil.
        if ($familyCode === 'sheet') {
            $thickness = $this->sheetThickness($normalized);

            return $thickness === null ? [] : [$thickness];
        }

        // Steel grades ride along in the code (S275JR, FE510) and are not sizes.
        $stripped = (string) preg_replace('/(FE\d+|S\d{3}[A-Z0-9]*)/', ' ', $normalized);

        $matched = preg_match_all('/\d+(?:\.\d+)?/', $stripped, $matches);

        if ($matched === 0) {
            return [];
        }

        $numbers = array_map(static fn (string $n): float => (float) $n, $matches[0]);

        // A doubled X marks a square section written once: TQ60XX3 is 60 x 60 x 3.
        if (preg_match('/^[A-Z]+(\d+(?:\.\d+)?)XX/', $stripped, $square) === 1) {
            array_splice($numbers, 1, 0, [(float) ($square[1] ?? '')]);
        }

        rsort($numbers);

        return array_values(array_filter($numbers, static fn (float $n): bool => $n > 0.0));
    }

    /**
     * Sheet thickness as the trade writes it: 15/10 of a millimetre in the
     * description, 1,5 in the code. Both mean 1,5 mm.
     */
    private function sheetThickness(string $code): ?float
    {
        // Everything before the H-suffix, minus the family letters, is the gauge.
        if (preg_match('/^[A-Z]+(\d+(?:\.\d+)?)(?:H\d+)?$/', $code, $match) === 1) {
            return (float) ($match[1] ?? '');
        }

        return null;
    }

    /**
     * How well a stock section fits the one needed, as a relative error, or null
     * when the item cannot serve the section at all.
     *
     * Stock may be larger than needed — a 150 mm bar can be cut down to 120 — but
     * never smaller, so an item that does not contain the section is rejected
     * rather than scored badly.
     *
     * @param  list<float>  $stock  section of the stock item, largest first
     * @param  list<float>  $needed  section of the piece, largest first
     */
    public function fitScore(array $stock, array $needed): ?float
    {
        if ($stock === [] || $needed === []) {
            return null;
        }

        $error = 0.0;
        $compared = 0;

        foreach ($needed as $index => $dimension) {
            $candidate = $stock[$index] ?? null;

            if ($candidate === null) {
                break;
            }

            // Tolerate a millimetre of modelling noise before calling it undersized.
            if ($candidate + 0.5 < $dimension) {
                return null;
            }

            $error += ($candidate - $dimension) / max($dimension, 0.1);
            $compared++;
        }

        return $compared === 0 ? null : $error / $compared;
    }
}
