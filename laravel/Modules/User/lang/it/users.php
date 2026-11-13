<?php

declare(strict_types=1);

// Etichette della tabella utenti (UsersTable / BaseUsersTable).
return [
    'fields' => [
<<<<<<< .merge_file_e3eCot
        'id' => [
            'label' => 'ID',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'first_name' => [
            'label' => 'Nome',
        ],
        'last_name' => [
            'label' => 'Cognome',
        ],
        'email' => [
            'label' => 'E-mail',
        ],
        'email_verified_at' => [
            'label' => 'E-mail verificata il',
        ],
        'is_active' => [
            'label' => 'Attivo',
        ],
        'is_otp' => [
            'label' => 'Password temporanea',
        ],
        'lang' => [
            'label' => 'Lingua',
        ],
        'current_team_id' => [
            'label' => 'Team attuale',
        ],
        'type' => [
            'label' => 'Tipo',
        ],
        'created_at' => [
            'label' => 'Creato il',
        ],
        'updated_at' => [
            'label' => 'Modificato il',
        ],
        'state' => [
            'label' => 'Stato',
        ],
        'password_expires_at' => [
            'label' => 'Password scade il',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout',
        ],
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
        ],
        'changePassword' => [
            'label' => 'changePassword',
            'icon' => 'changePassword',
            'tooltip' => 'changePassword',
        ],
        'deactivate' => [
            'label' => 'Disattiva',
            'icon' => 'deactivate',
            'tooltip' => 'deactivate',
        ],
        'edit' => [
            'tooltip' => 'edit',
            'icon' => 'edit',
            'label' => 'edit',
        ],
        'view' => [
            'tooltip' => 'view',
            'icon' => 'view',
            'label' => 'view',
        ],
        'verify_email' => [
            'tooltip' => 'verify_email',
            'icon' => 'verify_email',
            'label' => 'Verifica e-mail',
        ],
        'export' => [
            'label' => 'Esporta',
            'icon' => 'export',
            'tooltip' => 'export',
        ],
=======
        'name' => ['label' => 'Nome e cognome'],
        'email' => ['label' => 'Email'],
        'first_name' => ['label' => 'Nome'],
        'last_name' => ['label' => 'Cognome'],
        'is_active' => ['label' => 'Attivo'],
        'email_verified_at' => ['label' => 'Email verificata il'],
        'is_otp' => ['label' => 'Password temporanea'],
        'lang' => ['label' => 'Lingua'],
        'current_team_id' => ['label' => 'Team corrente'],
        'type' => ['label' => 'Tipo'],
        'state' => ['label' => 'Stato'],
        'password_expires_at' => ['label' => 'Scadenza password'],
        'id' => ['label' => 'ID'],
        'created_at' => ['label' => 'Creato il'],
        'updated_at' => ['label' => 'Aggiornato il'],
    ],
    'actions' => [
        'create' => ['label' => 'Nuovo utente'],
        'delete' => ['label' => 'Elimina'],
        'change_password' => ['label' => 'Cambia password', 'tooltip' => 'Imposta una nuova password'],
        'deactivate' => ['label' => 'Disattiva', 'tooltip' => 'Toglie l\'accesso: l\'utente viene eliminato (ripristinabile dal cestino)'],
        'edit' => ['label' => 'Modifica'],
        'view' => ['label' => 'Apri'],
        'verify_email' => ['label' => 'Segna email verificata'],
        'export' => ['label' => 'Esporta'],
>>>>>>> .merge_file_rGz1AD
    ],
];
