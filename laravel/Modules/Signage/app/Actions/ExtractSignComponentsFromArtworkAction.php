<?php

declare(strict_types=1);

namespace Modules\Signage\Actions;

use InvalidArgumentException;
use Modules\Signage\Datas\ArtworkExtraction;
use Modules\Signage\Datas\SignComponent;
use Modules\Signage\Support\ArtworkTextLayout;
use Modules\Signage\Support\SignTypeCodeDecoder;
use Smalot\PdfParser\Parser;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_match_all;

/**
 * Reads a Signage sign drawing (Illustrator .ai, which is a PDF, or a plain
 * .pdf export) and returns the full list of its components/properties: the
 * title-block fields, the decoded sign code, every material callout and the
 * technical layers present. This is the first step towards a quotation.
 */
final class ExtractSignComponentsFromArtworkAction
{
    /** @var array<string, string> Title-block labels (right column) mapped to their extraction field key. */
    private const array TITLE_BLOCK_LABELS = [
        'CUSTOMER' => 'customer',
        'REFERENT' => 'referent',
        'PROJECT' => 'project',
        'BRAND' => 'brand',
        'SHOP' => 'shop',
        'TYPE OF SIGN' => 'sign_type_code',
        'Q.TY' => 'quantity',
        'PAINTING' => 'painting',
        'FINISHING' => 'finishing',
        'LIGHTING' => 'lighting',
        'DRAWING' => 'drawing',
    ];

    /** @var list<string> Labels that are not stored as fields but still act as boundaries so a value
     * never bleeds into the row below (e.g. the PM/REV/SCALE/SIZE bottom strip). */
    private const array BOUNDARY_LABELS = ['PM', 'REV', 'TAV', 'SCALE', 'SIZE', 'NOTE'];

    /** @var list<array{role: string, keyword: string}> Component classification rules, evaluated in order (first match wins). */
    private const array COMPONENT_RULES = [
        ['role' => 'body', 'keyword' => '/ACRYLBLOCK|PLEXBLOCK|BLOCK|LASTRA|BODY|RETRO|BACK OF THE LETTERS/i'],
        ['role' => 'face', 'keyword' => '/FRONT SIDE|FRONT LIGHT|LETTERS FRONT|FACE|OPAL PLEX|FRONTE/i'],
        ['role' => 'led', 'keyword' => '/LED|STRIPLED|STRIP LED|MODUL/i'],
        ['role' => 'cable', 'keyword' => '/CABLE|CAVO|ELECTRICAL/i'],
        ['role' => 'fixing', 'keyword' => '/FIXING|PINS|BUSHING|PRISONIER|THREADED|\bM[0-9]\b/i'],
        ['role' => 'paint', 'keyword' => '/PAINTED|VERNICIAT/i'],
    ];

    /** @var array<string, string> Layer-name tokens (lowercased substring) mapped to a friendly description. */
    private const array LAYER_TOKENS = [
        'led' => 'LED lighting',
        'cable' => 'Cabling / cable holes',
        'fixing' => 'Fixing holes',
        'fori' => 'Holes',
        'dimension' => 'Dimensions',
        'testi' => 'Text / logo',
        'vettoriale' => 'Vector cut path',
    ];

    public function __construct(private readonly Parser $parser = new Parser()) {}

    public function execute(string $filePath): ArtworkExtraction
    {
        if (! is_file($filePath)) {
            throw new InvalidArgumentException("Il file del disegno non esiste: {$filePath}");
        }

        $pdf = $this->parser->parseFile($filePath);
        $pages = $pdf->getPages();

        if ($pages === []) {
            throw new InvalidArgumentException('Il disegno non contiene pagine leggibili.');
        }

        /** @var array<int, mixed> $dataTm */
        $dataTm = $pages[0]->getDataTm();

        $layout = ArtworkTextLayout::fromDataTm($dataTm);
        $cells = $layout->cells;

        $details = $pdf->getDetails();
        $drawingTitle = isset($details['Title']) && is_string($details['Title']) ? $details['Title'] : null;
        $creator = isset($details['Creator']) && is_string($details['Creator']) ? $details['Creator'] : null;

        $titleBlock = $this->extractTitleBlock($cells);
        $decodedCode = SignTypeCodeDecoder::decode($titleBlock['sign_type_code']);

        $components = $this->extractComponents($cells);
        $layers = $this->extractLayers($filePath);
        $components = $this->supplementComponentsFromLayers($components, $layers);
        $components = $this->supplementComponentsFromTitleBlock($components, $titleBlock);

        return new ArtworkExtraction(
            sourceFile: basename($filePath),
            drawingTitle: $drawingTitle,
            creator: $creator,
            titleBlock: $titleBlock,
            decodedCode: $decodedCode,
            components: $components,
            layers: $layers,
            notes: $this->extractNotes($cells),
        );
    }

    /**
     * @param  list<array{x: float, y: float, text: string}>  $cells
     * @return array{
     *     customer: ?string, referent: ?string, project: ?string, brand: ?string,
     *     shop: ?string, sign_type_code: ?string, quantity: ?int, painting: ?string,
     *     finishing: ?string, lighting: ?string, scale: ?string, size: ?string,
     *     revision: ?string, drawing: ?string
     * }
     */
    private function extractTitleBlock(array $cells): array
    {
        // Title block lives in the right column of the sheet.
        $blockCells = array_values(array_filter($cells, static fn (array $c): bool => $c['x'] >= 650.0));

        $labelCells = [];
        foreach ($blockCells as $cell) {
            $normalized = strtoupper($cell['text']);
            if (isset(self::TITLE_BLOCK_LABELS[$normalized])) {
                $labelCells[] = ['field' => self::TITLE_BLOCK_LABELS[$normalized], 'x' => $cell['x'], 'y' => $cell['y']];

                continue;
            }
            foreach (self::BOUNDARY_LABELS as $boundary) {
                if (str_starts_with($normalized, $boundary)) {
                    $labelCells[] = ['field' => null, 'x' => $cell['x'], 'y' => $cell['y']];
                    break;
                }
            }
        }

        $fields = [];
        foreach ($labelCells as $label) {
            if ($label['field'] === null) {
                continue;
            }
            $value = $this->collectValueBelow($blockCells, $labelCells, $label['x'], $label['y']);
            if ($value !== null && ($fields[$label['field']] ?? null) === null) {
                $fields[$label['field']] = $value;
            }
        }

        $allText = implode("\n", array_map(static fn (array $c): string => $c['text'], $cells));

        $signCode = $fields['sign_type_code'] ?? null;
        if (is_string($signCode)) {
            // Codes never contain spaces; the layout may have split kerned glyphs.
            $signCode = str_replace(' ', '', $signCode);
        }

        $quantity = null;
        if (isset($fields['quantity']) && preg_match('/\d+/', $fields['quantity'], $m) === 1) {
            $quantity = (int) ($m[0] ?? 0);
        }

        return [
            'customer' => $fields['customer'] ?? null,
            'referent' => $fields['referent'] ?? null,
            'project' => $fields['project'] ?? null,
            'brand' => $fields['brand'] ?? null,
            'shop' => $fields['shop'] ?? null,
            'sign_type_code' => $signCode,
            'quantity' => $quantity,
            'painting' => $fields['painting'] ?? null,
            'finishing' => $fields['finishing'] ?? null,
            'lighting' => $fields['lighting'] ?? $this->firstMatch('/\b\d{3,4}K\b/', $allText),
            'scale' => $this->firstMatch('/\b1:\d+\b/', $allText),
            'size' => $this->firstMatch('/\bA[0-9]\b/', $allText),
            'revision' => $this->firstMatch('/\bRS\s?\d[\w\/]*/', $allText),
            'drawing' => $fields['drawing'] ?? null,
        ];
    }

    /**
     * Collects the value cell(s) sitting directly below a label, within the same
     * column band and above the next label, joined top-to-bottom.
     *
     * @param  list<array{x: float, y: float, text: string}>  $blockCells
     * @param  list<array{field: ?string, x: float, y: float}>  $labelCells
     */
    private function collectValueBelow(array $blockCells, array $labelCells, float $labelX, float $labelY): ?string
    {
        $labelSet = [];
        foreach ($labelCells as $label) {
            $labelSet[] = [$label['x'], $label['y']];
        }

        // The value ends where the next label in the same column band begins.
        $lowerBound = $labelY - 48.0;
        foreach ($labelCells as $label) {
            if (abs($label['x'] - $labelX) <= 45.0 && $label['y'] < $labelY && $label['y'] > $lowerBound) {
                $lowerBound = $label['y'];
            }
        }

        $values = [];
        foreach ($blockCells as $cell) {
            $isLabel = false;
            foreach ($labelSet as [$lx, $ly]) {
                if (abs($cell['x'] - $lx) < 0.5 && abs($cell['y'] - $ly) < 0.5) {
                    $isLabel = true;
                    break;
                }
            }
            if ($isLabel) {
                continue;
            }

            if (abs($cell['x'] - $labelX) <= 45.0 && $cell['y'] < $labelY && $cell['y'] > $lowerBound) {
                $values[] = $cell;
            }
        }

        if ($values === []) {
            return null;
        }

        usort($values, static fn (array $a, array $b): int => $b['y'] <=> $a['y'] ?: $a['x'] <=> $b['x']);
        $text = trim(implode(' ', array_map(static fn (array $c): string => $c['text'], $values)));

        return $text === '' || $text === '-' ? null : $text;
    }

    /**
     * @param  list<array{x: float, y: float, text: string}>  $cells
     * @return list<SignComponent>
     */
    private function extractComponents(array $cells): array
    {
        // Callouts live in the drawing area (left/centre columns).
        $area = array_values(array_filter(
            $cells,
            static fn (array $c): bool => $c['x'] > 150.0 && $c['x'] < 600.0,
        ));

        // Group vertically-adjacent cells in the same column band into one block.
        $blocks = [];
        $current = null;
        foreach ($area as $cell) {
            if ($current !== null
                && ($current['y'] - $cell['y']) <= 12.0
                && abs($cell['x'] - $current['x']) <= 45.0
            ) {
                $current['lines'][] = $cell['text'];
                $current['y'] = $cell['y'];

                continue;
            }

            if ($current !== null) {
                $blocks[] = $current;
            }
            $current = ['x' => $cell['x'], 'y' => $cell['y'], 'lines' => [$cell['text']]];
        }
        if ($current !== null) {
            $blocks[] = $current;
        }

        $components = [];
        $seenRoles = [];
        foreach ($blocks as $block) {
            $label = $block['lines'][0];
            $text = trim(implode(' ', $block['lines']));

            foreach (self::COMPONENT_RULES as $rule) {
                if (preg_match($rule['keyword'], $text) !== 1) {
                    continue;
                }

                $role = $rule['role'];
                if (isset($seenRoles[$role])) {
                    break; // one component per role from callouts
                }
                $seenRoles[$role] = true;

                $components[] = new SignComponent(
                    role: $role,
                    label: $label,
                    description: $text,
                    material: $this->extractMaterial($text),
                    colorRal: $this->firstMatch('/\bRAL\s*(\d{4})\b/i', $text, 1),
                    thicknessMm: $this->extractThickness($text),
                    lighting: $this->firstMatch('/\b\d{3,4}K\b/', $text),
                    source: 'callout',
                );
                break;
            }
        }

        return $components;
    }

    private function extractMaterial(string $text): ?string
    {
        // Prefer the specific LED product name over the bare "LED" keyword.
        $material = $this->firstMatch('/\bSTRIPLED\b/i', $text)
            ?? $this->firstMatch('/\b(ACRYLBLOCK|PLEXBLOCK|OPAL PLEX|PLEX|OTTONE|ALLUMINIO|ALUMINI\w*|STEEL|ACCIAIO|PVC|DIBOND|FOREX|LED)\b/i', $text);

        return $material === null ? null : strtoupper($material);
    }

    private function extractThickness(string $text): ?float
    {
        // Prefer an explicit thickness marker (th / spessore / sp) over any mm value.
        $value = $this->firstMatch('/\b(?:th\.?|spessore|sp\.?)\s*(\d+(?:[.,]\d+)?)\s*mm/i', $text, 1);

        if ($value === null) {
            return null;
        }

        return (float) str_replace(',', '.', $value);
    }

    /**
     * @param  list<SignComponent>  $components
     * @param  list<string>  $layers
     * @return list<SignComponent>
     */
    private function supplementComponentsFromLayers(array $components, array $layers): array
    {
        $present = [];
        foreach ($components as $component) {
            $present[$component->role] = true;
        }

        $layerRoles = [
            'Holes' => 'holes',
            'Fixing holes' => 'holes',
            'Vector cut path' => 'cutting',
            'Text / logo' => 'text',
            'Dimensions' => 'dimension',
        ];

        foreach ($layers as $layer) {
            $role = $layerRoles[$layer] ?? null;
            if ($role === null || isset($present[$role])) {
                continue;
            }
            $present[$role] = true;
            $components[] = new SignComponent(
                role: $role,
                label: $layer,
                description: $layer,
                source: 'layer',
            );
        }

        return $components;
    }

    /**
     * @param  list<SignComponent>  $components
     * @param  array<string, mixed>  $titleBlock
     * @return list<SignComponent>
     */
    private function supplementComponentsFromTitleBlock(array $components, array $titleBlock): array
    {
        $hasPaint = false;
        foreach ($components as $component) {
            if ($component->role === 'paint') {
                $hasPaint = true;
                break;
            }
        }

        $painting = is_string($titleBlock['painting'] ?? null) ? $titleBlock['painting'] : null;
        if (! $hasPaint && $painting !== null) {
            $components[] = new SignComponent(
                role: 'paint',
                label: 'PAINTING',
                description: trim($painting.' '.(is_string($titleBlock['finishing'] ?? null) ? $titleBlock['finishing'] : '')),
                colorRal: $this->firstMatch('/\b(\d{4})\b/', $painting, 1),
                lighting: null,
                source: 'title_block',
            );
        }

        return $components;
    }

    /**
     * @return list<string>
     */
    private function extractLayers(string $filePath): array
    {
        $raw = (string) file_get_contents($filePath);
        if (preg_match_all('~/Name\((.*?)\)~', $raw, $matches) === 0) {
            return [];
        }

        $friendly = [];
        foreach ($matches[1] as $name) {
            $lower = strtolower($name);
            foreach (self::LAYER_TOKENS as $token => $description) {
                if (str_contains($lower, $token)) {
                    $friendly[$description] = true;
                    break;
                }
            }
        }

        return array_keys($friendly);
    }

    /**
     * @param  list<array{x: float, y: float, text: string}>  $cells
     * @return list<string>
     */
    private function extractNotes(array $cells): array
    {
        $notes = [];
        foreach ($cells as $cell) {
            if (preg_match('/INSPECTIONABLE|OFFSET|FONT ENLARGED|NON ISPEZIONAB/i', $cell['text']) === 1) {
                $notes[$cell['text']] = true;
            }
        }

        return array_keys($notes);
    }

    private function firstMatch(string $pattern, string $subject, int $group = 0): ?string
    {
        if (preg_match($pattern, $subject, $m) === 1) {
            return trim($m[$group] ?? '') !== '' ? trim($m[$group]) : null;
        }

        return null;
    }
}
