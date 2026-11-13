<?php

declare(strict_types=1);

return [
    'list' => [
        'region' => [
            'aria_label' => 'Lista de incidencias',
        ],
        'intro' => [
            'description' => 'Consulta y filtra todas las incidencias del Ayuntamiento.',
        ],
    ],
    'filters' => [
        'group' => [
            'aria_label' => 'Filtros de incidencias',
        ],
        'all' => [
            'label' => 'Todas',
        ],
        'open' => [
            'label' => 'Abiertas',
        ],
        'closed' => [
            'label' => 'Cerradas',
        ],
    ],
    'items' => [
        'title' => [
            'title' => 'Incidencia #:number',
        ],
        'status' => [
            'label' => 'Estado:',
        ],
        'open' => [
            'label' => 'Abierta',
        ],
        'closed' => [
            'label' => 'Cerrada',
        ],
        'description' => [
            'description' => 'Descripción completa de la incidencia. Datos reales para el usuario autenticado.',
        ],
    ],
    'map' => [
        'show' => [
            'label' => 'Mostrar mapa (DivIcon #007A52)',
        ],
        'placeholder' => [
            'message' => 'Mapa Leaflet / OpenStreetMap — visualización de incidencias',
        ],
    ],
];
