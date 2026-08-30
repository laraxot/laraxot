<?php

declare(strict_types=1);

/*
 * Solo valori di default: niente env() nei config di modulo (Larastan lo vieta
 * fuori dalla config/ di root).
 */
return [
    // Fallback markup applied when neither the estimate, its work centre nor the
    // material says otherwise. 0 = sell at cost.
    'default_markup_percent' => 25,

    // Steel density. Everything the customer buys (S235/S275/S355, DX51, COR-TEN)
    // sits at 7.85 kg/dm3; stainless and aluminium are per-material overrides on
    // the raw-material row.
    'default_density_kg_dm3' => 7.85,

    // A STEP solid is a boundary representation, not a mesh: the exact volume
    // cannot be computed without a tessellator. The weight is therefore derived
    // from the bounding box times an occupancy factor the operator can correct on
    // screen — a number they can sanity-check against the drawing, instead of a
    // black-box figure.
    'default_occupancy_percent' => 15.0,

    // Offcut allowance added on top of the net weight.
    'default_scrap_percent' => 12.0,

    // Below this the part is treated as sheet metal: the weight then comes from
    // footprint x thickness, which is exact for a flat blank.
    'flat_part_max_thickness_mm' => 30.0,

    // Work centres seeded on install. `hourly_rate` is the internal cost per hour,
    // `default_markup_percent` the margin applied to that centre's rows.
    'work_centers' => [
        ['code' => 'material', 'name_key' => 'material', 'is_internal' => false, 'hourly_rate' => 0.0, 'default_markup_percent' => 10.0, 'sort_order' => 10],
        ['code' => 'laser_cutting', 'name_key' => 'laser_cutting', 'is_internal' => true, 'hourly_rate' => 75.0, 'default_markup_percent' => 25.0, 'sort_order' => 20],
        ['code' => 'oxy_cutting', 'name_key' => 'oxy_cutting', 'is_internal' => false, 'hourly_rate' => 60.0, 'default_markup_percent' => 15.0, 'sort_order' => 30],
        ['code' => 'sawing', 'name_key' => 'sawing', 'is_internal' => true, 'hourly_rate' => 45.0, 'default_markup_percent' => 25.0, 'sort_order' => 40],
        ['code' => 'bending', 'name_key' => 'bending', 'is_internal' => true, 'hourly_rate' => 55.0, 'default_markup_percent' => 25.0, 'sort_order' => 50],
        ['code' => 'drilling', 'name_key' => 'drilling', 'is_internal' => true, 'hourly_rate' => 45.0, 'default_markup_percent' => 25.0, 'sort_order' => 60],
        ['code' => 'machining', 'name_key' => 'machining', 'is_internal' => true, 'hourly_rate' => 65.0, 'default_markup_percent' => 30.0, 'sort_order' => 70],
        ['code' => 'welding', 'name_key' => 'welding', 'is_internal' => true, 'hourly_rate' => 50.0, 'default_markup_percent' => 30.0, 'sort_order' => 80],
        ['code' => 'assembly', 'name_key' => 'assembly', 'is_internal' => true, 'hourly_rate' => 40.0, 'default_markup_percent' => 25.0, 'sort_order' => 90],
        ['code' => 'sandblasting', 'name_key' => 'sandblasting', 'is_internal' => false, 'hourly_rate' => 40.0, 'default_markup_percent' => 15.0, 'sort_order' => 100],
        ['code' => 'painting', 'name_key' => 'painting', 'is_internal' => false, 'hourly_rate' => 45.0, 'default_markup_percent' => 15.0, 'sort_order' => 110],
        ['code' => 'shipping', 'name_key' => 'shipping', 'is_internal' => false, 'hourly_rate' => 0.0, 'default_markup_percent' => 0.0, 'sort_order' => 120],
    ],

    // How the geometry read from the STEP file turns into machining minutes. Each
    // rule picks a driver from the geometry, multiplies it by `minutes_per_unit`
    // and adds `setup_minutes` once per part. `min_*` gates the rule off for parts
    // that do not need that step.
    'operation_rules' => [
        [
            'work_center_code' => 'laser_cutting',
            'driver' => 'cut_length_m',
            'minutes_per_unit' => 1.2,
            'setup_minutes' => 5.0,
            'only_flat_parts' => true,
        ],
        [
            'work_center_code' => 'oxy_cutting',
            'driver' => 'cut_length_m',
            'minutes_per_unit' => 3.5,
            'setup_minutes' => 8.0,
            'only_thick_parts' => true,
        ],
        [
            'work_center_code' => 'drilling',
            'driver' => 'hole_count',
            'minutes_per_unit' => 1.5,
            'setup_minutes' => 3.0,
        ],
        [
            'work_center_code' => 'bending',
            'driver' => 'bend_count',
            'minutes_per_unit' => 1.0,
            'setup_minutes' => 6.0,
            'only_flat_parts' => true,
        ],
        [
            'work_center_code' => 'welding',
            'driver' => 'solid_count',
            'minutes_per_unit' => 12.0,
            'setup_minutes' => 10.0,
            'min_driver_value' => 2.0,
        ],
        [
            'work_center_code' => 'painting',
            'driver' => 'surface_sqm',
            'minutes_per_unit' => 6.0,
            'setup_minutes' => 4.0,
        ],
    ],

    // How a bill of materials turns into machining minutes. Same shape as
    // `operation_rules`, but the drivers are totals across every piece of the
    // drawing rather than measurements of one lump.
    //
    // Square cuts and profiled cuts are separate rules on purpose: a bar sawn to
    // length and a plate whose outline has to be followed by a torch are not the
    // same job, and charging one at the price of the other is the quickest way to
    // lose a tender or a margin.
    'bill_of_materials_rules' => [
        [
            'work_center_code' => 'oxy_cutting',
            'driver' => 'profiled_cut_length_m',
            'minutes_per_unit' => 3.5,
            'setup_minutes' => 8.0,
        ],
        [
            'work_center_code' => 'sawing',
            'driver' => 'square_cut_count',
            'minutes_per_unit' => 4.0,
            'setup_minutes' => 5.0,
        ],
        [
            'work_center_code' => 'drilling',
            'driver' => 'hole_count',
            'minutes_per_unit' => 1.5,
            'setup_minutes' => 3.0,
        ],
        [
            'work_center_code' => 'welding',
            'driver' => 'piece_count',
            'minutes_per_unit' => 12.0,
            'setup_minutes' => 10.0,
            'min_driver_value' => 2.0,
        ],
        [
            'work_center_code' => 'assembly',
            'driver' => 'piece_count',
            'minutes_per_unit' => 3.0,
            'setup_minutes' => 15.0,
            'min_driver_value' => 2.0,
        ],
        [
            'work_center_code' => 'painting',
            'driver' => 'surface_sqm',
            'minutes_per_unit' => 6.0,
            'setup_minutes' => 4.0,
        ],
    ],

    // Where the demo material lives. The seeder reads the customer's real supplier
    // invoices from here instead of shipping a made-up price list.
    'demo' => [
        'invoice_path' => 'docs/meccanica-industriale/materiale/fatture',
        'drawing_path' => 'docs/meccanica-industriale/materiale/disegni',
    ],
];
