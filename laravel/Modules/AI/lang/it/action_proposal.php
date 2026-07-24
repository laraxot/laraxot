<?php

declare(strict_types=1);

return [
    'label' => 'Proposta di azione AI',
    'plural_label' => 'Proposte di azione AI',
    'navigation_group' => 'Assistente AI',
    'fields' => [
        'section' => 'Dettagli proposta',
        'thread' => 'Conversazione',
        'type' => 'Tipo',
        'status' => 'Stato',
        'preview' => 'Anteprima',
        'error' => 'Errore',
        'confirmed_at' => 'Confermata il',
        'executed_at' => 'Eseguita il',
    ],
    'statuses' => [
        'pending' => 'In attesa',
        'cancelled' => 'Annullata',
        'confirmed' => 'Confermata',
        'executed' => 'Eseguita',
        'failed' => 'Fallita',
    ],
    'actions' => [
        'confirm' => 'Conferma',
        'cancel' => 'Annulla',
    ],
];
