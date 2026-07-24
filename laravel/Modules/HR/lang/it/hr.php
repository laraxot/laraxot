<?php

declare(strict_types=1);

return [
    'navigation' => 'Risorse umane',
    'employee' => 'Dipendente',
    'employees' => 'Dipendenti',
    'absence_request' => 'Richiesta di assenza',
    'absence_requests' => 'Richieste di assenza',
    'documents' => 'Documenti',
    'medical_visits' => 'Visite mediche',
    'trainings' => 'Formazione',
    'fields' => [
        'user' => 'Utente collegato',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'email' => 'Email',
        'phone' => 'Telefono',
        'hired_at' => 'Data assunzione',
        'terminated_at' => 'Data cessazione',
        'notes' => 'Note',
        'is_active' => 'Attivo',
        'title' => 'Titolo',
        'kind' => 'Tipologia',
        'file' => 'File',
        'name' => 'Nome corso',
        'visited_at' => 'Data visita',
        'expires_at' => 'Scadenza',
        'completed_at' => 'Data completamento',
        'absence_type' => 'Tipo assenza',
        'starts_at' => 'Inizio',
        'ends_at' => 'Fine',
        'status' => 'Stato',
    ],
    'statuses' => [
        'pending' => 'In attesa',
        'approved' => 'Approvata',
        'rejected' => 'Rifiutata',
    ],
    'absence_types' => [
        'vacation' => 'Ferie',
        'leave' => 'Permesso',
        'sick' => 'Malattia',
        'injury' => 'Infortunio',
    ],
    'actions' => [
        'approve' => 'Approva',
        'reject' => 'Rifiuta',
        'download' => 'Scarica',
    ],
];
