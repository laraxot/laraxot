<?php

declare(strict_types=1);

return [
    'title' => 'Reimposta password',
    'subtitle' => 'Inserisci il tuo indirizzo email per ricevere il link di reimpostazione password',
    'description' => 'Inserisci il tuo indirizzo email per ricevere il link di reimpostazione password',
    'email' => [
        'label' => 'Indirizzo email',
        'placeholder' => 'Inserisci la tua email',
    ],
    'submit' => [
        'label' => 'Invia link di reset password',
        'processing' => 'Invio in corso...',
    ],
    'email_sent' => [
        'title' => 'Email inviata con successo!',
        'message' => 'Ti abbiamo inviato un link per reimpostare la password. Controlla la tua casella di posta elettronica e segui le istruzioni.',
        'check_email' => 'Controlla email',
    ],
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
        'password' => [
            'label' => 'Nuova password',
            'placeholder' => 'Inserisci la nuova password',
            'requirements' => 'La password deve avere almeno 8 caratteri',
        ],
        'password_confirmation' => [
            'label' => 'Conferma nuova password',
            'placeholder' => 'Conferma la nuova password',
        ],
        'submit' => [
            'label' => 'Conferma nuova password',
            'processing' => 'Elaborazione in corso...',
        ],
    ],
    'actions' => [
        'back_to_login' => 'Torna al login',
        'request_new_link' => 'Richiedi un nuovo link',
        'send_another' => 'Invia un altro link',
        'try_again' => 'Riprova',
    ],
    'success' => [
        'title' => 'Password reimpostata con successo!',
        'message' => 'La tua password è stata aggiornata. Ora puoi accedere con la nuova password.',
        'redirect_notice' => 'Reindirizzamento automatico in corso...',
        'go_to_dashboard' => 'Vai alla dashboard',
        'go_to_login' => 'Vai al login',
    ],
    'errors' => [
        'title' => 'Errore nel reset della password',
        'invalid_token' => 'Il link di reset non è più valido o è scaduto.',
        'invalid_user' => 'Non è stato possibile trovare un utente con questo indirizzo email.',
        'generic' => 'Si è verificato un errore durante il reset della password. Riprova più tardi.',
        'possible_causes' => 'Possibili cause:',
        'causes' => [
            'expired_token' => 'Il link di reset è scaduto (valido per 60 minuti)',
            'invalid_email' => 'L\'indirizzo email non corrisponde a nessun account',
            'already_used' => 'Il link di reset è già stato utilizzato',
        ],
    ],
    'instructions' => [
        'title' => 'Istruzioni per il reset',
        'description' => 'Inserisci la tua email e la nuova password per completare il reset.',
    ],
    'security' => [
        'title' => 'Sicurezza',
        'note' => 'Il link di reset è valido per 60 minuti e può essere utilizzato una sola volta.',
    ],
    'help' => [
        'having_trouble' => 'Problemi con il reset?',
        'contact_support' => 'Contatta il supporto',
    ],
    'processing' => 'Elaborazione in corso...',
    'sending' => 'Invio in corso...',
];
