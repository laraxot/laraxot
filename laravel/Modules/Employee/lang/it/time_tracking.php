<?php

declare(strict_types=1);

return [
    'actions' => [
        'clock' => [
            'label' => 'clock',
        ],
        'viewTodayEntries' => [
            'label' => 'viewTodayEntries',
        ],
    ],
    'tracker' => [
        'status' => [
            'active' => 'Sessione Attiva',
            'completed' => 'Giornata Completata',
            'waiting' => 'In Attesa di Timbratura',
        ],
        'no_entries_today' => [
            'message' => 'Nessuna timbratura oggi',
        ],
        'end_session' => [
            'label' => 'Termina sessione',
        ],
        'start_session' => [
            'label' => 'Inizia sessione',
        ],
        'entries_today' => [
            'description' => '{1} :count timbratura oggi|[2,*] :count timbrature oggi',
        ],
    ],
    'card' => [
        'hours_worked_today' => [
            'label' => 'Ore lavorate oggi',
        ],
        'last_entry' => [
            'label' => 'Ultima timbratura',
        ],
        'hours_today' => [
            'label' => 'Ore oggi',
        ],
        'week' => [
            'label' => 'Settimana',
        ],
        'month' => [
            'label' => 'Mese',
        ],
        'auto_refresh' => [
            'label' => 'Aggiornato automaticamente ogni 30 secondi',
        ],
    ],
];
