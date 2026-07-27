<?php

declare(strict_types=1);

// AI translations — LangServiceProvider SSoT (never ->label() in Filament PHP).
// Canon: Modules/AI/docs/wiki — domain i18n only.
// File: lang/it/banner.php
return [
    'navigation' => [
        'name' => 'Banner',
        'plural' => 'Banners',
        'group' => [
            'name' => 'Content',
        ],
        'sort' => 28,
        'icon' => 'heroicon-o-rectangle-stack',
        'label' => 'Banner',
    ],
    'fields' => [
        'id' => [
            'label' => 'Id',
        ],
        'title' => [
            'label' => 'Titolo',
        ],
        'category' => [
            'title' => [
                'label' => 'Categoria abbinata',
            ],
        ],
        'image' => [
            'label' => 'Immagine',
        ],
        'file' => [
            'label' => 'file',
        ],
        'fileContent' => [
            'label' => 'fileContent',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
        ],
        'import' => [
            'label' => 'import',
        ],
    ],
];
