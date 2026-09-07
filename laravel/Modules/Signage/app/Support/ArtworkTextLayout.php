<?php

declare(strict_types=1);

namespace Modules\Signage\Support;

/**
 * Rebuilds a positioned "cell" model out of the raw text-with-matrix data that
 * smalot/pdfparser exposes for a page. Illustrator drawings have no logical
 * reading order, so the only reliable way to pair a title-block label with its
 * value (or a callout header with its description) is by X/Y position.
 *
 * A cell is a run of text fragments that share the same baseline (Y) and are
 * close together horizontally (same column). Fragments whose horizontal gap is
 * wide are split into separate cells so two side-by-side columns never merge.
 */
final class ArtworkTextLayout
{
    /** @var float Max horizontal gap (pt) between fragments before they become separate cells. */
    private const float COLUMN_GAP = 55.0;

    /** @var float Max vertical distance (pt) for two fragments to share a baseline. */
    private const float LINE_TOLERANCE = 2.0;

    /**
     * @param  list<array{x: float, y: float, text: string}>  $cells
     */
    private function __construct(public readonly array $cells) {}

    /**
     * Il dataTm arriva da pdfparser, che non promette una forma: ogni voce viene
     * verificata invece di essere data per buona, perche' un .ai malformato non
     * deve far esplodere l'estrazione ma solo produrre meno testo.
     *
     * @param  array<int, mixed>  $dataTm
     */
    public static function fromDataTm(array $dataTm): self
    {
        $fragments = [];

        foreach ($dataTm as $item) {
            if (! is_array($item) || ! isset($item[0], $item[1]) || ! is_array($item[0])) {
                continue;
            }

            $matrix = $item[0];
            $text = trim((string) (is_scalar($item[1]) ? $item[1] : ''));

            if ($text === '' || ! isset($matrix[4], $matrix[5])) {
                continue;
            }

            $fragments[] = [
                'x' => (float) (is_numeric($matrix[4]) ? $matrix[4] : 0),
                'y' => (float) (is_numeric($matrix[5]) ? $matrix[5] : 0),
                'text' => $text,
            ];
        }

        // Group fragments into baselines (same Y within tolerance).
        usort($fragments, static fn (array $a, array $b): int => $b['y'] <=> $a['y'] ?: $a['x'] <=> $b['x']);

        /** @var list<list<array{x: float, y: float, text: string}>> $lines */
        $lines = [];
        foreach ($fragments as $fragment) {
            $placed = false;
            foreach ($lines as $index => $line) {
                if (abs($line[0]['y'] - $fragment['y']) <= self::LINE_TOLERANCE) {
                    $lines[$index][] = $fragment;
                    $placed = true;
                    break;
                }
            }
            if (! $placed) {
                $lines[] = [$fragment];
            }
        }

        // Within each baseline, split into cells on wide horizontal gaps.
        $cells = [];
        foreach ($lines as $line) {
            usort($line, static fn (array $a, array $b): int => $a['x'] <=> $b['x']);

            $current = null;
            $lastX = null;
            foreach ($line as $fragment) {
                if ($current !== null && $lastX !== null && ($fragment['x'] - $lastX) > self::COLUMN_GAP) {
                    $cells[] = $current;
                    $current = null;
                }

                if ($current === null) {
                    $current = ['x' => $fragment['x'], 'y' => $fragment['y'], 'text' => $fragment['text']];
                } elseif (! str_ends_with($current['text'], $fragment['text'])) {
                    // Skip overlapping duplicate glyph runs (Illustrator prints some
                    // title-block labels twice at the same position).
                    $current['text'] = trim($current['text'].' '.$fragment['text']);
                }

                $lastX = $fragment['x'];
            }

            if ($current !== null) {
                $cells[] = $current;
            }
        }

        usort($cells, static fn (array $a, array $b): int => $b['y'] <=> $a['y'] ?: $a['x'] <=> $b['x']);

        return new self($cells);
    }
}
