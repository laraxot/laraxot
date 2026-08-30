<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Signage\Models\SignPriceListItem;

/**
 * Starter price list for Signage, transcribed from the real items visible in the
 * COSA gestionale (distinta base + supplier lists): brass sheets, milling, box-
 * letter labour and STRIPLED strips. These are Signage's own reference figures,
 * NOT invented data — but they are a starting set to be verified and extended by
 * the operator. Work centres are set only where the distinta screenshot shows
 * them (materiale / fresa / manopopera). Idempotent (keyed on code).
 */
final class SignPriceListSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->items() as $item) {
            SignPriceListItem::query()->updateOrCreate(['code' => $item['code']], $item);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function items(): array
    {
        return [
            // --- Materials (brass sheets) — METALLI ITALIA SRL ---
            [
                'code' => 'OT63_L-CR_200010001',
                'name' => 'Lastra ottone OT63 lucido a specchio + PVC / CRUDO - MM 2000x1000x1 (1 Fg ≈ kg 17, €/kg 13,70)',
                'category_code' => 'material',
                'work_center_code' => 'material',
                'supplier_name' => 'METALLI ITALIA SRL',
                'unit_code' => 'mq',
                'unit_cost' => 116.4500,
            ],
            [
                'code' => 'OT63_L-CT_200010000,8',
                'name' => 'Lastra ottone OT63 lucido a specchio + PVC / COTTO - MM 2000x1000x0,8 (1 Fg ≈ kg 13,6, €/kg 14,90)',
                'category_code' => 'material',
                'work_center_code' => 'material',
                'supplier_name' => 'METALLI ITALIA SRL',
                'unit_code' => 'mq',
                'unit_cost' => 93.1600,
            ],

            // --- Machining (milling) — RETAIL SIGN SRL ---
            [
                'code' => '99900029',
                'name' => 'Taglio fresa ottone 1.0mm',
                'category_code' => 'machining',
                'work_center_code' => 'milling',
                'supplier_name' => 'RETAIL SIGN SRL',
                'unit_code' => 'mt',
                'unit_cost' => 2.1000,
            ],
            [
                'code' => '99900028',
                'name' => 'Taglio fresa ottone 0.8mm',
                'category_code' => 'machining',
                'work_center_code' => 'milling',
                'supplier_name' => 'RETAIL SIGN SRL',
                'unit_code' => 'mt',
                'unit_cost' => 1.4000,
            ],

            // --- Labour — RETAIL SIGN SRL ---
            [
                'code' => 'RS_SCAT_DIOR',
                'name' => 'Piegatura + stagnatura lettere scatolate (1min/cm, minimo 60 min/lettera)',
                'category_code' => 'labor',
                'work_center_code' => 'labor',
                'supplier_name' => 'RETAIL SIGN SRL',
                'unit_code' => 'min',
                'unit_cost' => 0.5000,
            ],
            [
                'code' => 'RS_file',
                'name' => 'Elaborazione file Retail Sign',
                'category_code' => 'labor',
                'work_center_code' => 'labor',
                'supplier_name' => 'RETAIL SIGN SRL',
                'unit_code' => 'min',
                'unit_cost' => 0.7000,
            ],

            // --- LED (STRIPLED MINI SERIES 2016 SMD) — Blueview Elec-Optic Tech Co. Ltd ---
            // No work centre: these strips never appear in a distinta screenshot,
            // so the operator assigns it rather than us guessing.
            ...$this->stripledItems(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function stripledItems(): array
    {
        $temperatures = ['27K' => '2700K', '30K' => '3000K', '35K' => '3500K', '40K' => '4000K', '50K' => '5000K'];
        $items = [];

        foreach ($temperatures as $suffix => $kelvin) {
            $items[] = [
                'code' => "FN-2016A-182-24-{$suffix}",
                'name' => "STRIPLED MINI SERIES 2016 SMD, PCB 5mm 182diodes/m 24V 9.36W/M {$kelvin} BIN:41S, 5m/roll, two ends with cable, white 3M sticker",
                'category_code' => 'led',
                'supplier_name' => 'Blueview Elec-Optic Tech Co. Ltd',
                'unit_code' => 'mt',
                'unit_cost' => 3.6500,
            ];
        }

        return $items;
    }
}
