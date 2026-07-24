<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Quotation technical parameters & parametric pricing rules
|--------------------------------------------------------------------------
| Structured technical-survey parameters an operator can capture (by voice or
| from attachments) on a quotation, plus rules that turn those parameters into
| priced line items using the CURRENT catalog/price list. Only rule-governed
| lines are recomputed; manually added lines are preserved.
|
| Rules match products by SKU in Modules\Catalog products (fed by the listino /
| magazzino / METEL imports). A rule whose product SKU is missing is skipped.
|
| NOTE: this file is registered by XotBaseServiceProvider::registerConfig()
| under the "quotation.quotation_technical" key (namespaced by module), not the
| bare "quotation_technical" key — always read it via
| config('quotation.quotation_technical.*').
*/

return [

    // Fields shown/filled on the quotation. `key` is stored in technical_params.
    'params' => [
        ['key' => 'surface_m2', 'type' => 'number', 'unit' => 'm²'],
        ['key' => 'flue_length_m', 'type' => 'number', 'unit' => 'm'],
        ['key' => 'radiators_count', 'type' => 'number', 'unit' => 'pz'],
        ['key' => 'boiler_power_kw', 'type' => 'number', 'unit' => 'kW'],
        ['key' => 'thermostat_present', 'type' => 'bool'],
    ],

    // Parametric pricing rules.
    // active_when.op: 'gt' (numeric > value) | 'truthy' | 'falsey'
    // quantity_from: parameter key that provides the quantity, or use fixed 'quantity'
    'rules' => [
        [
            'product_sku' => 'THERMO-CANNA-1M',
            'active_when' => ['param' => 'flue_length_m', 'op' => 'gt', 'value' => 0],
            'quantity_from' => 'flue_length_m',
        ],
        [
            'product_sku' => 'THERMO-VALV-TERM',
            'active_when' => ['param' => 'radiators_count', 'op' => 'gt', 'value' => 0],
            'quantity_from' => 'radiators_count',
        ],
        [
            // Thermostat ABSENT -> add a programmable thermostat.
            'product_sku' => 'THERMO-CRONO',
            'active_when' => ['param' => 'thermostat_present', 'op' => 'falsey'],
            'quantity' => 1,
        ],
    ],
];
