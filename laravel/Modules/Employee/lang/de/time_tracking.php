<?php

declare(strict_types=1);

return [
    'actions' => [
        'clock' => [
            'label' => 'Ein-/Ausstempeln',
        ],
        'viewTodayEntries' => [
            'label' => 'Heutige Einträge anzeigen',
        ],
    ],
    'tracker' => [
        'status' => [
            'active' => 'Sitzung aktiv',
            'completed' => 'Tag abgeschlossen',
            'waiting' => 'Warten auf Stempelung',
        ],
        'no_entries_today' => [
            'message' => 'Heute keine Stempelungen',
        ],
        'end_session' => [
            'label' => 'Sitzung beenden',
        ],
        'start_session' => [
            'label' => 'Sitzung starten',
        ],
        'entries_today' => [
            'description' => '{1} :count Stempelung heute|[2,*] :count Stempelungen heute',
        ],
    ],
    'card' => [
        'hours_worked_today' => [
            'label' => 'Heute gearbeitete Stunden',
        ],
        'last_entry' => [
            'label' => 'Letzte Stempelung',
        ],
        'hours_today' => [
            'label' => 'Stunden heute',
        ],
        'week' => [
            'label' => 'Woche',
        ],
        'month' => [
            'label' => 'Monat',
        ],
        'auto_refresh' => [
            'label' => 'Wird alle 30 Sekunden automatisch aktualisiert',
        ],
    ],
];
