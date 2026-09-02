<?php

declare(strict_types=1);

return [
    'fields' => [
        'original_text' => [
            'label' => 'Testo',
            'tooltip' => 'Contenuto originale del commento',
            'helper_text' => '',
            'description' => '',
        ],
        'text' => [
            'label' => 'Testo renderizzato',
            'tooltip' => 'Corpo HTML elaborato',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable_type' => [
            'label' => 'Tipo oggetto',
            'tooltip' => 'Classe morph dell\'entità commentata',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable_id' => [
            'label' => 'ID oggetto',
            'tooltip' => 'Chiave primaria dell\'entità commentata',
            'helper_text' => '',
            'description' => '',
        ],
        'approved_at' => [
            'label' => 'Approvato il',
            'tooltip' => 'Timestamp approvazione moderazione',
            'helper_text' => '',
            'description' => '',
        ],
        'commentator_display' => [
            'label' => 'Autore',
            'tooltip' => 'Nome visualizzato del commentatore',
            'helper_text' => '',
            'description' => '',
        ],
        'moderation_status' => [
            'label' => 'Stato',
            'tooltip' => 'Stato di moderazione',
            'helper_text' => '',
            'description' => '',
        ],
        'commentator' => [
            'label' => 'Autore',
            'tooltip' => 'Utente che ha scritto il commento',
            'helper_text' => '',
            'description' => '',
        ],
        'commentable' => [
            'label' => 'Oggetto',
            'tooltip' => 'Entità a cui è collegato il commento',
            'helper_text' => '',
            'description' => '',
        ],
        'status' => [
            'label' => 'Stato',
            'tooltip' => 'Stato di moderazione',
            'helper_text' => '',
            'description' => '',
            'pending' => 'In attesa',
            'approved' => 'Approvato',
        ],
        'parent_id' => [
            'label' => 'Risposta a',
            'tooltip' => 'ID del commento padre',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
            'label' => 'Creato il',
            'tooltip' => 'Data di creazione',
            'helper_text' => '',
            'description' => '',
        ],
    ],
    'actions' => [
        'approve' => [
            'label' => 'Approva',
            'tooltip' => 'Approva questo commento',
        ],
        'reject' => [
            'label' => 'Rifiuta',
            'tooltip' => 'Elimina il commento rifiutato',
            'confirmation' => 'Confermi il rifiuto di questo commento?',
        ],
        'approve_bulk' => [
            'label' => 'Approva selezionati',
            'tooltip' => 'Approva i commenti in attesa selezionati',
        ],
        'reject_bulk' => [
            'label' => 'Rifiuta selezionati',
            'tooltip' => 'Rifiuta i commenti selezionati',
            'confirmation' => 'Confermi il rifiuto dei commenti selezionati?',
        ],
    ],
    'filters' => [
        'moderation' => [
            'label' => 'Moderazione',
            'tooltip' => 'Filtra per stato di approvazione',
            'pending' => 'In attesa',
            'approved' => 'Approvati',
        ],
    ],
    'label' => 'Commento',
    'plural_label' => 'Commenti',
    'navigation' => [
        'name' => 'Commenti',
        'plural' => 'Commenti',
        'group' => [
            'name' => 'Moderazione',
            'description' => 'Gestione commenti e reazioni',
        ],
        'label' => 'Commenti',
        'sort' => 40,
        'icon' => 'heroicon-o-chat-bubble-left-right',
    ],
];
