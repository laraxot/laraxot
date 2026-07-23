# Standard Lingue Modulo Employee - Laraxot

## Panoramica

Questo documento definisce gli standard e le best practices per i file di lingua del modulo Employee, seguendo le convenzioni Laraxot e mantenendo coerenza con il resto del sistema.

## Principi Fondamentali

### 1. Architettura Modulare
- **Isolamento**: Ogni modulo gestisce le proprie traduzioni
- **Coerenza**: Struttura uniforme tra tutti i moduli
- **Manutenibilità**: Organizzazione logica e documentata

### 2. Convenzioni Naming
- **File**: `{model_name}.php` in snake_case
- **Chiavi**: Gerarchia logica con separatori punto
- **Valori**: Lingua nativa per ogni locale

### 3. Struttura Standardizzata
```php
return [
    'navigation' => [...],      // Navigazione e menu
    'resource' => [...],        // Metadati risorsa
    'fields' => [...],          // Campi del form
    'actions' => [...],         // Azioni disponibili
    'sections' => [...],        // Sezioni del form
    'filters' => [...],         // Filtri e ricerca
    'tabs' => [...],            // Tab e navigazione
    'pages' => [...],           // Pagine specifiche
    'widgets' => [...],         // Widget e componenti
    'status' => [...],          // Stati e enumerazioni
    'messages' => [...],        // Messaggi di sistema
    'summary' => [...],         // Riepiloghi e statistiche
    'quick_actions' => [...],   // Azioni rapide
];
```

## Modulo Employee - Struttura Lingue

### 1. Organizzazione File
```
laravel/Modules/Employee/lang/
├── it/                    # Italiano (lingua principale)
│   ├── work_hour.php     # Traduzioni per WorkHour
│   ├── employee.php      # Traduzioni per Employee
│   └── department.php    # Traduzioni per Department
├── en/                    # Inglese
│   ├── work_hour.php     # English translations
│   ├── employee.php      # English translations
│   └── department.php    # English translations
└── de/                    # Tedesco (futuro)
    └── ...
```

### 2. Modello WorkHour - Struttura Completa
```php
'fields' => [
    'employee_id' => [
        'label' => 'Dipendente',
        'placeholder' => 'Seleziona dipendente',
        'help' => 'Il dipendente a cui appartiene questa voce oraria',
        'tooltip' => 'Identifica il dipendente per la registrazione',
        'description' => 'Campo obbligatorio per associare la voce al dipendente corretto',
    ],
    'type' => [
        'label' => 'Tipo di Voce',
        'placeholder' => 'Seleziona tipo',
        'help' => 'Tipo di registrazione oraria',
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
        'help' => 'Momento della registrazione oraria',
        'tooltip' => 'Data e ora esatta della registrazione',
    ],
    'location_lat' => [
        'label' => 'Latitudine',
        'placeholder' => 'Coordinata GPS latitudine',
        'help' => 'Posizione GPS - latitudine',
        'tooltip' => 'Coordinate GPS per verifica posizione',
    ],
    'location_lng' => [
        'label' => 'Longitudine',
        'placeholder' => 'Coordinata GPS longitudine',
        'help' => 'Posizione GPS - longitudine',
        'tooltip' => 'Coordinate GPS per verifica posizione',
    ],
    'location_name' => [
        'label' => 'Nome Località',
        'placeholder' => 'Es: Ufficio, Casa, Cliente...',
        'help' => 'Descrizione della località di lavoro',
        'tooltip' => 'Nome descrittivo della posizione',
    ],
    'device_info' => [
        'label' => 'Info Dispositivo',
        'placeholder' => 'Dettagli dispositivo utilizzato',
        'help' => 'Informazioni sul dispositivo di registrazione',
        'tooltip' => 'Dettagli tecnici del dispositivo',
    ],
    'photo_path' => [
        'label' => 'Foto Verifica',
        'placeholder' => 'Carica foto opzionale',
        'help' => 'Foto di verifica per questa registrazione',
        'tooltip' => 'Foto per documentare la presenza',
    ],
    'status' => [
        'label' => 'Stato Approvazione',
        'placeholder' => 'Seleziona stato',
        'help' => 'Stato di approvazione della voce',
        'options' => [
            'pending' => 'In Attesa',
            'approved' => 'Approvato',
            'rejected' => 'Rifiutato',
        ],
    ],
    'approved_by' => [
        'label' => 'Approvato Da',
        'placeholder' => 'Utente che ha approvato',
        'help' => 'Chi ha approvato questa voce',
        'tooltip' => 'Responsabile dell\'approvazione',
    ],
    'approved_at' => [
        'label' => 'Data Approvazione',
        'placeholder' => 'Quando è stato approvato',
        'help' => 'Data e ora di approvazione',
        'tooltip' => 'Timestamp dell\'approvazione',
    ],
    'notes' => [
        'label' => 'Note',
        'placeholder' => 'Aggiungi note opzionali...',
        'help' => 'Note aggiuntive per questa voce',
        'tooltip' => 'Commenti aggiuntivi opzionali',
    ],
    'created_at' => [
        'label' => 'Creato il',
        'help' => 'Data di creazione del record',
        'tooltip' => 'Timestamp di creazione',
    ],
    'updated_at' => [
        'label' => 'Aggiornato il',
        'help' => 'Data ultimo aggiornamento',
        'tooltip' => 'Timestamp ultimo aggiornamento',
    ],
],
```

### 3. Azioni Complete
```php
'actions' => [
    'create' => [
        'label' => 'Crea Voce Oraria',
        'success' => 'Voce oraria creata con successo',
        'error' => 'Impossibile creare la voce oraria',
        'confirmation' => 'Confermi la creazione di questa voce oraria?',
        'tooltip' => 'Crea una nuova registrazione oraria',
    ],
    'edit' => [
        'label' => 'Modifica Voce Oraria',
        'success' => 'Voce oraria aggiornata con successo',
        'error' => 'Impossibile aggiornare la voce oraria',
        'confirmation' => 'Confermi le modifiche a questa voce oraria?',
        'tooltip' => 'Modifica la registrazione esistente',
    ],
    'delete' => [
        'label' => 'Elimina Voce Oraria',
        'success' => 'Voce oraria eliminata con successo',
        'error' => 'Impossibile eliminare la voce oraria',
        'confirmation' => 'Sei sicuro di voler eliminare questa voce oraria? Questa azione è irreversibile.',
        'tooltip' => 'Elimina definitivamente la registrazione',
    ],
    'view' => [
        'label' => 'Visualizza Dettagli',
        'tooltip' => 'Visualizza tutti i dettagli della registrazione',
    ],
    'approve' => [
        'label' => 'Approva Voce',
        'success' => 'Voce oraria approvata con successo',
        'error' => 'Impossibile approvare la voce oraria',
        'confirmation' => 'Confermi l\'approvazione di questa voce oraria?',
        'tooltip' => 'Approva la registrazione oraria',
    ],
    'reject' => [
        'label' => 'Rifiuta Voce',
        'success' => 'Voce oraria rifiutata',
        'error' => 'Impossibile rifiutare la voce oraria',
        'confirmation' => 'Confermi il rifiuto di questa voce oraria?',
        'tooltip' => 'Rifiuta la registrazione oraria',
    ],
],
```

### 4. Sezioni e Layout
```php
'sections' => [
    'time_entry_details' => [
        'heading' => 'Dettagli Voce Oraria',
        'description' => 'Informazioni sulla registrazione oraria',
        'subtitle' => 'Compila i dettagli della voce oraria',
        'collapsible' => true,
        'collapsed' => false,
    ],
    'location_info' => [
        'heading' => 'Informazioni Località',
        'description' => 'Dati sulla posizione di lavoro',
        'subtitle' => 'Specifica la posizione di lavoro',
        'collapsible' => true,
        'collapsed' => true,
    ],
    'approval_info' => [
        'heading' => 'Informazioni Approvazione',
        'description' => 'Stato e dettagli approvazione',
        'subtitle' => 'Gestisci l\'approvazione della voce',
        'collapsible' => true,
        'collapsed' => true,
    ],
    'metadata' => [
        'heading' => 'Metadati',
        'description' => 'Informazioni tecniche e note',
        'subtitle' => 'Dettagli aggiuntivi e note',
        'collapsible' => true,
        'collapsed' => true,
    ],
],
```

### 5. Filtri e Ricerca
```php
'filters' => [
    'employee' => [
        'label' => 'Dipendente',
        'placeholder' => 'Seleziona dipendente',
        'help' => 'Filtra per dipendente specifico',
    ],
    'entry_type' => [
        'label' => 'Tipo Voce',
        'placeholder' => 'Seleziona tipo',
        'help' => 'Filtra per tipo di registrazione',
    ],
    'date_range' => [
        'label' => 'Periodo',
        'from_date' => 'Da Data',
        'to_date' => 'A Data',
        'help' => 'Seleziona il periodo di interesse',
    ],
    'status' => [
        'label' => 'Stato',
        'placeholder' => 'Seleziona stato',
        'help' => 'Filtra per stato di approvazione',
    ],
    'today_only' => [
        'label' => 'Solo Oggi',
        'help' => 'Mostra solo le registrazioni di oggi',
    ],
    'pending_approval' => [
        'label' => 'In Attesa Approvazione',
        'help' => 'Mostra solo voci in attesa di approvazione',
    ],
],
```

### 6. Tab e Navigazione
```php
'tabs' => [
    'all' => [
        'label' => 'Tutte le Voci',
        'description' => 'Visualizza tutte le registrazioni',
        'icon' => 'heroicon-o-list-bullet',
    ],
    'today' => [
        'label' => 'Oggi',
        'description' => 'Registrazioni di oggi',
        'icon' => 'heroicon-o-calendar-days',
    ],
    'this_week' => [
        'label' => 'Questa Settimana',
        'description' => 'Registrazioni della settimana corrente',
        'icon' => 'heroicon-o-calendar',
    ],
    'pending' => [
        'label' => 'In Attesa',
        'description' => 'Voci in attesa di approvazione',
        'icon' => 'heroicon-o-clock',
    ],
    'approved' => [
        'label' => 'Approvate',
        'description' => 'Voci approvate',
        'icon' => 'heroicon-o-check-circle',
    ],
    'rejected' => [
        'label' => 'Rifiutate',
        'description' => 'Voci rifiutate',
        'icon' => 'heroicon-o-x-circle',
    ],
],
```

### 7. Pagine Specifiche
```php
'pages' => [
    'timeclock' => [
        'title' => 'Timbratura Dipendenti',
        'subtitle' => 'Registra le tue ore di lavoro facilmente',
        'heading' => 'Sistema di Timbratura',
        'description' => 'Interfaccia per la registrazione delle ore di lavoro',
        'welcome_message' => 'Benvenuto nel sistema di timbratura',
        'instructions' => 'Seleziona il tipo di registrazione e conferma',
    ],
    'list' => [
        'title' => 'Ore di Lavoro',
        'subtitle' => 'Gestione delle registrazioni orarie',
        'heading' => 'Elenco Voci Orarie',
        'description' => 'Visualizza e gestisci tutte le registrazioni orarie',
        'empty_state' => 'Nessuna voce oraria trovata',
        'loading_message' => 'Caricamento voci orarie...',
    ],
    'create' => [
        'title' => 'Nuova Voce Oraria',
        'subtitle' => 'Crea una nuova registrazione oraria',
        'heading' => 'Creazione Voce Oraria',
        'description' => 'Compila i dettagli per la nuova registrazione',
        'success_message' => 'Voce oraria creata con successo',
    ],
    'edit' => [
        'title' => 'Modifica Voce Oraria',
        'subtitle' => 'Modifica la registrazione esistente',
        'heading' => 'Modifica Voce Oraria',
        'description' => 'Aggiorna i dettagli della registrazione',
        'success_message' => 'Voce oraria aggiornata con successo',
    ],
    'view' => [
        'title' => 'Dettagli Voce Oraria',
        'subtitle' => 'Visualizza tutti i dettagli',
        'heading' => 'Dettagli Voce Oraria',
        'description' => 'Informazioni complete sulla registrazione',
    ],
],
```

### 8. Widget e Componenti
```php
'widgets' => [
    'stats' => [
        'title' => 'Statistiche Ore di Lavoro',
        'description' => 'Panoramica delle registrazioni orarie',
        'today_entries' => [
            'label' => 'Voci di Oggi',
            'description' => 'Numero di registrazioni oggi',
            'tooltip' => 'Conteggio voci registrate oggi',
        ],
        'this_week' => [
            'label' => 'Questa Settimana',
            'description' => 'Totale ore questa settimana',
            'tooltip' => 'Ore totali lavorate questa settimana',
        ],
        'avg_daily' => [
            'label' => 'Media Giornaliera',
            'description' => 'Media ore per giorno',
            'tooltip' => 'Media delle ore lavorate per giorno',
        ],
        'pending_approval' => [
            'label' => 'In Attesa Approvazione',
            'description' => 'Voci da approvare',
            'tooltip' => 'Numero di voci in attesa di approvazione',
        ],
        'total_hours' => [
            'label' => 'Ore Totali',
            'description' => 'Totale ore lavorate',
            'tooltip' => 'Somma totale delle ore lavorate',
        ],
    ],
    'recent_activity' => [
        'title' => 'Attività Recenti',
        'description' => 'Ultime registrazioni orarie',
        'empty_state' => 'Nessuna attività recente',
        'view_all' => 'Visualizza Tutte',
        'refresh' => 'Aggiorna',
    ],
    'quick_actions' => [
        'title' => 'Azioni Rapide',
        'description' => 'Accesso veloce alle funzioni principali',
        'clock_in' => [
            'label' => 'Entrata',
            'description' => 'Registra entrata',
            'icon' => 'heroicon-o-play',
        ],
        'clock_out' => [
            'label' => 'Uscita',
            'description' => 'Registra uscita',
            'icon' => 'heroicon-o-stop',
        ],
        'break_start' => [
            'label' => 'Inizio Pausa',
            'description' => 'Inizia pausa',
            'icon' => 'heroicon-o-pause',
        ],
        'break_end' => [
            'label' => 'Fine Pausa',
            'description' => 'Termina pausa',
            'icon' => 'heroicon-o-play',
        ],
    ],
],
```

### 9. Stati e Enumerazioni
```php
'status' => [
    'not_clocked_in' => [
        'label' => 'Non Timbrato',
        'description' => 'Dipendente non ha ancora timbrato l\'entrata',
        'color' => 'gray',
        'icon' => 'heroicon-o-clock',
    ],
    'clocked_in' => [
        'label' => 'Timbrato in Entrata',
        'description' => 'Dipendente ha timbrato l\'entrata',
        'color' => 'green',
        'icon' => 'heroicon-o-check-circle',
    ],
    'on_break' => [
        'label' => 'In Pausa',
        'description' => 'Dipendente è in pausa',
        'color' => 'yellow',
        'icon' => 'heroicon-o-pause',
    ],
    'clocked_out' => [
        'label' => 'Timbrato in Uscita',
        'description' => 'Dipendente ha timbrato l\'uscita',
        'color' => 'red',
        'icon' => 'heroicon-o-x-circle',
    ],
    'pending' => [
        'label' => 'In Attesa',
        'description' => 'Voce in attesa di approvazione',
        'color' => 'orange',
        'icon' => 'heroicon-o-clock',
    ],
    'approved' => [
        'label' => 'Approvato',
        'description' => 'Voce approvata dal supervisore',
        'color' => 'green',
        'icon' => 'heroicon-o-check-circle',
    ],
    'rejected' => [
        'label' => 'Rifiutato',
        'description' => 'Voce rifiutata dal supervisore',
        'color' => 'red',
        'icon' => 'heroicon-o-x-circle',
    ],
],
```

### 10. Messaggi di Sistema
```php
'messages' => [
    'validation' => [
        'invalid_sequence' => 'Sequenza voci non valida. Ultima voce: :last_entry',
        'duplicate_entry' => 'Esiste già una voce con questo timestamp per il dipendente',
        'outside_working_hours' => 'Registrazione disponibile dalle :start alle :end',
        'invalid_time' => 'Orario non valido per il turno di lavoro',
        'employee_required' => 'Il campo dipendente è obbligatorio',
        'type_required' => 'Il tipo di voce è obbligatorio',
        'timestamp_required' => 'La data e ora sono obbligatorie',
        'timestamp_future' => 'Non è possibile registrare voci future',
        'timestamp_past_limit' => 'Non è possibile registrare voci oltre :days giorni fa',
    ],
    'success' => [
        'entry_recorded' => 'Registrazione completata: :action alle :time',
        'data_refreshed' => 'Dati aggiornati con successo',
        'entry_created' => 'Voce oraria creata correttamente',
        'entry_updated' => 'Voce oraria aggiornata correttamente',
        'entry_deleted' => 'Voce oraria eliminata correttamente',
        'entry_approved' => 'Voce oraria approvata correttamente',
        'entry_rejected' => 'Voce oraria rifiutata correttamente',
        'bulk_approved' => ':count voci orarie approvate correttamente',
        'bulk_rejected' => ':count voci orarie rifiutate correttamente',
    ],
    'error' => [
        'user_not_found' => 'Dipendente non trovato',
        'invalid_action' => 'Azione non valida per lo stato attuale',
        'failed_to_record' => 'Errore nella registrazione: :error',
        'permission_denied' => 'Non hai i permessi per questa operazione',
        'entry_not_found' => 'Voce oraria non trovata',
        'invalid_status_transition' => 'Transizione di stato non valida',
        'approval_failed' => 'Impossibile approvare la voce: :error',
        'rejection_failed' => 'Impossibile rifiutare la voce: :error',
        'bulk_operation_failed' => 'Operazione bulk fallita: :error',
    ],
    'empty_states' => [
        'no_entries_today' => 'Nessuna voce registrata oggi',
        'no_recent_entries' => 'Nessuna voce recente disponibile',
        'no_entries_found' => 'Nessuna voce oraria trovata',
        'no_pending_approvals' => 'Nessuna voce in attesa di approvazione',
        'no_approved_entries' => 'Nessuna voce approvata trovata',
        'no_rejected_entries' => 'Nessuna voce rifiutata trovata',
    ],
    'confirmations' => [
        'delete_entry' => 'Sei sicuro di voler eliminare questa voce oraria?',
        'bulk_delete' => 'Sei sicuro di voler eliminare :count voci orarie?',
        'approve_entry' => 'Confermi l\'approvazione di questa voce oraria?',
        'reject_entry' => 'Confermi il rifiuto di questa voce oraria?',
        'bulk_approve' => 'Confermi l\'approvazione di :count voci orarie?',
        'bulk_reject' => 'Confermi il rifiuto di :count voci orarie?',
    ],
],
```

### 11. Riepiloghi e Statistiche
```php
'summary' => [
    'total_hours_worked' => [
        'label' => 'Ore Totali Lavorate',
        'description' => 'Somma totale delle ore lavorate',
        'tooltip' => 'Calcolo basato su entrate e uscite',
    ],
    'current_status' => [
        'label' => 'Stato Attuale',
        'description' => 'Stato corrente del dipendente',
        'tooltip' => 'Stato basato sull\'ultima registrazione',
    ],
    'total_entries' => [
        'label' => 'Voci Totali',
        'description' => 'Numero totale di registrazioni',
        'tooltip' => 'Conteggio di tutte le voci',
    ],
    'todays_summary' => [
        'label' => 'Riepilogo di Oggi',
        'description' => 'Panoramica delle registrazioni di oggi',
        'tooltip' => 'Statistiche giornaliere',
    ],
    'todays_entries' => [
        'label' => 'Voci di Oggi',
        'description' => 'Registrazioni effettuate oggi',
        'tooltip' => 'Lista delle voci di oggi',
    ],
    'last_entry' => [
        'label' => 'Ultima voce: :type alle :time',
        'description' => 'Ultima registrazione effettuata',
        'tooltip' => 'Dettagli dell\'ultima voce',
    ],
    'work_time' => [
        'label' => 'Tempo di Lavoro: :hours ore',
        'description' => 'Tempo totale lavorato',
        'tooltip' => 'Calcolo ore lavorate',
    ],
    'break_time' => [
        'label' => 'Tempo di Pausa: :hours ore',
        'description' => 'Tempo totale in pausa',
        'tooltip' => 'Calcolo tempo pausa',
    ],
    'overtime' => [
        'label' => 'Straordinario: :hours ore',
        'description' => 'Ore oltre l\'orario standard',
        'tooltip' => 'Calcolo ore straordinarie',
    ],
],
```

### 12. Azioni Rapide
```php
'quick_actions' => [
    'title' => 'Azioni Rapide',
    'description' => 'Accesso veloce alle funzioni principali',
    'refresh_data' => [
        'label' => 'Aggiorna Dati',
        'description' => 'Ricarica i dati più recenti',
        'tooltip' => 'Sincronizza con il database',
    ],
    'export_data' => [
        'label' => 'Esporta Dati',
        'description' => 'Esporta i dati in formato Excel/CSV',
        'tooltip' => 'Scarica report in formato Excel',
    ],
    'view_reports' => [
        'label' => 'Visualizza Report',
        'description' => 'Accedi ai report e statistiche',
        'tooltip' => 'Apri dashboard reportistica',
    ],
    'time_clock' => [
        'label' => 'Vai alla Timbratura',
        'description' => 'Accedi al sistema di timbratura',
        'tooltip' => 'Apri interfaccia timbratura',
    ],
    'view_all' => [
        'label' => 'Visualizza Tutte le Voci',
        'description' => 'Mostra tutte le registrazioni',
        'tooltip' => 'Apri lista completa',
    ],
    'create_entry' => [
        'label' => 'Nuova Voce',
        'description' => 'Crea una nuova registrazione',
        'tooltip' => 'Apri form creazione',
    ],
    'bulk_approve' => [
        'label' => 'Approva Selezionate',
        'description' => 'Approva tutte le voci selezionate',
        'tooltip' => 'Operazione bulk approvazione',
    ],
    'bulk_reject' => [
        'label' => 'Rifiuta Selezionate',
        'description' => 'Rifiuta tutte le voci selezionate',
        'tooltip' => 'Operazione bulk rifiuto',
    ],
],
```

## Best Practices Implementazione

### 1. Struttura File
- **Organizzazione**: Raggruppamento logico per funzionalità
- **Consistenza**: Struttura uniforme tra tutti i modelli
- **Manutenibilità**: Chiavi chiare e documentate

### 2. Qualità Traduzioni
- **Professionalità**: Linguaggio professionale ma comprensibile
- **Consistenza**: Terminologia uniforme in tutto il modulo
- **Completezza**: Tutti i campi hanno label, placeholder e help

### 3. Parametri Dinamici
- **Placeholder**: Usare `:param_name` per valori dinamici
- **Contesto**: Parametri significativi e descrittivi
- **Validazione**: Gestire casi di parametri mancanti

### 4. Manutenzione
- **Sincronizzazione**: Mantenere aggiornati `it/` e `en/`
- **Validazione**: Testare sempre con `php -l filename.php`
- **Documentazione**: Aggiornare quando si aggiungono nuove chiavi

## Collegamenti e Riferimenti

- [Best Practices Employee Module](../laravel/Modules/Employee/docs/language_best_practices.md)
- [Documentazione Lingue Centralizzata](../laravel/Modules/Lang/docs/)
- [Convenzioni Laraxot](../laravel/Modules/Xot/docs/conventions.md)
- [Standard Traduzioni](../laravel/Modules/Lang/docs/translation_file_syntax.md)

---

**RICORDA**: Ogni modifica ai file di lingua deve essere testata con `php -l` e aggiornata in tutte le lingue supportate. Mantieni sempre la coerenza strutturale e la qualità delle traduzioni.
