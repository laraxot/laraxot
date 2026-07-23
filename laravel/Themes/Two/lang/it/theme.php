<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Navigazione
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'menu' => 'Menu',
        'close' => 'Chiudi',
        'home' => 'Home',
        'about' => 'Chi Siamo',
        'services' => 'Servizi',
        'contact' => 'Contatti',
        'login' => 'Accedi',
        'register' => 'Registrati',
        'profile' => 'Profilo',
        'logout' => 'Esci',
        'search' => 'Cerca',
        'toggle_menu' => 'Apri/Chiudi Menu',
        'toggle_search' => 'Apri/Chiudi Ricerca',
        'toggle_theme' => 'Cambia Tema',
        'back_to_top' => 'Torna su',
    ],

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */
    'form' => [
        'required' => 'Campo obbligatorio',
        'email' => 'Inserisci un indirizzo email valido',
        'min' => 'Il campo deve contenere almeno :min caratteri',
        'max' => 'Il campo non può superare :max caratteri',
        'submit' => 'Invia',
        'cancel' => 'Annulla',
        'save' => 'Salva',
        'delete' => 'Elimina',
        'edit' => 'Modifica',
        'view' => 'Visualizza',
        'search' => 'Cerca...',
        'filter' => 'Filtra',
        'reset' => 'Reimposta',
        'select' => 'Seleziona',
        'choose' => 'Scegli...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Messaggi
    |--------------------------------------------------------------------------
    */
    'messages' => [
        'success' => 'Operazione completata con successo',
        'error' => 'Si è verificato un errore',
        'warning' => 'Attenzione',
        'info' => 'Informazione',
        'loading' => 'Caricamento in corso...',
        'no_results' => 'Nessun risultato trovato',
        'confirm_delete' => 'Sei sicuro di voler eliminare questo elemento?',
        'yes' => 'Sì',
        'no' => 'No',
        'cookie_consent' => 'Questo sito utilizza i cookie per migliorare la tua esperienza',
        'accept' => 'Accetta',
        'decline' => 'Rifiuta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
    'footer' => [
        'copyright' => 'Tutti i diritti riservati',
        'privacy' => 'Privacy',
        'terms' => 'Termini e Condizioni',
        'cookies' => 'Cookie Policy',
        'social' => [
            'follow' => 'Seguici su',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin' => 'LinkedIn',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Errori
    |--------------------------------------------------------------------------
    */
    'errors' => [
        '404' => [
            'title' => 'Pagina non trovata',
            'message' => 'La pagina che stai cercando non esiste',
        ],
        '500' => [
            'title' => 'Errore del server',
            'message' => 'Si è verificato un errore interno del server',
        ],
        '403' => [
            'title' => 'Accesso negato',
            'message' => 'Non hai i permessi per accedere a questa pagina',
        ],
        'offline' => [
            'title' => 'Offline',
            'message' => 'Non sei connesso a Internet',
        ],
    ],
];
