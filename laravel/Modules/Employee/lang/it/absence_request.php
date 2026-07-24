<?php

declare(strict_types=1);

return [
    'label' => 'Richiesta di assenza',
    'plural_label' => 'Richieste di assenza',
    'navigation_group' => 'Presenze e Assenze',
    'fields' => [
        'section' => 'Dettagli richiesta',
        'user' => 'Dipendente',
        'type' => 'Tipo',
        'status' => 'Stato',
        'starts_at' => 'Data inizio',
        'ends_at' => 'Data fine',
        'notes' => 'Note',
        'decided_by' => 'Approvato/Rifiutato da',
        'decided_at' => 'Data decisione',
    ],
    'types' => [
        'vacation' => 'Ferie',
        'leave' => 'Permesso',
        'sick' => 'Malattia',
        'injury' => 'Infortunio',
    ],
    'statuses' => [
        'pending' => 'In attesa',
        'approved' => 'Approvata',
        'rejected' => 'Rifiutata',
    ],
    'actions' => [
        'approve' => 'Approva',
        'reject' => 'Rifiuta',
    ],
];
