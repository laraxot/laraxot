<?php

declare(strict_types=1);

return [
    'navigation' => 'F-Gas and register',

    'circuit' => 'Refrigerant circuit',
    'circuits' => 'Refrigerant circuits',
    'leak_check' => 'Leak check',
    'leak_checks' => 'Leak checks',

    'fields' => [
        'code' => 'Code',
        'customer' => 'Customer',
        'heating_system' => 'System',
        'description' => 'Description',
        'location' => 'Location',
        'refrigerant_code' => 'Refrigerant',
        'charge_kg' => 'Charge (kg)',
        'co2_equivalent_tonnes' => 'CO₂ equivalent (t)',
        'is_hermetically_sealed' => 'Hermetically sealed',
        'has_leak_detection_system' => 'Leak detection system',
        'check_interval_months' => 'Check interval (months)',
        'last_leak_check_on' => 'Last check',
        'next_leak_check_due_on' => 'Next check',
        'performed_on' => 'Performed on',
        'outcome' => 'Outcome',
        'method' => 'Method',
        'gas_added_kg' => 'Gas added (kg)',
        'gas_recovered_kg' => 'Gas recovered (kg)',
        'operator_certificate' => 'Operator certificate',
        'region' => 'Region',
        'registry' => 'Regional register',
        'registry_submitted_on' => 'Filed with the register on',
        'registry_reference' => 'Register reference',
    ],

    'outcomes' => [
        'no_leak' => 'No leak',
        'leak_found' => 'Leak found',
        'repaired' => 'Repaired',
    ],

    'methods' => [
        'direct' => 'Direct',
        'indirect' => 'Indirect',
    ],

    'exemptions' => [
        'below_threshold' => 'Under 5 t CO₂ equivalent: no leak-check duty',
        'hermetically_sealed' => 'Hermetically sealed under 10 t CO₂ equivalent: exempt',
        'unknown_refrigerant' => 'Refrigerant not recognised: the duty cannot be worked out until it is identified',
    ],

    'registry' => [
        'not_submitted' => 'To be filed',
        'submitted' => 'Filed',
        'no_registry' => 'No register is configured for this region',
        'manual_notice' => 'Filing with the regional register stays manual: this only tracks what has been filed and what has not.',
    ],
];
