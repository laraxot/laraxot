<?php

declare(strict_types=1);

return [
    'list' => [
        'region' => [
            'aria_label' => 'Reports list',
        ],
        'intro' => [
            'description' => 'View and filter all the Municipality reports.',
        ],
    ],
    'filters' => [
        'group' => [
            'aria_label' => 'Report filters',
        ],
        'all' => [
            'label' => 'All',
        ],
        'open' => [
            'label' => 'Open',
        ],
        'closed' => [
            'label' => 'Closed',
        ],
    ],
    'items' => [
        'title' => [
            'title' => 'Report #:number',
        ],
        'status' => [
            'label' => 'Status:',
        ],
        'open' => [
            'label' => 'Open',
        ],
        'closed' => [
            'label' => 'Closed',
        ],
        'description' => [
            'description' => 'Full description of the report. Real data for the authenticated user.',
        ],
    ],
    'map' => [
        'show' => [
            'label' => 'Show map (DivIcon #007A52)',
        ],
        'placeholder' => [
            'message' => 'Leaflet / OpenStreetMap map — reports view',
        ],
    ],
];
