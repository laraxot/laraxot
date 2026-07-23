<?php

declare(strict_types=1);

return [
    'values' => [
        'mail' => [
            'label' => 'Mail',
            'color' => 'success',
            'icon' => 'heroicon-o-envelope',
            'description' => 'Canale email',
        ],
        'sms' => [
            'label' => 'SMS',
            'color' => 'info',
            'icon' => 'heroicon-o-chat-bubble-left-ellipsis',
            'description' => 'Canale SMS',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'color' => 'warning',
            'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
            'description' => 'Canale WhatsApp',
        ],
    ],
    'label' => 'Channel Enum',
    'plural_label' => 'Channel Enum (Plurale)',
    'navigation' => [
        'name' => 'Channel Enum',
        'plural' => 'Channel Enum',
        'group' => [
            'name' => 'General',
            'description' => 'General Settings',
        ],
        'label' => 'Channel Enum',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
            'label' => 'Data Creazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'updated_at' => [
            'label' => 'Ultima Modifica',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Channel Enum',
        ],
        'edit' => [
            'label' => 'Modifica Channel Enum',
        ],
        'delete' => [
            'label' => 'Elimina Channel Enum',
        ],
    ],
];
