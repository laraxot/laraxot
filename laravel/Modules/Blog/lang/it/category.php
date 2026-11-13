<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Category',
        'plural' => 'Categories',
        'group' => 'Contenuti',
        'sort' => 21,
        'icon' => 'heroicon-o-tag',
        'label' => 'Categorie',
    ],
    'show' => [
        'title' => 'Articoli della categoria ',
    ],
    'fields' => [
        'icon' => [
            'label' => 'Icona',
            'description' => '',
            'helper_text' => '',
            'placeholder' => '',
        ],
        'image' => [
            'label' => 'Immagine',
            'description' => '',
            'helper_text' => '',
            'placeholder' => '',
        ],
        'description' => [
            'label' => 'Descrizione',
            'description' => '',
            'helper_text' => '',
            'placeholder' => '',
        ],
        'parent_id' => [
            'label' => 'Voce superiore',
            'description' => '',
            'placeholder' => '',
            'helper_text' => '',
        ],
        'slug' => [
            'label' => 'Indirizzo (slug)',
            'placeholder' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'title' => [
            'label' => 'Titolo',
            'placeholder' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'parent' => [
            'title' => [
                'label' => 'Titolo',
            ],
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo',
        ],
        'activeLocale' => [
            'label' => 'Lingua',
        ],
        'logout' => [
            'tooltip' => '',
            'icon' => 'logout',
            'label' => 'logout',
        ],
    ],
];
