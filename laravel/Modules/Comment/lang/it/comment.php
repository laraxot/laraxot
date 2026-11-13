<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Commenti',
        'group' => 'Moderazione',
        'icon' => 'heroicon-o-chat-bubble-left-right',
        'sort' => 48,
    ],
    'label' => 'Commento',
    'plural_label' => 'Commenti',
    'fields' => [
        'id' => [
            'label' => 'ID',
        ],
        'original_text' => [
            'label' => 'Testo',
        ],
        'commentator' => [
            'label' => 'Autore',
        ],
        'commentator_display' => [
            'label' => 'Autore',
        ],
        'commentable' => [
            'label' => 'Commento su',
        ],
        'commentable_type' => [
            'label' => 'Commento su',
        ],
        'status' => [
            'label' => 'Stato',
            'approved' => 'Approvato',
            'pending' => 'In attesa',
        ],
        'moderation' => [
            'label' => 'Moderazione',
        ],
        'moderation_status' => [
            'label' => 'Stato',
        ],
        'parent_id' => [
            'label' => 'Risposta a',
        ],
        'created_at' => [
            'label' => 'Scritto il',
        ],
    ],
    'filters' => [
        'moderation' => [
            'label' => 'Stato moderazione',
            'pending' => 'In attesa',
            'approved' => 'Approvati',
        ],
    ],
    'actions' => [
        'approve' => [
            'label' => 'Approva',
            'icon' => 'heroicon-o-check-circle',
            'tooltip' => 'Rendi visibile il commento',
        ],
        'reject' => [
            'label' => 'Rifiuta',
            'icon' => 'heroicon-o-x-circle',
            'tooltip' => 'Elimina il commento',
        ],
        'approve_bulk' => [
            'label' => 'Approva selezionati',
        ],
        'reject_bulk' => [
            'label' => 'Rifiuta selezionati',
        ],
        'logout' => [
            'tooltip' => '',
            'icon' => 'logout',
            'label' => 'Esci',
        ],
        'profile' => [
            'tooltip' => 'profile',
            'icon' => 'profile',
            'label' => 'profile',
        ],
        'reorderRecords' => [
            'tooltip' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'label' => 'reorderRecords',
        ],
    ],
];
