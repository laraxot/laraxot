<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Ore di Lavoro',
        'group' => 'Gestione Dipendenti',
    ],
    'resource' => [
        'label' => 'Ora di Lavoro',
        'plural_label' => 'Ore di Lavoro',
        'navigation_label' => 'Ore di Lavoro',
    ],
    'fields' => [
        'employee_id' => [
            'label' => 'Dipendente',
            'placeholder' => 'Seleziona dipendente',
            'help' => 'Il dipendente a cui appartiene questa voce di orario di lavoro',
        ],
        'type' => [
            'label' => 'Tipo di Voce',
            'placeholder' => 'Seleziona tipo di voce',
            'help' => 'Tipo di voce orario di lavoro',
            'options' => [
                'clock_in' => 'Entrata',
                'clock_out' => 'Uscita',
                'break_start' => 'Inizio Pausa',
                'break_end' => 'Fine Pausa',
            ],
        ],
        'timestamp' => [
            'label' => 'Data e Ora',
            'placeholder' => 'Seleziona data e ora',
            'help' => 'Data e ora della voce orario di lavoro',
        ],
        'location_lat' => [
            'label' => 'Latitudine',
            'placeholder' => 'Latitudine GPS',
            'help' => 'Coordinata latitudine GPS',
        ],
        'location_lng' => [
            'label' => 'Longitudine',
            'placeholder' => 'Longitudine GPS',
            'help' => 'Coordinata longitudine GPS',
        ],
        'location_name' => [
            'label' => 'Nome Località',
            'placeholder' => 'Ufficio, Casa, Cliente...',
            'help' => 'Nome descrittivo della località',
        ],
        'device_info' => [
            'label' => 'Informazioni Dispositivo',
            'placeholder' => 'Dettagli dispositivo',
            'help' => 'Informazioni sul dispositivo utilizzato per questa voce',
        ],
        'photo_path' => [
            'label' => 'Foto',
            'placeholder' => 'Carica foto',
            'help' => 'Foto opzionale di verifica per questa voce',
        ],
        'status' => [
            'label' => 'Stato',
            'placeholder' => 'Seleziona stato',
            'help' => 'Stato di approvazione di questa voce',
            'options' => [
                'pending' => 'In Attesa',
                'approved' => 'Approvato',
                'rejected' => 'Rifiutato',
            ],
        ],
        'approved_by' => [
            'label' => 'Approvato Da',
            'placeholder' => 'Seleziona approvatore',
            'help' => 'Utente che ha approvato questa voce',
        ],
        'approved_at' => [
            'label' => 'Approvato Il',
            'placeholder' => 'Data approvazione',
            'help' => 'Data e ora quando questa voce è stata approvata',
        ],
        'notes' => [
            'label' => 'Note',
            'placeholder' => 'Aggiungi note opzionali...',
            'help' => 'Note opzionali per questa voce',
        ],
        'created_at' => [
            'label' => 'Creato il',
        ],
        'updated_at' => [
            'label' => 'Aggiornato il',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Ora di Lavoro',
            'success' => 'Voce orario di lavoro creata con successo',
            'error' => 'Impossibile creare la voce orario di lavoro',
        ],
        'edit' => [
            'label' => 'Modifica Ora di Lavoro',
            'success' => 'Voce orario di lavoro aggiornata con successo',
            'error' => 'Impossibile aggiornare la voce orario di lavoro',
        ],
        'delete' => [
            'label' => 'Elimina Ora di Lavoro',
            'success' => 'Voce orario di lavoro eliminata con successo',
            'error' => 'Impossibile eliminare la voce orario di lavoro',
            'confirmation' => 'Sei sicuro di voler eliminare questa voce orario di lavoro?',
        ],
        'view' => [
            'label' => 'Visualizza Ora di Lavoro',
        ],
        'timeclock' => [
            'label' => 'Timbratura',
        ],
        'clock_in' => [
            'label' => 'Entra',
            'success' => 'Entrata registrata con successo',
            'error' => 'Impossibile registrare l\'entrata',
        ],
        'clock_out' => [
            'label' => 'Esci',
            'success' => 'Uscita registrata con successo',
            'error' => 'Impossibile registrare l\'uscita',
        ],
        'break_start' => [
            'label' => 'Inizia Pausa',
            'success' => 'Pausa iniziata con successo',
            'error' => 'Impossibile iniziare la pausa',
        ],
        'break_end' => [
            'label' => 'Termina Pausa',
            'success' => 'Pausa terminata con successo',
            'error' => 'Impossibile terminare la pausa',
        ],
        'refresh' => [
            'label' => 'Aggiorna',
        ],
        'back_to_list' => [
            'label' => 'Torna alle Ore di Lavoro',
        ],
        'view_all_entries' => [
            'label' => 'Visualizza Tutte le Voci',
        ],
    ],
    'filters' => [
        'employee' => [
            'label' => 'Dipendente',
        ],
        'entry_type' => [
            'label' => 'Tipo di Voce',
        ],
        'date_range' => [
            'label' => 'Intervallo di Date',
            'from_date' => 'Da Data',
            'to_date' => 'A Data',
        ],
        'today' => [
            'label' => 'Solo Oggi',
        ],
    ],
    'tabs' => [
        'all' => 'Tutte le Voci',
        'today' => 'Oggi',
        'this_week' => 'Questa Settimana',
        'clock_in' => 'Entrate',
        'clock_out' => 'Uscite',
    ],
    'pages' => [
        'timeclock' => [
            'title' => 'Timbratura Dipendenti',
            'subtitle' => 'Traccia le tue ore di lavoro con un semplice click',
            'heading' => 'Timbratura',
        ],
        'dashboard' => [
            'title' => 'Dashboard Ore di Lavoro',
            'subtitle' => 'Panoramica delle ore di lavoro e statistiche',
        ],
    ],
    'widgets' => [
        'stats' => [
            'today' => [
                'label' => 'Oggi',
            ],
            'this_week' => [
                'label' => 'Questa Settimana',
            ],
            'avg_day' => [
                'label' => 'Media/Giorno',
            ],
            'work_days' => [
                'label' => 'Giorni Lavorativi',
            ],
        ],
        'progress' => [
            'weekly_progress' => 'Progresso Settimanale',
            'target_hours' => ':percentage% di :target h obiettivo',
        ],
        'breakdown' => [
            'daily_breakdown' => 'Ripartizione Giornaliera',
            'weekly_breakdown' => 'Ripartizione Settimanale',
        ],
        'recent_entries' => [
            'title' => 'Voci Recenti',
            'empty_state' => 'Nessuna voce recente',
        ],
    ],
    'status' => [
        'not_clocked_in' => 'Non Timbrato',
        'clocked_in' => 'Timbrato in Entrata',
        'on_break' => 'In Pausa',
        'clocked_out' => 'Timbrato in Uscita',
    ],
    'messages' => [
        'validation' => [
            'invalid_sequence' => 'Sequenza di voci non valida. Ultima voce: :last_entry. Azione prevista: :expected_action',
            'duplicate_entry' => 'Esiste già una voce con lo stesso timestamp e tipo per questo dipendente.',
            'outside_working_hours' => 'La timbratura è disponibile solo tra le :start e le :end',
            'invalid_time' => 'Gli orari di lavoro devono essere tra le :start e le :end',
        ],
        'success' => [
            'entry_recorded' => 'Registrato con successo: :action',
            'data_refreshed' => 'Dati aggiornati con successo',
        ],
        'error' => [
            'user_not_found' => 'Utente non trovato',
            'invalid_action' => 'Questa azione non è valida in base al tuo stato attuale',
            'failed_to_record' => 'Impossibile registrare la voce oraria: :error',
        ],
        'empty_states' => [
            'no_entries_today' => 'Nessuna voce registrata oggi',
            'no_recent_entries' => 'Nessuna voce recente',
        ],
    ],
    'summary' => [
        'total_hours_worked' => 'Ore Totali Lavorate',
        'current_status' => 'Stato Attuale',
        'total_entries' => 'Voci Totali',
        'todays_summary' => 'Riepilogo di Oggi',
        'todays_entries' => 'Voci di Oggi',
        'last_entry' => 'Ultima voce: :type alle :time',
    ],
    'quick_actions' => [
        'title' => 'Azioni Rapide',
        'refresh_data' => 'Aggiorna Dati',
        'time_clock' => 'Timbratura',
        'view_all' => 'Visualizza Tutte le Voci',
    ],
];
