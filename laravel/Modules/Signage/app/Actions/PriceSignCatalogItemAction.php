<?php

declare(strict_types=1);

namespace Modules\Signage\Actions;

use Illuminate\Support\Collection;
use Modules\Costing\Datas\MarkupDecision;
use Modules\Costing\Models\WorkCenter;
use Modules\Costing\Support\MarkupResolver;
use Modules\Signage\Datas\SignCatalogCostLine;
use Modules\Signage\Datas\SignCatalogItemPrice;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignCatalogItemComponent;
use Modules\Signage\Support\SignageCosting;

/**
 * Costs a catalog item from its distinta and applies the markup cascade.
 *
 * Cost of a row: surface units take the quantity from the piece of material
 * consumed (B x H in millimetres), every other unit multiplies the quantity
 * directly. Markup is resolved per row, most specific first:
 *
 *   item.markup_percent -> work centre -> price list -> config default
 *
 * A hand-written `manual_sale_price` wins over the whole computation; the
 * percentages are then derived from it, which is how the customer's gestionale
 * behaves today.
 */
final class PriceSignCatalogItemAction
{
    public function execute(SignCatalogItem $item): SignCatalogItemPrice
    {
        $item->loadMissing(['components.supplierItem', 'priceList']);

        $workCenters = WorkCenter::query()->forContext(SignageCosting::CONTEXT)->get()->keyBy('code');

        $lines = [];
        $totalCost = 0.0;
        $computedPrice = 0.0;

        /** @var SignCatalogItemComponent $component */
        foreach ($item->components as $component) {
            $quantity = $component->effectiveQuantity();
            $unitCost = (float) $component->unit_cost;
            $lineCost = $component->lineCost();

            $decision = $this->resolveMarkup($item, $component, $workCenters);
            $markup = $decision->percent;
            $source = $decision->sourceCode;
            $linePrice = $decision->priceOf($lineCost);

            $supplierItem = $component->supplierItem;

            $lines[] = new SignCatalogCostLine(
                position: (int) $component->position,
                workCenterCode: (string) $component->work_center_code,
                supplierItemCode: $supplierItem?->code,
                supplierName: $supplierItem?->supplier_name,
                description: (string) $component->description,
                unitCode: (string) $component->unit_code,
                quantity: $quantity,
                widthMm: $component->width_mm === null ? null : (float) $component->width_mm,
                heightMm: $component->height_mm === null ? null : (float) $component->height_mm,
                unitCost: $unitCost,
                lineCost: $lineCost,
                markupPercent: $markup,
                linePrice: $linePrice,
                markupSourceCode: $source,
            );

            $totalCost += $lineCost;
            $computedPrice += $linePrice;
        }

        $isManual = $item->manual_sale_price !== null;
        $salePrice = $isManual ? (float) $item->manual_sale_price : round($computedPrice, 2);

        return new SignCatalogItemPrice(
            code: (string) $item->code,
            name: (string) $item->name,
            lines: $lines,
            totalCost: round($totalCost, 2),
            salePrice: $salePrice,
            isManualPrice: $isManual,
        );
    }

    /**
     * Most specific first: the item's own override, then its work centre, then
     * the price list it belongs to, then the system default.
     *
     * @param  Collection<string, WorkCenter>  $workCenters
     */
    private function resolveMarkup(
        SignCatalogItem $item,
        SignCatalogItemComponent $component,
        Collection $workCenters,
    ): MarkupDecision {
        $workCenter = $workCenters->get((string) $component->work_center_code);

        return MarkupResolver::firstOf([
            'item' => $item->markup_percent === null ? null : (float) $item->markup_percent,
            'work_center' => $workCenter?->default_markup_percent === null ? null : (float) $workCenter->default_markup_percent,
            'price_list' => $item->priceList?->default_markup_percent === null ? null : (float) $item->priceList->default_markup_percent,
            'config' => MarkupResolver::configDefault('signage.default_markup_percent'),
        ]);
    }
}
