# Best Practices per File di Lingua - Modulo Employee

## Principi Fondamentali

### 1. Struttura Standardizzata
- **Namespace**: `Modules\Employee\lang\{locale}\`
- **Formato**: Array PHP con struttura gerarchica chiara
- **Naming**: Snake_case per le chiavi, PascalCase per i valori
- **Organizzazione**: Raggruppamento logico per funzionalità

### 2. Convenzioni di Naming
- **File**: `{model_name}.php` (es. `work_hour.php`, `employee.php`)
- **Chiavi**: `{section}.{subsection}.{field}` (es. `fields.employee_id.label`)
- **Valori**: Italiano per `it/`, Inglese per `en/`

### 3. Struttura Standard per Modelli
```php
return [
    'navigation' => [
        'label' => 'Nome Modulo',
        'group' => 'Gruppo Navigazione',
        'icon' => 'heroicon-o-icon',
        'sort' => 50,
    ],
    
    'resource' => [
        'label' => 'Nome Singolare',
        'plural_label' => 'Nome Plurale',
        'navigation_label' => 'Label Navigazione',
    ],
    
    'fields' => [
        'field_name' => [
            'label' => 'Label Campo',
            'placeholder' => 'Placeholder Campo',
            'help' => 'Testo di Aiuto',
            'tooltip' => 'Tooltip Campo',
            'description' => 'Descrizione Dettagliata',
        ],
    ],
    
    'actions' => [
        'action_name' => [
            'label' => 'Label Azione',
            'success' => 'Messaggio Successo',
            'error' => 'Messaggio Errore',
            'confirmation' => 'Messaggio Conferma',
        ],
    ],
    
    'sections' => [
        'section_name' => [
            'heading' => 'Titolo Sezione',
            'description' => 'Descrizione Sezione',
        ],
    ],
    
    'filters' => [
        'filter_name' => [
            'label' => 'Label Filtro',
        ],
    ],
    
    'tabs' => [
        'tab_name' => 'Nome Tab',
    ],
    
    'pages' => [
        'page_name' => [
            'title' => 'Titolo Pagina',
            'subtitle' => 'Sottotitolo Pagina',
            'heading' => 'Intestazione Pagina',
        ],
    ],
    
    'widgets' => [
        'widget_name' => [
            'label' => 'Label Widget',
        ],
    ],
    
    'status' => [
        'status_name' => 'Nome Status',
    ],
    
    'messages' => [
        'validation' => [
            'rule_name' => 'Messaggio Validazione',
        ],
        'success' => [
            'action_name' => 'Messaggio Successo',
        ],
        'error' => [
            'error_name' => 'Messaggio Errore',
        ],
        'empty_states' => [
            'state_name' => 'Messaggio Stato Vuoto',
        ],
    ],
    
    'summary' => [
        'summary_name' => 'Nome Riepilogo',
    ],
    
    'quick_actions' => [
        'title' => 'Titolo Azioni Rapide',
        'action_name' => 'Nome Azione',
    ],
];
```

## Best Practices per WorkHour

### 1. Organizzazione Campi
- **Campi Principali**: `employee_id`, `type`, `timestamp`
- **Campi GPS**: `location_lat`, `location_lng`, `location_name`
- **Campi Approvazione**: `status`, `approved_by`, `approved_at`
- **Campi Metadati**: `device_info`, `photo_path`, `notes`

### 2. Tipi di Voce Oraria
```php
'type' => [
    'label' => 'Tipo di Voce',
    'placeholder' => 'Seleziona tipo',
    'description' => 'Tipo di registrazione oraria',
    'options' => [
        'clock_in' => 'Entrata',
        'clock_out' => 'Uscita',
        'break_start' => 'Inizio Pausa',
        'break_end' => 'Fine Pausa',
    ],
],
```

### 3. Stati di Approvazione
```php
'status' => [
    'label' => 'Stato Approvazione',
    'placeholder' => 'Seleziona stato',
    'description' => 'Stato di approvazione della voce',
    'options' => [
        'pending' => 'In Attesa',
        'approved' => 'Approvato',
        'rejected' => 'Rifiutato',
    ],
],
```

### 4. Messaggi di Validazione
```php
'messages' => [
    'validation' => [
        'invalid_sequence' => 'Sequenza voci non valida. Ultima voce: :last_entry',
        'duplicate_entry' => 'Esiste già una voce con questo timestamp per il dipendente',
        'outside_working_hours' => 'Registrazione disponibile dalle :start alle :end',
        'invalid_time' => 'Orario non valido per il turno di lavoro',
    ],
],
```

### 5. Messaggi di Successo/Errore
```php
'messages' => [
    'success' => [
        'entry_recorded' => 'Registrazione completata: :action alle :time',
        'data_refreshed' => 'Dati aggiornati con successo',
        'entry_created' => 'Voce oraria creata correttamente',
        'entry_updated' => 'Voce oraria aggiornata correttamente',
    ],
    'error' => [
        'user_not_found' => 'Dipendente non trovato',
        'invalid_action' => 'Azione non valida per lo stato attuale',
        'failed_to_record' => 'Errore nella registrazione: :error',
        'permission_denied' => 'Non hai i permessi per questa operazione',
    ],
],
```

## Regole di Qualità

### 1. Consistenza Linguistica
- **Terminologia**: Usare sempre gli stessi termini per concetti simili
- **Formalità**: Mantenere un tono professionale ma comprensibile
- **Lunghezza**: Messaggi concisi ma informativi

### 2. Parametri e Placeholder
- **Parametri**: Usare `:param_name` per valori dinamici
- **Placeholder**: Testi di esempio realistici e utili
- **Help Text**: Spiegazioni chiare del campo

### 3. Validazione Sintassi
- **PHP Syntax**: Verificare sempre con `php -l filename.php`
- **Array Structure**: Controllare parentesi e virgole
- **Encoding**: UTF-8 senza BOM

### 4. Manutenzione
- **Aggiornamenti**: Mantenere sincronizzati `it/` e `en/`
- **Rimozione**: Eliminare chiavi non più utilizzate
- **Documentazione**: Aggiornare quando si aggiungono nuove chiavi

## Esempi di Implementazione

### 1. Campo Completo
```php
'employee_id' => [
    'label' => 'Dipendente',
    'placeholder' => 'Seleziona dipendente',
    'help' => 'Il dipendente a cui appartiene questa voce oraria',
    'tooltip' => 'Identifica il dipendente per la registrazione',
    'description' => 'Campo obbligatorio per associare la voce al dipendente corretto',
],
```

### 2. Azione Completa
```php
'create' => [
    'label' => 'Crea Voce Oraria',
    'success' => 'Voce oraria creata con successo',
    'error' => 'Impossibile creare la voce oraria',
    'confirmation' => 'Confermi la creazione di questa voce oraria?',
],
```

### 3. Sezione Completa
```php
'time_entry_details' => [
    'heading' => 'Dettagli Voce Oraria',
    'description' => 'Informazioni sulla registrazione oraria',
    'subtitle' => 'Compila i dettagli della voce oraria',
],
```

## Collegamenti e Riferimenti

- [Documentazione Lingue Centralizzata](../../Lang/docs/)
- [Best Practices Xot Module](../../Xot/docs/)
- [Convenzioni Laraxot](../../Xot/docs/conventions.md)
- [Struttura Moduli](../../Xot/docs/module-structure.md)

---

**RICORDA**: Ogni modifica ai file di lingua deve essere testata con `php -l` e aggiornata in tutte le lingue supportate.
