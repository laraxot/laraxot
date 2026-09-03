<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Disegni',
        'group' => 'Carpenteria',
        'icon' => 'heroicon-o-document-magnifying-glass',
        'sort' => 25,
    ],
    'fields' => [
        'part_code' => [
            'label' => 'Codice disegno',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'bbox' => [
            'label' => 'Ingombro (mm)',
        ],
        'solid_count' => [
            'label' => 'Solidi',
        ],
        'cylinder_count' => [
            'label' => 'Superfici cilindriche',
        ],
        'is_assembly' => [
            'label' => 'Assieme',
        ],
        'estimates_count' => [
            'label' => 'Stime pezzi',
        ],
        'source_file' => [
            'label' => 'File',
        ],
        'cad_system' => [
            'label' => 'Sistema CAD',
        ],
        'created_at' => [
            'label' => 'Creato il',
        ],
    ],
];
