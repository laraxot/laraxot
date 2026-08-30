<?php

declare(strict_types=1);

return [
    'navigation' => 'Boiler maintenance',

    'system' => 'Heating system',
    'systems' => 'Heating systems',
    'boilers' => 'Boilers / generators',
    'controls' => 'Controls & maintenance',

    'fields' => [
        'code' => 'Code',
        'customer' => 'Customer',
        'site' => 'Site/Location',
        'description' => 'Description',
        'fuel_type' => 'Fuel',
        'total_power_kw' => 'Total power',
        'plant_registry_code' => 'Plant registry code',
        'installation_date' => 'Installation date',
        'is_active' => 'Active',
        'notes' => 'Notes',
        'boilers' => 'Boilers',
        'status' => 'Status',

        'brand' => 'Brand',
        'model' => 'Model',
        'serial_number' => 'Serial number',
        'generator_type' => 'Generator type',
        'power_kw' => 'Power',
        'maintenance_interval_months' => 'Maintenance interval (months)',
        'efficiency_check_interval_months' => 'Efficiency check interval (months)',
        'next_maintenance' => 'Next maintenance',
        'next_efficiency' => 'Next efficiency control',
        'next_due_on' => 'Next due',

        'control_type' => 'Control type',
        'performed_on' => 'Performed on',
        'outcome' => 'Outcome',
        'efficiency_percent' => 'Efficiency',
        'flue_gas_temp' => 'Flue gas temperature',
        'smoke_index' => 'Smoke index',
        'bollino_code' => 'Sticker code',
        'bollino' => 'Sticker',
        'scheduled_at' => 'Scheduled at',
        'technician' => 'Technician',
    ],

    'fuels' => [
        'metano' => 'Natural gas',
        'gpl' => 'LPG',
        'gasolio' => 'Diesel',
        'biomassa' => 'Biomass',
        'altro' => 'Other',
    ],

    'generators' => [
        'standard' => 'Standard',
        'condensazione' => 'Condensing',
        'pompa_calore' => 'Heat pump',
        'ibrido' => 'Hybrid',
        'altro' => 'Other',
    ],

    'control_types' => [
        'maintenance' => 'Maintenance',
        'efficiency_check' => 'Efficiency control (RCEE)',
    ],

    'outcomes' => [
        'conforme' => 'Compliant',
        'non_conforme' => 'Non-compliant',
    ],

    'actions' => [
        'add_boiler' => 'Add boiler',
        'register_control' => 'Register control',
        'control_registered' => 'Control recorded and due date updated.',
        'generate_intervention' => 'Generate intervention',
        'intervention_generated' => 'Scheduled intervention created.',
        'download_rcee' => 'Download RCEE (PDF)',
    ],

    'deadlines' => [
        'title' => 'Maintenance deadlines',
        'empty' => 'No deadlines to show.',
    ],

    'status' => [
        'overdue' => 'Overdue',
        'expiring' => 'Expiring',
        'ok' => 'Compliant',
        'unknown' => 'To plan',
    ],
];
