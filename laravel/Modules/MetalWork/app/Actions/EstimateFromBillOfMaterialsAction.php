<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Collection;
use Modules\Costing\Datas\MarkupDecision;
use Modules\Costing\Models\WorkCenter;
use Modules\Costing\Support\MarkupResolver;
use Modules\MetalWork\Datas\MetalCostLine;
use Modules\MetalWork\Datas\MetalPartCosting;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalDrawingComponent;
use Modules\MetalWork\Support\MetalWorksCosting;

/**
 * Costs a drawing from its bill of materials: one material row per piece the
 * drawing contains, plus the machining the whole set adds up to.
 *
 * This is the accurate half of the module. Costing a 228-piece machine base as
 * one lump of steel means guessing how much of its bounding box is solid; costing
 * it piece by piece does not, because each piece is bought as the rectangle or
 * the length of profile it is cut from, and that is a real purchase with a real
 * invoiced price behind it.
 *
 * What is still approximate is stated rather than hidden:
 *  - a piece priced by the metre is exact, the profile is bought cut to length;
 *  - a solid piece priced by the kilo is exact, its blank is the bounding box;
 *  - a hollow profile priced by the kilo is not, and raises a warning, because
 *    its bounding box is solid where the section is not.
 */
final class EstimateFromBillOfMaterialsAction
{
    /** Sections that enclose air: their bounding box is not their weight. */
    /** @var list<string> */
    private const array HOLLOW_FAMILIES = ['tube', 'beam'];

    /**
     * Accetta l'intero set di opzioni di pricing della pagina, anche quelle che
     * qui non servono (spessore e riempimento riguardano il pezzo singolo): il
     * chiamante non deve sapere quale action usa cosa.
     *
     * @param  array{
     *     quantity?: int,
     *     thickness_mm?: float|null,
     *     occupancy_percent?: float|null,
     *     scrap_percent?: float|null,
     *     markup_percent?: float|null,
     *     manual_sale_price?: float|null,
     *     operations?: array<string, float>
     * }  $options
     */
    public function execute(MetalDrawing $drawing, array $options = []): MetalPartCosting
    {
        $drawing->loadMissing('components.rawMaterial');
        $components = $drawing->components;

        $workCenters = WorkCenter::query()->forContext(MetalWorksCosting::CONTEXT)->get()->keyBy('code');
        $quantity = max(1, (int) ($options['quantity'] ?? 1));
        $scrapPercent = $this->floatOption($options, 'scrap_percent') ?? $this->configFloat('default_scrap_percent', 0.0);
        $markupOverride = $this->floatOption($options, 'markup_percent');

        $warnings = [];
        $lines = [];
        $position = 0;
        $netWeightKg = 0.0;

        foreach ($components as $component) {
            $netWeightKg += $this->componentWeightKg($component);

            $line = $this->materialLine($component, $scrapPercent, $workCenters, $markupOverride, $position, $warnings);

            if ($line !== null) {
                $lines[] = $line;
                $position++;
            }
        }

        foreach ($this->operationLines($components, $workCenters, $markupOverride, $options, $position) as $line) {
            $lines[] = $line;
        }

        if ($components->isEmpty()) {
            $warnings[] = 'no_bill_of_materials';
        }

        $unitCost = round(array_sum(array_map(static fn (MetalCostLine $l): float => $l->lineCost, $lines)), 2);
        $computedPrice = round(array_sum(array_map(static fn (MetalCostLine $l): float => $l->linePrice, $lines)), 2);
        $manualPrice = $this->floatOption($options, 'manual_sale_price');

        return new MetalPartCosting(
            partCode: (string) $drawing->part_code,
            name: (string) $drawing->name,
            quantity: $quantity,
            netWeightKg: round($netWeightKg, 3),
            grossWeightKg: round($netWeightKg * (1 + $scrapPercent / 100), 3),
            lines: $lines,
            unitCost: $unitCost,
            unitSalePrice: $manualPrice !== null ? round($manualPrice, 2) : $computedPrice,
            isManualPrice: $manualPrice !== null,
            warnings: array_values(array_unique($warnings)),
        );
    }

    /**
     * Weight of every piece on the row, from the stock each one is cut from.
     */
    private function componentWeightKg(MetalDrawingComponent $component): float
    {
        $material = $component->rawMaterial;
        $density = $material?->density() ?? $this->configFloat('default_density_kg_dm3', 7.85);

        // A profile bought by the metre states its own weight per metre; the
        // bounding box of a tube would count the air inside it as steel.
        if ($material !== null && $material->weight_per_meter_kg !== null) {
            return (float) $component->length_mm / 1000 * (float) $material->weight_per_meter_kg * $component->quantity;
        }

        // mm3 -> dm3 is a factor of 1.000.000.
        return $component->stockVolumeMm3() / 1_000_000 * $density;
    }

    /**
     * @param  Collection<string, WorkCenter>  $workCenters
     * @param  list<string>  $warnings
     */
    private function materialLine(
        MetalDrawingComponent $component,
        float $scrapPercent,
        Collection $workCenters,
        ?float $markupOverride,
        int $position,
        array &$warnings,
    ): ?MetalCostLine {
        $material = $component->rawMaterial;

        if ($material === null) {
            $warnings[] = 'component_without_material';

            return null;
        }

        $unitPrice = $material->currentUnitPrice();

        if ($unitPrice === null) {
            $warnings[] = 'component_without_price';

            return null;
        }

        [$quantity, $unitCode, $driver] = $this->materialQuantity($component, $material->unit_code === null ? 'kg' : (string) $material->unit_code);

        if ($unitCode === 'kg') {
            $quantity = round($quantity * (1 + $scrapPercent / 100), 4);

            if (in_array((string) $material->family_code, self::HOLLOW_FAMILIES, true)) {
                $warnings[] = 'hollow_section_weight_estimated';
            }
        }

        $lineCost = round($quantity * $unitPrice, 2);
        $decision = $this->resolveMarkup('material', $workCenters, $markupOverride);

        return new MetalCostLine(
            position: $position,
            workCenterCode: 'material',
            description: sprintf(
                '%d × %s — %s',
                $component->quantity,
                $component->label(),
                (string) $material->description,
            ),
            unitCode: $unitCode,
            quantity: round($quantity, 4),
            unitCost: $unitPrice,
            lineCost: $lineCost,
            markupPercent: $decision->percent,
            linePrice: $decision->priceOf($lineCost),
            markupSourceCode: $decision->sourceCode,
            driverCode: $driver,
        );
    }

    /**
     * How much stock the row consumes, in the unit the supplier prices it in.
     *
     * @return array{float, string, string}
     */
    private function materialQuantity(MetalDrawingComponent $component, string $unitCode): array
    {
        return match ($unitCode) {
            'ml', 'mt' => [
                (float) $component->length_mm / 1000 * $component->quantity,
                'ml',
                'component_length',
            ],
            'mq' => [
                (float) $component->length_mm / 1000 * ((float) $component->width_mm / 1000) * $component->quantity,
                'mq',
                'component_footprint',
            ],
            'nr', 'pz' => [(float) $component->quantity, 'nr', 'component_count'],
            default => [$this->componentWeightKg($component), 'kg', 'component_weight'],
        };
    }

    /**
     * The machining the whole drawing adds up to. Drivers are summed across the
     * bill of materials — every hole in every piece, every metre of profiled cut —
     * so a rule fires once with the real total instead of once per piece.
     *
     * @param  Collection<int, MetalDrawingComponent>  $components
     * @param  Collection<string, WorkCenter>  $workCenters
     * @param  array<string, mixed>  $options
     * @return list<MetalCostLine>
     */
    private function operationLines(
        Collection $components,
        Collection $workCenters,
        ?float $markupOverride,
        array $options,
        int $position,
    ): array {
        $overrides = is_array($options['operations'] ?? null) ? $options['operations'] : [];
        $drivers = $this->drivers($components);
        $lines = [];

        foreach ($this->rules() as $rule) {
            $code = $this->stringValue($rule, 'work_center_code');
            $workCenter = $workCenters->get($code);

            if (! $workCenter instanceof WorkCenter) {
                continue;
            }

            $overrideMinutes = $overrides[$code] ?? null;
            $minutes = is_int($overrideMinutes) || is_float($overrideMinutes)
                ? (float) $overrideMinutes
                : $this->derivedMinutes($rule, $drivers);

            if ($minutes <= 0.0) {
                continue;
            }

            $lineCost = $workCenter->costOfMinutes($minutes);
            $decision = $this->resolveMarkup($code, $workCenters, $markupOverride);

            $lines[] = new MetalCostLine(
                position: $position++,
                workCenterCode: $code,
                description: (string) $workCenter->name,
                unitCode: 'h',
                quantity: round($minutes / 60, 4),
                unitCost: (float) $workCenter->hourly_rate,
                lineCost: $lineCost,
                markupPercent: $decision->percent,
                linePrice: $decision->priceOf($lineCost),
                markupSourceCode: $decision->sourceCode,
                driverCode: array_key_exists($code, $overrides) ? 'manual' : $this->stringValue($rule, 'driver'),
            );
        }

        return $lines;
    }

    /**
     * Totals of every quantity the machining rules can be driven by.
     *
     * A square offcut is sawn and a profiled outline is torched, so the two kinds
     * of cutting are counted separately: charging laser time for a bar cut to
     * length is the single easiest way to price a job out of the market.
     *
     * @param  Collection<int, MetalDrawingComponent>  $components
     * @return array<string, float>
     */
    private function drivers(Collection $components): array
    {
        $drivers = [
            'profiled_cut_length_m' => 0.0,
            'square_cut_count' => 0.0,
            'hole_count' => 0.0,
            'piece_count' => 0.0,
            'surface_sqm' => 0.0,
            'weight_kg' => 0.0,
        ];

        foreach ($components as $component) {
            $quantity = (float) $component->quantity;
            $solid = $component->solid();

            $drivers['piece_count'] += $quantity;
            $drivers['hole_count'] += (float) $component->hole_count * $quantity;
            $drivers['weight_kg'] += $this->componentWeightKg($component);
            $drivers['surface_sqm'] += $solid->boundingSurfaceSqm() * $quantity;

            if ($component->has_profiled_cut) {
                $drivers['profiled_cut_length_m'] += $solid->cutLengthM() * $quantity;

                continue;
            }

            $drivers['square_cut_count'] += $quantity;
        }

        return $drivers;
    }

    /**
     * @param  array<string, mixed>  $rule
     * @param  array<string, float>  $drivers
     */
    private function derivedMinutes(array $rule, array $drivers): float
    {
        $driver = $drivers[$this->stringValue($rule, 'driver')] ?? 0.0;
        $minimum = isset($rule['min_driver_value']) && is_numeric($rule['min_driver_value'])
            ? (float) $rule['min_driver_value']
            : 0.0;

        if ($driver <= 0.0 || $driver < $minimum) {
            return 0.0;
        }

        $perUnit = isset($rule['minutes_per_unit']) && is_numeric($rule['minutes_per_unit'])
            ? (float) $rule['minutes_per_unit']
            : 0.0;
        $setup = isset($rule['setup_minutes']) && is_numeric($rule['setup_minutes'])
            ? (float) $rule['setup_minutes']
            : 0.0;

        return round($driver * $perUnit + $setup, 3);
    }

    /**
     * @param  Collection<string, WorkCenter>  $workCenters
     */
    private function resolveMarkup(string $workCenterCode, Collection $workCenters, ?float $override): MarkupDecision
    {
        $workCenter = $workCenters->get($workCenterCode);

        return MarkupResolver::firstOf([
            'estimate' => $override,
            'work_center' => $workCenter?->default_markup_percent === null ? null : (float) $workCenter->default_markup_percent,
            'config' => MarkupResolver::configDefault('metalworks.default_markup_percent'),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function rules(): array
    {
        $rules = config('metalwork.bill_of_materials_rules', []);

        if (! is_array($rules)) {
            return [];
        }

        $valid = [];

        foreach ($rules as $rule) {
            if (is_array($rule)) {
                /** @var array<string, mixed> $rule */
                $valid[] = $rule;
            }
        }

        return $valid;
    }

    /**
     * @param  array<string, mixed>  $rule
     */
    private function stringValue(array $rule, string $key): string
    {
        $value = $rule[$key] ?? null;

        return is_string($value) ? $value : '';
    }

    /**
     * @param  array<string, mixed>  $options
     */
    private function floatOption(array $options, string $key): ?float
    {
        $value = $options[$key] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    private function configFloat(string $key, float $fallback): float
    {
        $value = config('metalwork.'.$key, $fallback);

        return is_numeric($value) ? (float) $value : $fallback;
    }
}
