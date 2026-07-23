<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Autenticazione
    |--------------------------------------------------------------------------
    */
    'login' => [
        'title' => 'Accedi al tuo account',
        'back_to_home' => 'Torna alla home',
        'or' => 'oppure',
        'create_account' => 'crea un nuovo account',
        'forgot_password' => 'Hai dimenticato la password?',
        'back_to_login' => 'torna al login',
        'email' => 'Indirizzo email',
        'password' => 'Password',
        'remember_me' => 'Ricordami',
        'login_button' => 'Accedi',
    ],

    'register' => [
        'title' => 'Crea il tuo account',
        'welcome_message' => 'Benvenuto in <span class="font-bold"><nome progetto></span>',
        'description' => 'Crea il tuo account per accedere a tutti i servizi',
        'already_have_account' => 'Hai già un account?',
        'login_link' => 'accedi qui',
        'register_button' => 'Registrati',
        'already_registered' => 'Sei già registrato?',
        'doctor' => [
            'title' => 'Registrazione Odontoiatra',
            'subtitle' => 'Crea il tuo account professionale per accedere alla piattaforma <nome progetto>',
            'description' => 'Registrati come dottore per gestire i tuoi pazienti e appuntamenti',
            'welcome_message' => 'Benvenuto in <span class="font-bold"><nome progetto></span>',
            'info' => [
                'title' => 'Informazioni Professionali',
                'message' => 'Completa le informazioni professionali per verificare la tua identità e specializzazione',
            ],
            'specialization_label' => 'Specializzazione',
            'specialization_placeholder' => 'Seleziona la tua specializzazione',
            'license_number_label' => 'Numero di licenza',
            'license_number_placeholder' => 'Inserisci il tuo numero di licenza medica',
            'phone_label' => 'Numero di telefono',
            'phone_placeholder' => 'Inserisci il tuo numero di telefono',
            'address_label' => 'Indirizzo',
            'address_placeholder' => 'Inserisci il tuo indirizzo di lavoro',
            'terms_acceptance' => 'Accetto i termini e le condizioni',
            'privacy_policy' => 'Accetto la privacy policy',
            'register_button' => 'Registrati come Dottore',
        ],
        'patient' => [
            'title' => 'Registrazione Paziente',
            'subtitle' => 'Crea il tuo account per prenotare appuntamenti e gestire la tua salute',
            'description' => 'Registrati come paziente per accedere ai servizi sanitari',
            'welcome_message' => 'Benvenuto in <span class="font-bold"><nome progetto></span>',
            'phone_label' => 'Numero di telefono',
            'phone_placeholder' => 'Inserisci il tuo numero di telefono',
            'date_of_birth_label' => 'Data di nascita',
            'date_of_birth_placeholder' => 'Seleziona la tua data di nascita',
            'gender_label' => 'Sesso',
            'gender_placeholder' => 'Seleziona il tuo sesso',
            'terms_acceptance' => 'Accetto i termini e le condizioni',
            'privacy_policy' => 'Accetto la privacy policy',
            'register_button' => 'Registrati come Paziente',
        ],
        'admin' => [
            'title' => 'Registrazione Amministratore',
            'subtitle' => 'Crea il tuo account amministrativo per gestire la piattaforma',
            'description' => 'Registrati come amministratore per gestire utenti e configurazioni',
            'welcome_message' => 'Benvenuto in <span class="font-bold"><nome progetto></span>',
            'role_label' => 'Ruolo amministrativo',
            'role_placeholder' => 'Seleziona il tuo ruolo',
            'phone_label' => 'Numero di telefono',
            'phone_placeholder' => 'Inserisci il tuo numero di telefono',
            'terms_acceptance' => 'Accetto i termini e le condizioni',
            'privacy_policy' => 'Accetto la privacy policy',
            'register_button' => 'Registrati come Amministratore',
        ],
        'fields' => [
            'name_label' => 'Nome completo',
            'name_placeholder' => 'Inserisci il tuo nome completo',
            'email_label' => 'Indirizzo email',
            'email_placeholder' => 'Inserisci il tuo indirizzo email',
            'password_label' => 'Password',
            'password_placeholder' => 'Inserisci una password sicura',
            'password_confirmation_label' => 'Conferma password',
            'password_confirmation_placeholder' => 'Conferma la tua password',
        ],
        'validation' => [
            'name_required' => 'Il nome è obbligatorio',
            'email_required' => 'L\'email è obbligatoria',
            'email_email' => 'L\'email deve essere valida',
            'email_unique' => 'Questa email è già registrata',
            'password_required' => 'La password è obbligatoria',
            'password_min' => 'La password deve essere di almeno 8 caratteri',
            'password_confirmed' => 'Le password non coincidono',
            'terms_required' => 'Devi accettare i termini e le condizioni',
            'privacy_required' => 'Devi accettare la privacy policy',
        ],
        'success' => [
            'title' => 'Registrazione completata',
            'message' => 'Il tuo account è stato creato con successo',
            'verification_sent' => 'Ti abbiamo inviato un\'email di verifica',
            'check_email' => 'Controlla la tua casella di posta per verificare l\'account',
        ],
        'errors' => [
            'registration_failed' => 'Errore durante la registrazione',
            'email_already_exists' => 'Questa email è già registrata',
            'invalid_data' => 'Dati non validi',
        ],
    ],

    'password' => [
        'reset' => [
            'title' => 'Reimposta password',
            'subtitle' => 'Inserisci il tuo indirizzo email per ricevere il link di reimpostazione password',
            'description' => 'Inserisci il tuo indirizzo email per ricevere il link di reimpostazione password',
            'email_label' => 'Indirizzo email',
            'email_placeholder' => 'Inserisci la tua email',
            'send_button' => 'Invia link reimpostazione password',
            'back_to_login' => 'torna al login',
            'or' => 'oppure',
            'email_sent' => 'Link di reimpostazione password inviato!',
            'email_sent_title' => 'Email inviata con successo',
            'email_sent_message' => 'Controlla la tua casella di posta e segui le istruzioni per reimpostare la password.',
            'check_email_status' => 'Controlla email',
            'breadcrumb' => [
                'request' => 'Richiesta reset',
                'confirm' => 'Conferma password',
            ],
            'info' => [
                'security' => [
                    'title' => 'Sicurezza garantita',
                    'description' => 'Il link di reset è criptato e valido solo per 60 minuti. Nessuno può accedere al tuo account senza questo link.',
                ],
                'password' => [
                    'title' => 'Nuova password',
                    'description' => 'Scegli una password sicura con almeno 8 caratteri, includendo lettere, numeri e simboli.',
                ],
                'expiry' => [
                    'title' => 'Scadenza link',
                    'description' => 'Il link di reset scade automaticamente dopo 60 minuti per motivi di sicurezza.',
                ],
            ],
            'confirm' => [
                'title' => 'Imposta nuova password',
                'subtitle' => 'Inserisci la tua email e la nuova password per completare il reset',
            ],
            'help' => [
                'having_trouble' => 'Problemi con il reset?',
            ],
        ],
    ],

    'password-reset' => [
        'submit' => [
            'label' => 'Invia link di reset password',
        ],
    ],

    'confirm' => [
        'title' => 'Conferma password',
        'description' => 'Inserisci la tua password per confermare l\'identità',
    ],

    'new' => [
        'title' => 'Nuova password',
        'password_label' => 'Nuova password',
        'confirm_password_label' => 'Conferma nuova password',
        'update_button' => 'Aggiorna password',
    ],

    'verify' => [
        'title' => 'Verifica il tuo account',
        'description' => 'Ti abbiamo inviato un\'email di verifica. Controlla la tua casella di posta.',
        'resend_button' => 'Invia nuovamente',
        'change_email' => 'Cambia indirizzo email',
    ],

    'logout' => [
        'title' => 'Disconnessione',
        'message' => 'Sei stato disconnesso con successo',
        'redirect_message' => 'Reindirizzamento in corso...',
    ],

    'thank_you' => [
        'title' => 'Grazie per la registrazione',
        'message' => 'Il tuo account è stato creato con successo',
        'continue_button' => 'Continua',
    ],

    'actions' => [
        'processing' => 'Elaborazione in corso...',
        'sending' => 'Invio in corso...',
        'refresh' => 'Ricarica pagina',
    ],

    'errors' => [
        'loading_failed' => 'Errore di caricamento',
        'please_refresh' => 'Si è verificato un errore. Ricarica la pagina e riprova.',
    ],
];
