<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Collection;
use Modules\MetalWork\Datas\StepSolid;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Support\RawMaterialDimensionReader;

/**
 * Proposes the stock a piece is cut from, by matching its section against the
 * article codes the suppliers actually invoice.
 *
 * Which families are even eligible follows how the workshop buys, as their own
 * invoices show it: thin flat pieces come off sheet, thick ones are bought as
 * oxy-cut blanks by the kilo ("PIASTRE OSSITAGLIATE VARIO SPESSORE"), long
 * pieces come off a profile of the right section, and solid blocks come from bar
 * stock or, again, oxy-cutting.
 *
 * The result is a proposal, never a decision: the operator confirms it on screen.
 * Where nothing fits, the row is left empty — a wrong material silently priced is
 * worse than an obvious gap.
 */
final class MatchRawMaterialToSolidAction
{
    /** Above this thickness a flat piece is bought as an oxy-cut blank, not as sheet. */
    private const SHEET_MAX_THICKNESS_MM = 4.0;

    /** Beyond this relative oversize the stock is the wrong size for the job. */
    private const MAX_FIT_ERROR = 0.6;

    /** @var array<string, list<string>> eligible material families per piece shape */
    private const ELIGIBLE_FAMILIES = [
        'plate' => ['sheet', 'oxy_cut', 'flat'],
        'bar' => ['tube', 'flat', 'angle', 'square', 'round', 'beam'],
        'block' => ['oxy_cut', 'square', 'round', 'flat'],
    ];

    public function __construct(
        private readonly RawMaterialDimensionReader $reader = new RawMaterialDimensionReader(),
    ) {}

    /**
     * @param  Collection<int, MetalRawMaterial>|null  $materials  pass the price list
     *                                                             once when matching a whole drawing
     */
    public function execute(StepSolid $solid, ?Collection $materials = null): ?MetalRawMaterial
    {
        $materials ??= MetalRawMaterial::query()->where('is_active', true)->get();
        $families = $this->eligibleFamilies($solid);
        $needed = $this->neededSections($solid);

        $best = null;
        $bestError = null;

        foreach ($materials as $material) {
            $family = (string) $material->family_code;

            if (! in_array($family, $families, true)) {
                continue;
            }

            // Oxy-cutting is sold by the kilo to any shape, so it always fits; it
            // scores last so a real profile of the right section wins over it.
            if ($family === 'oxy_cut') {
                $error = self::MAX_FIT_ERROR;
            } else {
                $stock = $this->reader->read((string) $material->code, $family);
                $error = $this->reader->fitScore($stock, $needed[$family] ?? $needed['section']);

                if ($error === null || $error > self::MAX_FIT_ERROR) {
                    continue;
                }
            }

            if ($bestError === null || $error < $bestError) {
                $best = $material;
                $bestError = $error;
            }
        }

        return $best;
    }

    /**
     * @return list<string>
     */
    private function eligibleFamilies(StepSolid $solid): array
    {
        $shape = $solid->shapeCode();

        if ($shape === 'plate' && $solid->thicknessMm > self::SHEET_MAX_THICKNESS_MM) {
            // Too thick to come off a coil: bought as a cut blank or a flat bar.
            return ['oxy_cut', 'flat'];
        }

        return self::ELIGIBLE_FAMILIES[$shape] ?? [];
    }

    /**
     * What the stock has to cover, which depends on what it is.
     *
     * Sheet is sold by thickness and cut to any outline, so only the thickness has
     * to match. Every other family is a profile of a fixed cross-section: a piece
     * 374 mm wide cannot be sawn out of a 30 mm flat bar however thick it is, and
     * reading the leading number of `FP30X15` as a thickness is exactly how that
     * mistake gets made — it is the width.
     *
     * @return array{sheet: list<float>, section: list<float>}
     */
    private function neededSections(StepSolid $solid): array
    {
        return [
            'sheet' => [$solid->thicknessMm],
            'section' => [$solid->widthMm, $solid->thicknessMm],
        ];
    }
}
