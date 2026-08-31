<?php

declare(strict_types=1);

namespace Modules\Signage\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Signage\Datas\ArtworkExtraction;
use Modules\Signage\Models\SignComponent;
use Modules\Signage\Models\SignSpec;

/**
 * Runs the artwork extraction and persists it as a SignSpec with its
 * component rows — the starting point of a quotation.
 */
final class ImportArtworkAsSignSpecAction
{
    public function __construct(
        private readonly ExtractSignComponentsFromArtworkAction $extract,
    ) {}

    public function execute(string $filePath, int|string|null $userId = null): SignSpec
    {
        $extraction = $this->extract->execute($filePath);

        return DB::transaction(function () use ($extraction, $userId): SignSpec {
            $spec = SignSpec::query()->create($this->specAttributes($extraction, $userId));

            $position = 0;
            foreach ($extraction->components as $component) {
                $spec->components()->save(new SignComponent([
                    'position' => $position++,
                    'role_code' => $component->role,
                    'label' => $component->label,
                    'description' => $component->description,
                    'material' => $component->material,
                    'color_ral' => $component->colorRal,
                    'thickness_mm' => $component->thicknessMm,
                    'lighting' => $component->lighting,
                    'source_code' => $component->source,
                ]));
            }

            return $spec;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function specAttributes(ArtworkExtraction $extraction, int|string|null $userId): array
    {
        $tb = $extraction->titleBlock;
        $decoded = $extraction->decodedCode;

        [$width, $height] = $this->splitDimensions($decoded['dimensions_mm']);

        return [
            'source_file' => $extraction->sourceFile,
            'drawing_title' => $extraction->drawingTitle,
            'customer_name' => $tb['customer'],
            'referent_name' => $tb['referent'],
            'project' => $tb['project'],
            'brand' => $tb['brand'],
            'shop' => $tb['shop'],
            'sign_type_code' => $tb['sign_type_code'],
            'quantity' => $tb['quantity'],
            'family' => $decoded['family'],
            'color_ral' => $decoded['color_ral'],
            'thickness_mm' => $decoded['thickness_mm'],
            'width_mm' => $width,
            'height_mm' => $height,
            'painting' => $tb['painting'],
            'finishing' => $tb['finishing'],
            'lighting' => $tb['lighting'],
            'scale' => $tb['scale'],
            'sheet_size' => $tb['size'],
            'revision' => $tb['revision'],
            'creator' => $extraction->creator,
            'layers' => $extraction->layers,
            'notes' => $extraction->notes,
            'raw_extraction' => $extraction->toArray(),
            'created_by_user_id' => $userId,
        ];
    }

    /**
     * @return array{0: ?int, 1: ?int}
     */
    private function splitDimensions(?string $dimensions): array
    {
        if ($dimensions === null || ! str_contains($dimensions, 'x')) {
            return [null, null];
        }

        [$width, $height] = explode('x', $dimensions, 2);

        return [
            ctype_digit($width) ? (int) $width : null,
            ctype_digit($height) ? (int) $height : null,
        ];
    }
}
