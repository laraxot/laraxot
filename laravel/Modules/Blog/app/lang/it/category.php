<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Category',
        'plural' => 'Categories',
        'group' => [
            'name' => 'Content',
        ],
    ],
    'show' => [
        'title' => 'Articoli della categoria ',
    ],
    'fields' => [
        'icon' => [
            'label' => 'icon',
        ],
        'image' => [
            'label' => 'image',
        ],
        'description' => [
            'label' => 'description',
        ],
        'parent_id' => [
            'label' => 'parent_id',
        ],
        'slug' => [
            'label' => 'slug',
        ],
        'title' => [
            'label' => 'title',
        ],
        'parent' => [
            'title' => [
                'label' => 'parent.title',
            ],
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
        ],
        'activeLocale' => [
            'label' => 'activeLocale',
        ],
    ],
];
