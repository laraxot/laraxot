<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;
use Modules\Signage\Models\SignPriceListItem;

/**
 * A sales price list built from the one visible in Signage's gestionale:
 * BV_SIDE_WWcbc for BOTTEGA VENETA, with the letter-height bands they actually
 * sell and the distinta of the first band.
 *
 * Two honesty notes, because the screenshot only shows part of the picture:
 *
 *  - the distinta below is the **visible excerpt** of that item's rows (the list
 *    scrolls past RS_file), so its cost is lower than the 2.638,47 the gestionale
 *    reports for the complete distinta;
 *  - for the same reason no sale price is forced here. The listed prices are kept
 *    in `notes` on each item so they can be checked once the full distinta is in.
 *
 * Idempotent (keyed on code). Requires SignPriceListSeeder + SignWorkCenterSeeder.
 */
final class SignCatalogDemoSeeder extends Seeder
{
    public function run(): void
    {
        $priceList = SignPriceList::query()->updateOrCreate(
            ['code' => 'BV_SIDE_WWcbc'],
            ['name' => 'BV_SIDE_WWcbc', 'brand' => 'BOTTEGA VENETA', 'is_active' => true],
        );

        // Letter-height bands and the prices shown in the gestionale, in order.
        $bands = [
            ['083150', 83, 150, 7680.00],
            ['151200', 151, 200, 7970.00],
            ['201250', 201, 250, 8280.00],
            ['251300', 251, 300, 8610.00],
            ['301350', 301, 350, 8960.00],
            ['351400', 351, 400, 9720.00],
        ];

        $first = null;

        foreach ($bands as $index => [$band, $min, $max, $listedPrice]) {
            $item = SignCatalogItem::query()->updateOrCreate(
                ['code' => "BV_SIDE_WWcbc_{$band} 3500K"],
                [
                    'sign_price_list_id' => $priceList->id,
                    'name' => "Sign BOTTEGA VENETA height letter B from {$min}mm to {$max}mm",
                    'description' => "L-SL-B - Single line logo\nINDOOR SIGN, CE CERTIFICATION, CERTIFICATION UL LISTED E524458, CCC FOR DRIVERS\nUL Certificate for the drivers and LED",
                    'family_code' => 'SIDE',
                    'color_code' => 'WWcbc',
                    'letter_height_min_mm' => $min,
                    'letter_height_max_mm' => $max,
                    'led_temperature_code' => '3500K',
                    'notes' => 'Listed price in the current gestionale: '.number_format($listedPrice, 2, ',', '.').' EUR',
                    'unit_code' => 'nr',
                    'sort_order' => ($index + 1) * 10,
                    'is_active' => true,
                ],
            );

            $first ??= $item;
        }

        if ($first instanceof SignCatalogItem) {
            $this->seedDistinta($first);
        }
    }

    /**
     * The six rows readable in the distinta screenshot. Surface rows carry the
     * piece of material consumed as B x H, exactly as the operator types it.
     */
    private function seedDistinta(SignCatalogItem $item): void
    {
        $rows = [
            [10, 'material', 'OT63_L-CR_200010001', 'LASTRA OTTONE OT63 LUCID. A SPECCHIO + PVC / CRUDO MM 2000x1000x1', 'mq', 1, 1800, 200, 116.4500],
            [20, 'milling', '99900029', 'Taglio fresa ottone 1.0mm', 'mt', 10, null, null, 2.1000],
            [30, 'material', 'OT63_L-CT_200010000,8', 'LASTRA OTTONE OT63 LUCID. A SPECCHIO + PVC / COTTO MM 2000x1000x0,8', 'mq', 1, 12500, 25, 93.1600],
            [40, 'milling', '99900028', 'Taglio fresa ottone 0.8mm', 'mt', 25, null, null, 1.4000],
            [50, 'labor', 'RS_SCAT_DIOR', 'Piegatura + stagnatura lettere scatolate', 'min', 780, null, null, 0.5000],
            [60, 'labor', 'RS_file', 'Elaborazione file Retail Sign', 'min', 60, null, null, 0.7000],
        ];

        foreach ($rows as [$position, $workCenter, $supplierCode, $description, $unit, $quantity, $width, $height, $unitCost]) {
            $supplierItem = SignPriceListItem::query()->where('code', $supplierCode)->first();

            $item->components()->updateOrCreate(
                ['position' => $position],
                [
                    'work_center_code' => $workCenter,
                    'sign_price_list_item_id' => $supplierItem?->id,
                    'description' => $description,
                    'unit_code' => $unit,
                    'quantity' => $quantity,
                    'width_mm' => $width,
                    'height_mm' => $height,
                    'unit_cost' => $unitCost,
                ],
            );
        }
    }
}
