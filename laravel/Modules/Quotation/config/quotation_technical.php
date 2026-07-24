<?php

declare(strict_types=1);

return [
    /*
     * Structured technical survey parameters captured by voice/attachments and
     * used to drive the parametric recompute of rule-governed line items.
     * Each definition: key, type (text|number|bool), label.
     */
    'params' => [
        [
            'key' => 'surface_sqm',
            'type' => 'number',
            'label' => 'Superficie (m²)',
        ],
        [
            'key' => 'flue_length_m',
            'type' => 'number',
            'label' => 'Lunghezza canna fumaria (m)',
        ],
        [
            'key' => 'has_thermostat',
            'type' => 'bool',
            'label' => 'Termostato presente',
        ],
    ],

    /*
     * Rules mapping technical params to automatic line item adjustments.
     * Empty by default: project-specific rules are configured per deployment.
     */
    'rules' => [],
];
