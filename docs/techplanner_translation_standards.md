# Standard Traduzioni Modulo TechPlanner - Laraxot

## Panoramica

Questo documento definisce gli standard e le best practices per i file di lingua del modulo TechPlanner, seguendo le convenzioni Laraxot e mantenendo coerenza con il resto del sistema.

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

## Modulo TechPlanner - Struttura Lingue

### 1. Organizzazione File
```
laravel/Modules/TechPlanner/lang/
├── it/                    # Italiano (lingua principale)
│   ├── appointment.php   # Traduzioni per Appointment
│   ├── client.php        # Traduzioni per Client
│   ├── phone_call.php    # Traduzioni per PhoneCall
│   └── legal_representative.php # Traduzioni per LegalRepresentative
├── en/                    # Inglese
│   ├── appointment.php   # English translations
│   ├── client.php        # English translations
│   ├── phone_call.php    # English translations
│   └── legal_representative.php # English translations
└── de/                    # Tedesco
    ├── appointment.php   # German translations
    ├── client.php        # German translations
    ├── phone_call.php    # German translations
    └── legal_representative.php # German translations
```

### 2. Convenzioni per le Icone

#### Icone Heroicon Standard
```php
'navigation' => [
    'label' => 'Appuntamenti',
    'group' => 'TechPlanner',
    'icon' => 'heroicon-o-calendar', // Icona Heroicon standard
],
```

#### Icone Personalizzate Modulo
```php
'navigation' => [
    'label' => 'Chiamate Telefoniche',
    'group' => 'TechPlanner',
    'icon' => 'techplanner-phone-call', // Icona personalizzata del modulo
],
```

#### Icone per Funzionalità Specifiche
```php
'navigation' => [
    'label' => 'Rappresentanti Legali',
    'group' => 'TechPlanner',
    'icon' => 'techplanner-legal-representative', // Icona specifica
],
```

### 3. Struttura Navigation Standard

#### Pattern Corretto
```php
'navigation' => [
    'label' => 'Nome Risorsa',
    'group' => 'TechPlanner',
    'icon' => 'heroicon-o-icon-name', // Icona Heroicon standard
    'sort' => 1,
],
```

#### Pattern per Icone Personalizzate
```php
'navigation' => [
    'label' => 'Nome Risorsa',
    'group' => 'TechPlanner',
    'icon' => 'techplanner-resource-name', // Icona personalizzata
    'sort' => 1,
],
```

### 4. Modello Appointment - Struttura Completa
```php
return [
    'navigation' => [
        'label' => 'Appuntamenti',
        'group' => 'TechPlanner',
        'icon' => 'heroicon-o-calendar',
        'sort' => 1,
    ],
    'resource' => [
        'label' => 'Appuntamento',
        'plural_label' => 'Appuntamenti',
        'navigation_group' => 'TechPlanner',
        'navigation_icon' => 'heroicon-o-calendar',
        'navigation_sort' => 1,
        'description' => 'Gestione completa degli appuntamenti',
    ],
    'fields' => [
        'client_id' => [
            'label' => 'Cliente',
            'placeholder' => 'Seleziona cliente',
            'help' => 'Il cliente per cui è programmato l\'appuntamento',
        ],
        'date' => [
            'label' => 'Data',
            'placeholder' => 'Seleziona data',
            'help' => 'Data dell\'appuntamento',
        ],
        'time' => [
            'label' => 'Ora',
            'placeholder' => 'Seleziona ora',
            'help' => 'Orario dell\'appuntamento',
        ],
        'status' => [
            'label' => 'Stato',
            'placeholder' => 'Seleziona stato',
            'help' => 'Stato corrente dell\'appuntamento',
            'options' => [
                'scheduled' => 'Programmato',
                'confirmed' => 'Confermato',
                'completed' => 'Completato',
                'cancelled' => 'Cancellato',
            ],
        ],
        'notes' => [
            'label' => 'Note',
            'placeholder' => 'Inserisci note aggiuntive',
            'help' => 'Note e dettagli sull\'appuntamento',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo Appuntamento',
            'icon' => 'heroicon-o-plus',
            'color' => 'primary',
            'tooltip' => 'Crea un nuovo appuntamento',
        ],
        'edit' => [
            'label' => 'Modifica Appuntamento',
            'icon' => 'heroicon-o-pencil',
            'color' => 'warning',
            'tooltip' => 'Modifica l\'appuntamento selezionato',
        ],
        'delete' => [
            'label' => 'Elimina Appuntamento',
            'icon' => 'heroicon-o-trash',
            'color' => 'danger',
            'tooltip' => 'Elimina l\'appuntamento selezionato',
        ],
        'confirm' => [
            'label' => 'Conferma Appuntamento',
            'icon' => 'heroicon-o-check-circle',
            'color' => 'success',
            'tooltip' => 'Conferma l\'appuntamento selezionato',
        ],
        'view' => [
            'label' => 'Visualizza Appuntamento',
            'icon' => 'heroicon-o-eye',
            'color' => 'info',
            'tooltip' => 'Visualizza i dettagli dell\'appuntamento',
        ],
    ],
    'filters' => [
        'client' => [
            'label' => 'Cliente',
            'placeholder' => 'Seleziona cliente',
            'help' => 'Filtra per cliente specifico',
        ],
        'status' => [
            'label' => 'Stato',
            'placeholder' => 'Seleziona stato',
            'help' => 'Filtra per stato dell\'appuntamento',
        ],
        'date_range' => [
            'label' => 'Periodo',
            'from_date' => 'Da Data',
            'to_date' => 'A Data',
            'help' => 'Seleziona il periodo di interesse',
        ],
    ],
    'messages' => [
        'created' => 'Appuntamento creato con successo',
        'updated' => 'Appuntamento aggiornato con successo',
        'deleted' => 'Appuntamento eliminato con successo',
        'confirmed' => 'Appuntamento confermato con successo',
        'cancelled' => 'Appuntamento cancellato con successo',
    ],
];
```

### 5. Modello PhoneCall - Struttura Completa
```php
return [
    'navigation' => [
        'label' => 'Chiamate Telefoniche',
        'group' => 'TechPlanner',
        'icon' => 'heroicon-o-phone', // Icona Heroicon standard
        'sort' => 2,
    ],
    'resource' => [
        'label' => 'Chiamata Telefonica',
        'plural_label' => 'Chiamate Telefoniche',
        'navigation_group' => 'TechPlanner',
        'navigation_icon' => 'heroicon-o-phone',
        'navigation_sort' => 2,
        'description' => 'Gestione delle chiamate telefoniche',
    ],
    'fields' => [
        'client_id' => [
            'label' => 'Cliente',
            'placeholder' => 'Seleziona cliente',
            'help' => 'Il cliente con cui è stata effettuata la chiamata',
        ],
        'call_type' => [
            'label' => 'Tipo Chiamata',
            'placeholder' => 'Seleziona tipo',
            'help' => 'Tipo di chiamata effettuata',
            'options' => [
                'incoming' => 'In Entrata',
                'outgoing' => 'In Uscita',
                'missed' => 'Persa',
            ],
        ],
        'duration' => [
            'label' => 'Durata',
            'placeholder' => 'Inserisci durata in minuti',
            'help' => 'Durata della chiamata in minuti',
        ],
        'notes' => [
            'label' => 'Note',
            'placeholder' => 'Inserisci note sulla chiamata',
            'help' => 'Note e dettagli sulla chiamata',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuova Chiamata',
            'icon' => 'heroicon-o-plus',
            'color' => 'primary',
            'tooltip' => 'Registra una nuova chiamata',
        ],
        'edit' => [
            'label' => 'Modifica Chiamata',
            'icon' => 'heroicon-o-pencil',
            'color' => 'warning',
            'tooltip' => 'Modifica la chiamata selezionata',
        ],
        'delete' => [
            'label' => 'Elimina Chiamata',
            'icon' => 'heroicon-o-trash',
            'color' => 'danger',
            'tooltip' => 'Elimina la chiamata selezionata',
        ],
    ],
    'messages' => [
        'created' => 'Chiamata registrata con successo',
        'updated' => 'Chiamata aggiornata con successo',
        'deleted' => 'Chiamata eliminata con successo',
    ],
];
```

### 6. Modello LegalRepresentative - Struttura Completa
```php
return [
    'navigation' => [
        'label' => 'Rappresentanti Legali',
        'group' => 'TechPlanner',
        'icon' => 'heroicon-o-user-group', // Icona Heroicon standard
        'sort' => 3,
    ],
    'resource' => [
        'label' => 'Rappresentante Legale',
        'plural_label' => 'Rappresentanti Legali',
        'navigation_group' => 'TechPlanner',
        'navigation_icon' => 'heroicon-o-user-group',
        'navigation_sort' => 3,
        'description' => 'Gestione dei rappresentanti legali',
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci nome completo',
            'help' => 'Nome completo del rappresentante legale',
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Inserisci indirizzo email',
            'help' => 'Indirizzo email del rappresentante',
        ],
        'phone' => [
            'label' => 'Telefono',
            'placeholder' => 'Inserisci numero di telefono',
            'help' => 'Numero di telefono del rappresentante',
        ],
        'role' => [
            'label' => 'Ruolo',
            'placeholder' => 'Seleziona ruolo',
            'help' => 'Ruolo del rappresentante legale',
            'options' => [
                'lawyer' => 'Avvocato',
                'notary' => 'Notaio',
                'consultant' => 'Consulente',
            ],
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo Rappresentante',
            'icon' => 'heroicon-o-plus',
            'color' => 'primary',
            'tooltip' => 'Aggiungi un nuovo rappresentante legale',
        ],
        'edit' => [
            'label' => 'Modifica Rappresentante',
            'icon' => 'heroicon-o-pencil',
            'color' => 'warning',
            'tooltip' => 'Modifica il rappresentante selezionato',
        ],
        'delete' => [
            'label' => 'Elimina Rappresentante',
            'icon' => 'heroicon-o-trash',
            'color' => 'danger',
            'tooltip' => 'Elimina il rappresentante selezionato',
        ],
    ],
    'messages' => [
        'created' => 'Rappresentante legale creato con successo',
        'updated' => 'Rappresentante legale aggiornato con successo',
        'deleted' => 'Rappresentante legale eliminato con successo',
    ],
];
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

### 3. Icone e Navigazione
- **Heroicon Standard**: Utilizzare icone Heroicon per funzionalità standard
- **Icone Personalizzate**: Creare icone personalizzate solo quando necessario
- **Coerenza**: Mantenere coerenza nell'uso delle icone tra moduli simili

### 4. Parametri Dinamici
- **Placeholder**: Usare `:param_name` per valori dinamici
- **Contesto**: Parametri significativi e descrittivi
- **Validazione**: Gestire casi di parametri mancanti

### 5. Manutenzione
- **Sincronizzazione**: Mantenere aggiornati `it/`, `en/` e `de/`
- **Validazione**: Testare sempre con `php -l filename.php`
- **Documentazione**: Aggiornare quando si aggiungono nuove chiavi

## Problemi Identificati e Soluzioni

### 1. Icone con ".navigation" (DA CORREGGERE)
```php
// ❌ ERRATO - Non utilizzare mai
'icon' => 'phone call.navigation',
'icon' => 'legal representative.navigation',

// ✅ CORRETTO - Utilizzare icone Heroicon standard
'icon' => 'heroicon-o-phone',
'icon' => 'heroicon-o-user-group',
```

### 2. File di Traduzione Problematici
- `laravel/Modules/TechPlanner/lang/it/phone_call.php`
- `laravel/Modules/TechPlanner/lang/it/legal_representative.php`
- `laravel/Modules/Geo/lang/it/.php`
- `laravel/Modules/Cms/resources/lang/it/section.php`
- `laravel/Modules/Chart/lang/*/chart.php`
- `laravel/Modules/Chart/lang/*/mixed_chart.php`

### 3. Soluzioni Applicate
- Sostituire tutte le icone con ".navigation" con icone Heroicon appropriate
- Standardizzare la struttura delle traduzioni
- Mantenere coerenza tra tutte le lingue (it, en, de)

## Collegamenti e Riferimenti

- [Employee Language Standards](employee_language_standards.md)
- [SVG Icon System Standards](svg_icon_system_standards.md)
- [Documentazione Lingue Centralizzata](../laravel/Modules/Lang/docs/)
- [Convenzioni Laraxot](../laravel/Modules/Xot/docs/conventions.md)
- [Standard Traduzioni](../laravel/Modules/Lang/docs/translation_file_syntax.md)

## Riepilogo File Tradotti

### Completati ✅
- `phone_call.php` (IT, EN, DE) - Rifattorizzato completamente
- `legal_representative.php` (IT, EN, DE) - Rifattorizzato completamente  
- `legal_office.php` (IT, EN, DE) - Rifattorizzato completamente

### Pattern Risolti
- ❌ `phone call.navigation` → ✅ `phone_call.navigation.label`
- ❌ `legal representative.navigation` → ✅ `legal_representative.navigation.label`
- ❌ `legal_office.navigation` → ✅ `legal_office.navigation.label`

### Prossimi Passi
1. Identificare altri file con pattern `.navigation` problematici
2. Applicare gli standard a tutti i file di traduzione del modulo TechPlanner
3. Verificare coerenza con altri moduli del sistema

---

**RICORDA**: Ogni modifica ai file di lingua deve essere testata con `php -l` e aggiornata in tutte le lingue supportate. Mantieni sempre la coerenza strutturale e la qualità delle traduzioni. Le icone devono seguire le convenzioni Heroicon standard.


