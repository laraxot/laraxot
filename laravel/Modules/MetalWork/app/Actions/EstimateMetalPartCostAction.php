<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Collection;
use Modules\Costing\Datas\MarkupDecision;
use Modules\Costing\Models\WorkCenter;
use Modules\Costing\Support\MarkupResolver;
use Modules\MetalWork\Datas\MetalCostLine;
use Modules\MetalWork\Datas\MetalPartCosting;
use Modules\MetalWork\Datas\StepGeometry;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Support\MetalWorksCosting;

/**
 * Costs one part from the geometry read out of its drawing.
 *
 * Two rows of very different confidence end up side by side, and the screen says
 * which is which:
 *
 *  - the material row is arithmetic on a measured bounding box and a price the
 *    supplier actually invoiced;
 *  - the machining rows are the workshop's own minutes-per-unit rules applied to
 *    counted features, i.e. a starting point the operator corrects.
 *
 * Weight is derived, never invented. A flat part weighs footprint x thickness,
 * which is exact for a blank. A solid one is the bounding box times an occupancy
 * factor the operator sees and can change, because the exact volume of a B-rep
 * cannot be computed without a geometry kernel.
 *
 * Markup is resolved per row, most specific first:
 *
 *   options.markup_percent -> work centre -> config default
 */
final class EstimateMetalPartCostAction
{
    /**
     * @param  array{
     *     quantity?: int,
     *     thickness_mm?: float|null,
     *     occupancy_percent?: float|null,
     *     scrap_percent?: float|null,
     *     markup_percent?: float|null,
     *     manual_sale_price?: float|null,
     *     operations?: array<string, float>
     * }  $options  `operations` overrides the derived minutes, keyed by work centre
     */
    public function execute(
        StepGeometry $geometry,
        ?MetalRawMaterial $material = null,
        array $options = [],
    ): MetalPartCosting {
        $workCenters = WorkCenter::query()->forContext(MetalWorksCosting::CONTEXT)->get()->keyBy('code');

        $quantity = max(1, (int) ($options['quantity'] ?? 1));
        $scrapPercent = $this->floatOption($options, 'scrap_percent') ?? $this->configFloat('default_scrap_percent', 0.0);
        $markupOverride = $this->floatOption($options, 'markup_percent');

        $warnings = [];
        $lines = [];
        $position = 0;

        [$netWeightKg, $weightWarning] = $this->netWeight($geometry, $material, $options);

        if ($weightWarning !== null) {
            $warnings[] = $weightWarning;
        }

        $grossWeightKg = round($netWeightKg * (1 + $scrapPercent / 100), 3);

        $materialLine = $this->materialLine(
            $geometry,
            $material,
            $grossWeightKg,
            $workCenters,
            $markupOverride,
            $position,
            $warnings,
        );

        if ($materialLine !== null) {
            $lines[] = $materialLine;
            $position++;
        }

        foreach ($this->operationLines($geometry, $workCenters, $markupOverride, $options, $position, $warnings) as $line) {
            $lines[] = $line;
        }

        $unitCost = round(array_sum(array_map(static fn (MetalCostLine $l): float => $l->lineCost, $lines)), 2);
        $computedPrice = round(array_sum(array_map(static fn (MetalCostLine $l): float => $l->linePrice, $lines)), 2);

        $manualPrice = $this->floatOption($options, 'manual_sale_price');
        $isManual = $manualPrice !== null;

        return new MetalPartCosting(
            partCode: $geometry->partCode,
            name: $geometry->name,
            quantity: $quantity,
            netWeightKg: $netWeightKg,
            grossWeightKg: $grossWeightKg,
            lines: $lines,
            unitCost: $unitCost,
            unitSalePrice: $isManual ? round($manualPrice, 2) : $computedPrice,
            isManualPrice: $isManual,
            warnings: $warnings,
        );
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array{float, string|null}
     */
    private function netWeight(StepGeometry $geometry, ?MetalRawMaterial $material, array $options): array
    {
        $volumeMm3 = $geometry->boundingVolumeMm3();

        if ($volumeMm3 === null) {
            return [0.0, 'no_bounding_box'];
        }

        $density = $material?->density() ?? $this->configFloat('default_density_kg_dm3', 7.85);

        // A blank is fully described by footprint x thickness: no occupancy guess
        // needed, and no warning to raise. The drawing's own thickness wins over
        // the price list's, since it measures this part rather than the stock it
        // was cut from.
        if ($geometry->isFlat()) {
            $thicknessMm = $this->floatOption($options, 'thickness_mm')
                ?? $geometry->thicknessMm
                ?? ($material?->thickness_mm !== null ? (float) $material->thickness_mm : 0.0);

            // m2 x m = m3, and a cubic metre is 1.000 cubic decimetres.
            $volumeDm3 = ($geometry->footprintSqm() ?? 0.0) * ($thicknessMm / 1000) * 1000;

            return [round($volumeDm3 * $density, 3), null];
        }

        $occupancy = $this->floatOption($options, 'occupancy_percent')
            ?? $this->configFloat('default_occupancy_percent', 100.0);

        // mm3 -> dm3 is a factor of 1.000.000.
        $volumeDm3 = $volumeMm3 / 1_000_000 * ($occupancy / 100);

        return [round($volumeDm3 * $density, 3), 'occupancy_estimated'];
    }

    /**
     * @param  Collection<string, WorkCenter>  $workCenters
     * @param  list<string>  $warnings
     */
    private function materialLine(
        StepGeometry $geometry,
        ?MetalRawMaterial $material,
        float $grossWeightKg,
        Collection $workCenters,
        ?float $markupOverride,
        int $position,
        array &$warnings,
    ): ?MetalCostLine {
        if ($material === null) {
            $warnings[] = 'no_material_selected';

            return null;
        }

        $unitPrice = $material->currentUnitPrice();

        if ($unitPrice === null) {
            $warnings[] = 'no_material_price';

            return null;
        }

        [$quantity, $unitCode, $driver] = $this->materialQuantity($material, $geometry, $grossWeightKg);
        $lineCost = round($quantity * $unitPrice, 2);
        $decision = $this->resolveMarkup('material', $workCenters, $markupOverride);

        return new MetalCostLine(
            position: $position,
            workCenterCode: 'material',
            description: (string) $material->description,
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
     * How much of the material the part consumes, in the unit the supplier prices
     * it in: kilos for sheet and plate, metres for profiles cut to length, square
     * metres for anything sold by area, one piece otherwise.
     *
     * @return array{float, string, string}
     */
    private function materialQuantity(MetalRawMaterial $material, StepGeometry $geometry, float $grossWeightKg): array
    {
        return match ((string) $material->unit_code) {
            'ml', 'mt' => [
                $geometry->lengthMm === null ? 0.0 : $geometry->lengthMm / 1000,
                'ml',
                'bbox_length',
            ],
            'mq' => [$geometry->footprintSqm() ?? 0.0, 'mq', 'footprint'],
            'nr', 'pz' => [1.0, 'nr', 'piece'],
            default => [$grossWeightKg, 'kg', 'gross_weight'],
        };
    }

    /**
     * Turns the geometry into machining rows through the workshop's own rules.
     * An operator-entered override for a work centre replaces the derived minutes
     * for that centre; a rule whose driver is zero produces no row at all.
     *
     * @param  Collection<string, WorkCenter>  $workCenters
     * @param  array<string, mixed>  $options
     * @param  list<string>  $warnings
     * @return list<MetalCostLine>
     */
    private function operationLines(
        StepGeometry $geometry,
        Collection $workCenters,
        ?float $markupOverride,
        array $options,
        int $position,
        array &$warnings,
    ): array {
        $overrides = is_array($options['operations'] ?? null) ? $options['operations'] : [];
        $lines = [];

        foreach ($this->rules() as $rule) {
            $code = $this->stringValue($rule, 'work_center_code');
            $workCenter = $workCenters->get($code);

            if (! $workCenter instanceof WorkCenter) {
                continue;
            }

            $minutes = array_key_exists($code, $overrides)
                ? (float) $overrides[$code]
                : $this->derivedMinutes($rule, $geometry);

            if ($minutes <= 0.0) {
                continue;
            }

            $hours = round($minutes / 60, 4);
            $lineCost = $workCenter->costOfMinutes($minutes);
            $decision = $this->resolveMarkup($code, $workCenters, $markupOverride);

            $lines[] = new MetalCostLine(
                position: $position++,
                workCenterCode: $code,
                description: (string) $workCenter->name,
                unitCode: 'h',
                quantity: $hours,
                unitCost: (float) $workCenter->hourly_rate,
                lineCost: $lineCost,
                markupPercent: $decision->percent,
                linePrice: $decision->priceOf($lineCost),
                markupSourceCode: $decision->sourceCode,
                driverCode: array_key_exists($code, $overrides) ? 'manual' : $this->stringValue($rule, 'driver'),
            );
        }

        if ($lines === [] && ! $geometry->hasBoundingBox()) {
            $warnings[] = 'no_operations_derived';
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $rule
     */
    private function derivedMinutes(array $rule, StepGeometry $geometry): float
    {
        if (($rule['only_flat_parts'] ?? false) === true && ! $geometry->isFlat()) {
            return 0.0;
        }

        if (($rule['only_thick_parts'] ?? false) === true && $geometry->isFlat()) {
            return 0.0;
        }

        $driver = $this->driverValue($this->stringValue($rule, 'driver'), $geometry);
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

    private function driverValue(string $driver, StepGeometry $geometry): float
    {
        return match ($driver) {
            'cut_length_m' => $geometry->cutLengthM() ?? 0.0,
            'hole_count' => (float) $geometry->estimatedHoleCount(),
            'bend_count' => (float) $geometry->estimatedBendCount(),
            'solid_count' => (float) $geometry->solidCount,
            'surface_sqm' => $geometry->boundingSurfaceSqm() ?? 0.0,
            'weight_kg' => 0.0,
            default => 0.0,
        };
    }

    /**
     * Most specific first: the estimate's own override, then its work centre,
     * then the system default.
     *
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
        $rules = config('metalwork.operation_rules', []);

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
