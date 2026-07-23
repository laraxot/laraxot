<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Arbeitszeiten',
        'group' => 'Mitarbeiterverwaltung',
    ],
    'resource' => [
        'label' => 'Arbeitsstunde',
        'plural_label' => 'Arbeitszeiten',
        'navigation_label' => 'Arbeitszeiten',
    ],
    'fields' => [
        'employee_id' => [
            'label' => 'Mitarbeiter',
            'placeholder' => 'Mitarbeiter auswählen',
            'help' => 'Der Mitarbeiter, dem dieser Arbeitsstunden-Eintrag gehört',
        ],
        'type' => [
            'label' => 'Eintragstyp',
            'placeholder' => 'Eintragstyp auswählen',
            'help' => 'Art des Arbeitsstunden-Eintrags',
            'options' => [
                'clock_in' => 'Einstempeln',
                'clock_out' => 'Ausstempeln',
                'break_start' => 'Pausenbeginn',
                'break_end' => 'Pausenende',
            ],
        ],
        'timestamp' => [
            'label' => 'Datum und Uhrzeit',
            'placeholder' => 'Datum und Uhrzeit auswählen',
            'help' => 'Datum und Uhrzeit des Arbeitsstunden-Eintrags',
        ],
        'location_lat' => [
            'label' => 'Breitengrad',
            'placeholder' => 'GPS-Breitengrad',
            'help' => 'GPS-Breitengrad-Koordinate',
        ],
        'location_lng' => [
            'label' => 'Längengrad',
            'placeholder' => 'GPS-Längengrad',
            'help' => 'GPS-Längengrad-Koordinate',
        ],
        'location_name' => [
            'label' => 'Standortname',
            'placeholder' => 'Büro, Zuhause, Kunde...',
            'help' => 'Beschreibender Name des Standorts',
        ],
        'device_info' => [
            'label' => 'Geräteinformationen',
            'placeholder' => 'Gerätedetails',
            'help' => 'Informationen über das für diesen Eintrag verwendete Gerät',
        ],
        'photo_path' => [
            'label' => 'Foto',
            'placeholder' => 'Foto hochladen',
            'help' => 'Optionales Verifikationsfoto für diesen Eintrag',
        ],
        'status' => [
            'label' => 'Status',
            'placeholder' => 'Status auswählen',
            'help' => 'Genehmigungsstatus dieses Eintrags',
            'options' => [
                'pending' => 'Ausstehend',
                'approved' => 'Genehmigt',
                'rejected' => 'Abgelehnt',
            ],
        ],
        'approved_by' => [
            'label' => 'Genehmigt von',
            'placeholder' => 'Genehmiger auswählen',
            'help' => 'Benutzer der diesen Eintrag genehmigt hat',
        ],
        'approved_at' => [
            'label' => 'Genehmigt am',
            'placeholder' => 'Genehmigungsdatum',
            'help' => 'Datum und Uhrzeit der Genehmigung dieses Eintrags',
        ],
        'notes' => [
            'label' => 'Notizen',
            'placeholder' => 'Optionale Notizen hinzufügen...',
            'help' => 'Optionale Notizen für diesen Eintrag',
        ],
        'created_at' => [
            'label' => 'Erstellt am',
        ],
        'updated_at' => [
            'label' => 'Aktualisiert am',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Arbeitsstunde erstellen',
            'success' => 'Arbeitsstunden-Eintrag erfolgreich erstellt',
            'error' => 'Arbeitsstunden-Eintrag konnte nicht erstellt werden',
        ],
        'edit' => [
            'label' => 'Arbeitsstunde bearbeiten',
            'success' => 'Arbeitsstunden-Eintrag erfolgreich aktualisiert',
            'error' => 'Arbeitsstunden-Eintrag konnte nicht aktualisiert werden',
        ],
        'delete' => [
            'label' => 'Arbeitsstunde löschen',
            'success' => 'Arbeitsstunden-Eintrag erfolgreich gelöscht',
            'error' => 'Arbeitsstunden-Eintrag konnte nicht gelöscht werden',
            'confirmation' => 'Sind Sie sicher, dass Sie diesen Arbeitsstunden-Eintrag löschen möchten?',
        ],
        'view' => [
            'label' => 'Arbeitsstunde anzeigen',
        ],
        'timeclock' => [
            'label' => 'Zeiterfassung',
        ],
        'clock_in' => [
            'label' => 'Einstempeln',
            'success' => 'Einstempeln erfolgreich aufgezeichnet',
            'error' => 'Einstempeln konnte nicht aufgezeichnet werden',
        ],
        'clock_out' => [
            'label' => 'Ausstempeln',
            'success' => 'Ausstempeln erfolgreich aufgezeichnet',
            'error' => 'Ausstempeln konnte nicht aufgezeichnet werden',
        ],
        'break_start' => [
            'label' => 'Pause beginnen',
            'success' => 'Pause erfolgreich begonnen',
            'error' => 'Pause konnte nicht begonnen werden',
        ],
        'break_end' => [
            'label' => 'Pause beenden',
            'success' => 'Pause erfolgreich beendet',
            'error' => 'Pause konnte nicht beendet werden',
        ],
        'refresh' => [
            'label' => 'Aktualisieren',
        ],
        'back_to_list' => [
            'label' => 'Zurück zu Arbeitszeiten',
        ],
        'view_all_entries' => [
            'label' => 'Alle Einträge anzeigen',
        ],
    ],
    'filters' => [
        'employee' => [
            'label' => 'Mitarbeiter',
        ],
        'entry_type' => [
            'label' => 'Eintragstyp',
        ],
        'date_range' => [
            'label' => 'Datumsbereich',
            'from_date' => 'Von Datum',
            'to_date' => 'Bis Datum',
        ],
        'today' => [
            'label' => 'Nur heute',
        ],
    ],
    'tabs' => [
        'all' => 'Alle Einträge',
        'today' => 'Heute',
        'this_week' => 'Diese Woche',
        'clock_in' => 'Einstempelungen',
        'clock_out' => 'Ausstempelungen',
    ],
    'pages' => [
        'timeclock' => [
            'title' => 'Mitarbeiter-Zeiterfassung',
            'subtitle' => 'Verfolgen Sie Ihre Arbeitszeiten mit einem einfachen Klick',
            'heading' => 'Zeiterfassung',
        ],
        'dashboard' => [
            'title' => 'Arbeitszeiten-Dashboard',
            'subtitle' => 'Überblick über Arbeitszeiten und Statistiken',
        ],
    ],
    'widgets' => [
        'stats' => [
            'today' => [
                'label' => 'Heute',
            ],
            'this_week' => [
                'label' => 'Diese Woche',
            ],
            'avg_day' => [
                'label' => 'Durchschn./Tag',
            ],
            'work_days' => [
                'label' => 'Arbeitstage',
            ],
        ],
        'progress' => [
            'weekly_progress' => 'Wöchentlicher Fortschritt',
            'target_hours' => ':percentage% von :target h Ziel',
        ],
        'breakdown' => [
            'daily_breakdown' => 'Tägliche Aufschlüsselung',
            'weekly_breakdown' => 'Wöchentliche Aufschlüsselung',
        ],
        'recent_entries' => [
            'title' => 'Aktuelle Einträge',
            'empty_state' => 'Keine aktuellen Einträge',
        ],
    ],
    'status' => [
        'not_clocked_in' => 'Nicht eingestempelt',
        'clocked_in' => 'Eingestempelt',
        'on_break' => 'In Pause',
        'clocked_out' => 'Ausgestempelt',
    ],
    'messages' => [
        'validation' => [
            'invalid_sequence' => 'Ungültige Eintragssequenz. Letzter Eintrag: :last_entry. Erwartete Aktion: :expected_action',
            'duplicate_entry' => 'Ein Eintrag mit demselben Zeitstempel und Typ existiert bereits für diesen Mitarbeiter.',
            'outside_working_hours' => 'Zeiterfassung ist nur zwischen :start und :end verfügbar',
            'invalid_time' => 'Arbeitszeiten müssen zwischen :start und :end liegen',
        ],
        'success' => [
            'entry_recorded' => 'Erfolgreich aufgezeichnet: :action',
            'data_refreshed' => 'Daten erfolgreich aktualisiert',
        ],
        'error' => [
            'user_not_found' => 'Benutzer nicht gefunden',
            'invalid_action' => 'Diese Aktion ist basierend auf Ihrem aktuellen Status nicht gültig',
            'failed_to_record' => 'Arbeitsstunden-Eintrag konnte nicht aufgezeichnet werden: :error',
        ],
        'empty_states' => [
            'no_entries_today' => 'Heute keine Einträge aufgezeichnet',
            'no_recent_entries' => 'Keine aktuellen Einträge',
        ],
    ],
    'summary' => [
        'total_hours_worked' => 'Gesamtarbeitsstunden',
        'current_status' => 'Aktueller Status',
        'total_entries' => 'Gesamteinträge',
        'todays_summary' => 'Heutige Zusammenfassung',
        'todays_entries' => 'Heutige Einträge',
        'last_entry' => 'Letzter Eintrag: :type um :time',
    ],
    'quick_actions' => [
        'title' => 'Schnellaktionen',
        'refresh_data' => 'Daten aktualisieren',
        'time_clock' => 'Zeiterfassung',
        'view_all' => 'Alle Einträge anzeigen',
    ],
];
