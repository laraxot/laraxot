<?php

declare(strict_types=1);

/*
 * Solo valori di default: niente env() nei config di modulo (Larastan lo vieta
 * fuori dalla config/ di root).
 */
return [
    // Fallback markup used when neither the catalog item, its work centre nor
    // its price list says otherwise. 0 = sell at cost.
    'default_markup_percent' => 0,

    // Units whose quantity is driven by the piece of material consumed (B x H in
    // millimetres) instead of being typed directly. Mirrors the customer's
    // distinta, where only the square-metre rows fill the B/H columns.
    'surface_unit_codes' => ['mq'],

    // Work centres seeded on install. Granularity follows the customer's
    // distinta, which groups by cost centre (materiale, fresa, manopopera), not
    // by machine — their production board splits milling further into Meca and
    // Zund, which is a separate question still open with them.
    'work_centers' => [
        ['code' => 'material', 'name_key' => 'material', 'is_internal' => true, 'sort_order' => 10],
        ['code' => 'milling', 'name_key' => 'milling', 'is_internal' => true, 'sort_order' => 20],
        ['code' => 'waterjet', 'name_key' => 'waterjet', 'is_internal' => true, 'sort_order' => 30],
        ['code' => 'jigs', 'name_key' => 'jigs', 'is_internal' => true, 'sort_order' => 40],
        ['code' => 'blockout', 'name_key' => 'blockout', 'is_internal' => true, 'sort_order' => 50],
        ['code' => 'labor', 'name_key' => 'labor', 'is_internal' => true, 'sort_order' => 60],
        ['code' => 'file_setup', 'name_key' => 'file_setup', 'is_internal' => true, 'sort_order' => 70],
        ['code' => 'polishing', 'name_key' => 'polishing', 'is_internal' => false, 'sort_order' => 80],
        ['code' => 'galvanic', 'name_key' => 'galvanic', 'is_internal' => false, 'sort_order' => 90],
        ['code' => 'painting', 'name_key' => 'painting', 'is_internal' => false, 'sort_order' => 100],
        ['code' => 'packaging', 'name_key' => 'packaging', 'is_internal' => true, 'sort_order' => 110],
        ['code' => 'shipping', 'name_key' => 'shipping', 'is_internal' => false, 'sort_order' => 120],
    ],

    // Which supplier-list category each drawing component role is costed
    // against. null => the role is not costed (holes/text/dimension are drawing
    // helpers). Used by the artwork-extraction path only.
    'role_to_category' => [
        'body' => 'material',
        'face' => 'material',
        'cutting' => 'machining',
        'led' => 'led',
        'cable' => 'cable',
        'fixing' => 'fixing',
        'paint' => 'paint',
        'holes' => null,
        'text' => null,
        'dimension' => null,
        'other' => null,
    ],
];
