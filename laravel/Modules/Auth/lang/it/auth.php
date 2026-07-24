<?php

declare(strict_types=1);

return [
    'navigation' => 'Utenti',
    'user' => 'Utente',
    'users' => 'Utenti',
    'invalid_credentials' => 'Credenziali non valide.',
    'no_role_assigned' => 'Nessun ruolo assegnato.',
    'fields' => [
        'name' => 'Nome',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Conferma password',
        'roles' => 'Ruoli',
        'approval_status' => 'Stato approvazione',
    ],
    'hierarchy' => [
        'manager' => 'Responsabile',
        'manager_hint' => "L'utente scelto vedrà anche il lavoro dei collaboratori assegnati.",
        'none' => 'Nessuno',
    ],
    'status' => [
        'pending' => 'In attesa',
        'approved' => 'Approvato',
    ],
    'actions' => [
        'approve' => 'Approva',
    ],
    'two_factor' => [
        'title' => 'Verifica in due passaggi',
        'code_sent' => 'Ti abbiamo inviato un codice via email.',
        'invalid_code' => 'Codice non valido o scaduto.',
    ],
];
