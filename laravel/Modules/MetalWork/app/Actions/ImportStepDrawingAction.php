<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Support\StepFileParser;

use function Safe\hash_file;

/**
 * Parses a STEP file and files it under its drawing number.
 *
 * The same drawing arrives more than once — a revision, or simply the operator
 * re-quoting a job — so the row is keyed by content hash: identical bytes update
 * the existing drawing instead of piling up duplicates in the archive, while a
 * changed file becomes a new row and leaves the old estimates pointing at the
 * geometry they were computed from.
 *
 * Importing also recovers the bill of materials — see
 * {@see BuildDrawingBillOfMaterialsAction} — because the customer's exports are
 * flattened and the component list has to be measured out of the solids.
 */
final class ImportStepDrawingAction
{
    public function __construct(
        private readonly StepFileParser $parser = new StepFileParser(),
        private readonly BuildDrawingBillOfMaterialsAction $billOfMaterials = new BuildDrawingBillOfMaterialsAction(),
    ) {}

    public function execute(string $path, ?string $storagePath = null, int|string|null $userId = null): MetalDrawing
    {
        $geometry = $this->parser->parse($path);
        $hash = hash_file('sha256', $path);

        $attributes = [
            ...$geometry->toArray(),
            'storage_path' => $storagePath,
            'content_hash' => $hash,
            'imported_by' => $userId,
        ];

        $existing = MetalDrawing::query()->where('content_hash', $hash)->first();

        $drawing = $existing ?? MetalDrawing::query()->create($attributes);

        if ($existing !== null) {
            $drawing->fill($attributes)->save();
        }

        // The bill of materials is what the quotation is built from, so it is
        // recovered as part of the import rather than on demand: the file is
        // already open, and the estimate screen must never wait on a 5 MB parse.
        $this->billOfMaterials->execute($drawing, $path);

        return $drawing;
    }
}
