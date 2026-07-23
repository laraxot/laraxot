<?php

declare(strict_types=1);

return [
    'accepted_appointments' => [
        'title' => 'Appuntamenti Accettati',
        'back_home' => 'Torna alla Home',
        'redirecting' => 'Reindirizzamento in corso...',
        'click_here' => 'clicca qui',
        'if_not_redirected' => 'Se non vieni reindirizzato automaticamente, :link.',
    ],
    'hero' => [
        'accepted_appointments' => [
            'title' => 'Appuntamenti Accettati',
            'description' => 'Visualizza tutti gli appuntamenti che sono stati confermati',
            'back_button' => [
                'label' => 'Torna indietro',
                'tooltip' => 'Ritorna alla pagina precedente',
            ],
        ],
        'pending_appointments' => [
            'title' => 'Appuntamenti in Attesa',
            'description' => 'Visualizza tutti gli appuntamenti in attesa di conferma',
        ],
        'completed_appointments' => [
            'title' => 'Appuntamenti Conclusi',
            'description' => 'Visualizza tutti gli appuntamenti che sono stati completati',
        ],
        'rejected_appointments' => [
            'title' => 'Appuntamenti Rifiutati',
            'description' => 'Visualizza tutti gli appuntamenti che sono stati rifiutati',
        ],
        'entry_appointments' => [
            'title' => 'Appuntamenti in Entrata',
            'description' => 'Visualizza tutti i nuovi appuntamenti richiesti',
        ],
    ],
    'states' => [
        'pending' => 'In attesa',
        'confirmed' => 'Confermato',
        'scheduled' => 'Programmato',
        'in_progress' => 'In corso',
        'completed' => 'Completato',
        'cancelled' => 'Annullato',
        'rejected' => 'Rifiutato',
        'no_show' => 'Non presentato',
        'rescheduled' => 'Riprogrammato',
    ],
];
