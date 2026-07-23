# Traduzioni del Tema Sixteen

## Panoramica

Il tema Sixteen implementa un sistema di traduzioni completo e strutturato che segue le **Linee Guida di Design per i Servizi Digitali della Pubblica Amministrazione**. Tutte le stringhe visibili all'utente sono gestite tramite file di traduzione per garantire accessibilità, inclusività e conformità agli standard italiani.

## Struttura delle Traduzioni

### Organizzazione File
```
Themes/Sixteen/lang/
├── it/                      # Italiano (lingua principale)
│   ├── components.php       # Componenti UI
│   ├── pages.php           # Pagine specifiche
│   ├── forms.php           # Form e validazione
│   ├── navigation.php      # Navigazione
│   ├── alerts.php          # Messaggi di sistema
│   ├── buttons.php         # Pulsanti e azioni
│   └── common.php          # Termini comuni
├── en/                     # Inglese
│   └── [stessa struttura]
└── de/                     # Tedesco
    └── [stessa struttura]
```

### Struttura Espansa Obbligatoria

Tutte le traduzioni devono seguire la struttura espansa per garantire completezza e accessibilità:

```php
<?php

declare(strict_types=1);

return [
    'components' => [
        'button' => [
            'primary' => [
                'label' => 'Conferma',
                'tooltip' => 'Conferma l\'operazione',
                'aria_label' => 'Pulsante di conferma',
            ],
            'secondary' => [
                'label' => 'Annulla',
                'tooltip' => 'Annulla l\'operazione',
                'aria_label' => 'Pulsante di annullamento',
            ],
            'danger' => [
                'label' => 'Elimina',
                'tooltip' => 'Elimina l\'elemento selezionato',
                'aria_label' => 'Pulsante di eliminazione',
                'confirmation' => 'Sei sicuro di voler eliminare questo elemento?',
            ],
        ],
        'form' => [
            'input' => [
                'email' => [
                    'label' => 'Indirizzo Email',
                    'placeholder' => 'Inserisci la tua email',
                    'help' => 'L\'email verrà utilizzata per accedere al sistema',
                    'validation' => [
                        'required' => 'L\'indirizzo email è obbligatorio',
                        'email' => 'Inserisci un indirizzo email valido',
                        'unique' => 'Questo indirizzo email è già registrato',
                    ],
                ],
                'password' => [
                    'label' => 'Password',
                    'placeholder' => 'Inserisci la password',
                    'help' => 'La password deve contenere almeno 8 caratteri',
                    'validation' => [
                        'required' => 'La password è obbligatoria',
                        'min' => 'La password deve contenere almeno :min caratteri',
                        'confirmed' => 'Le password non coincidono',
                    ],
                ],
            ],
        ],
    ],
];
```

## Convenzioni di Naming

### Chiavi Descrittive
```php
// ✅ CORRETTO - Chiavi descrittive e organizzate
'components' => [
    'button' => [
        'primary' => [
            'label' => 'Conferma',
        ],
    ],
],

// ❌ ERRATO - Chiavi generiche
'btn_confirm' => 'Conferma',
'btn_cancel' => 'Annulla',
```

### Organizzazione Gerarchica
```php
// ✅ CORRETTO - Struttura gerarchica logica
'pages' => [
    'dashboard' => [
        'title' => 'Dashboard',
        'description' => 'Panoramica generale del sistema',
        'sections' => [
            'overview' => [
                'title' => 'Panoramica',
                'description' => 'Statistiche principali',
            ],
        ],
    ],
],
```

## Tipi di Contenuto

### 1. Etichette (Labels)
```php
'label' => 'Nome Utente',
'label' => 'Indirizzo Email',
'label' => 'Data di Nascita',
```

### 2. Placeholder
```php
'placeholder' => 'Inserisci il tuo nome',
'placeholder' => 'Seleziona una provincia',
'placeholder' => 'Inserisci una descrizione',
```

### 3. Testi di Aiuto (Help Text)
```php
'help' => 'Il nome verrà visualizzato pubblicamente',
'help' => 'La password deve contenere almeno 8 caratteri',
'help' => 'Seleziona la provincia di residenza',
```

### 4. Messaggi di Validazione
```php
'validation' => [
    'required' => 'Questo campo è obbligatorio',
    'email' => 'Inserisci un indirizzo email valido',
    'min' => 'Il campo deve contenere almeno :min caratteri',
    'max' => 'Il campo non può superare :max caratteri',
],
```

### 5. Messaggi di Sistema
```php
'alerts' => [
    'success' => [
        'title' => 'Operazione Completata',
        'message' => 'L\'operazione è stata completata con successo',
    ],
    'error' => [
        'title' => 'Errore',
        'message' => 'Si è verificato un errore durante l\'operazione',
    ],
],
```

## Utilizzo nei Componenti

### Blade Templates
```blade
{{-- Utilizzo diretto --}}
<h1>{{ __('pub_theme::pages.dashboard.title') }}</h1>
<p>{{ __('pub_theme::pages.dashboard.description') }}</p>

{{-- Con parametri --}}
<p>{{ __('pub_theme::common.welcome_user', ['name' => $user->name]) }}</p>

{{-- Per pluralizzazione --}}
<p>{{ trans_choice('pub_theme::common.items_count', $count, ['count' => $count]) }}</p>
```

### Componenti Filament
```php
// In Filament Forms
TextInput::make('email')
    ->label(__('pub_theme::components.form.input.email.label'))
    ->placeholder(__('pub_theme::components.form.input.email.placeholder'))
    ->helperText(__('pub_theme::components.form.input.email.help'))
    ->required()
    ->email()
    ->rules(['required', 'email']);

// In Filament Tables
Tables\Columns\TextColumn::make('name')
    ->label(__('pub_theme::components.table.columns.name.label'))
    ->searchable()
    ->sortable();
```

### Livewire Components
```php
// In componenti Livewire
public function render()
{
    return view('pub_theme::livewire.user-form', [
        'title' => __('pub_theme::pages.users.create.title'),
        'description' => __('pub_theme::pages.users.create.description'),
    ]);
}
```

## Accessibilità e Inclusività

### ARIA Labels
```php
'aria_labels' => [
    'button' => [
        'close' => 'Chiudi finestra',
        'menu' => 'Apri menu di navigazione',
        'search' => 'Cerca nel sito',
        'submit' => 'Invia modulo',
    ],
    'form' => [
        'required_field' => 'Campo obbligatorio',
        'optional_field' => 'Campo opzionale',
    ],
],
```

### Screen Reader Support
```php
'screen_reader' => [
    'loading' => 'Caricamento in corso',
    'loaded' => 'Contenuto caricato',
    'error' => 'Errore durante il caricamento',
    'no_results' => 'Nessun risultato trovato',
    'results_found' => ':count risultati trovati',
],
```

## Pluralizzazione

### Gestione Plurali
```php
'items_count' => '{0} Nessun elemento|{1} Un elemento|[2,*] :count elementi',
'users_count' => '{0} Nessun utente|{1} Un utente|[2,*] :count utenti',
'files_count' => '{0} Nessun file|{1} Un file|[2,*] :count file',
```

### Utilizzo
```blade
{{ trans_choice('pub_theme::common.items_count', $items->count(), ['count' => $items->count()]) }}
```

## Validazione e Messaggi di Errore

### Struttura Completa
```php
'validation' => [
    'required' => 'Il campo :attribute è obbligatorio',
    'email' => 'Il campo :attribute deve essere un indirizzo email valido',
    'min' => [
        'string' => 'Il campo :attribute deve contenere almeno :min caratteri',
        'numeric' => 'Il campo :attribute deve essere almeno :min',
    ],
    'max' => [
        'string' => 'Il campo :attribute non può superare :max caratteri',
        'numeric' => 'Il campo :attribute non può essere maggiore di :max',
    ],
    'unique' => 'Il valore del campo :attribute è già stato utilizzato',
    'confirmed' => 'La conferma del campo :attribute non corrisponde',
],
```

### Attributi Personalizzati
```php
'attributes' => [
    'name' => 'nome',
    'email' => 'indirizzo email',
    'password' => 'password',
    'password_confirmation' => 'conferma password',
    'current_password' => 'password attuale',
    'new_password' => 'nuova password',
],
```

## Temi e Stati

### Messaggi di Stato
```php
'states' => [
    'loading' => [
        'title' => 'Caricamento',
        'message' => 'Attendere prego...',
    ],
    'success' => [
        'title' => 'Successo',
        'message' => 'Operazione completata con successo',
    ],
    'error' => [
        'title' => 'Errore',
        'message' => 'Si è verificato un errore',
    ],
    'warning' => [
        'title' => 'Attenzione',
        'message' => 'Attenzione: alcuni dati potrebbero essere incompleti',
    ],
    'info' => [
        'title' => 'Informazione',
        'message' => 'Informazione importante',
    ],
],
```

### Stati Vuoti
```php
'empty_states' => [
    'no_data' => [
        'title' => 'Nessun dato trovato',
        'description' => 'Non ci sono elementi da visualizzare al momento',
        'action' => 'Crea il primo elemento',
    ],
    'no_results' => [
        'title' => 'Nessun risultato',
        'description' => 'La ricerca non ha prodotto risultati',
        'action' => 'Modifica i criteri di ricerca',
    ],
],
```

## Best Practices

### 1. Completezza
- **SEMPRE** includere label, placeholder e help text
- **SEMPRE** fornire messaggi di validazione
- **SEMPRE** includere ARIA labels per accessibilità

### 2. Coerenza
- Utilizzare terminologia coerente in tutto il sistema
- Mantenere stile di scrittura uniforme
- Seguire le convenzioni di naming

### 3. Inclusività
- Utilizzare linguaggio inclusivo
- Evitare termini tecnici non necessari
- Fornire alternative per utenti con disabilità

### 4. Localizzazione
- Adattare contenuti alla cultura italiana
- Utilizzare formati data/ora italiani
- Considerare differenze regionali

## Esempi Completi

### Pagina Dashboard
```php
'pages' => [
    'dashboard' => [
        'title' => 'Dashboard',
        'description' => 'Panoramica generale del sistema',
        'sections' => [
            'overview' => [
                'title' => 'Panoramica',
                'description' => 'Statistiche principali del sistema',
                'stats' => [
                    'total_users' => [
                        'label' => 'Utenti Totali',
                        'description' => 'Numero totale di utenti registrati',
                    ],
                    'active_users' => [
                        'label' => 'Utenti Attivi',
                        'description' => 'Utenti attivi nell\'ultimo mese',
                    ],
                ],
            ],
            'recent_activity' => [
                'title' => 'Attività Recenti',
                'description' => 'Ultime attività registrate nel sistema',
                'empty_state' => [
                    'title' => 'Nessuna attività recente',
                    'description' => 'Non ci sono attività da visualizzare',
                ],
            ],
        ],
    ],
],
```

### Form di Registrazione
```php
'forms' => [
    'registration' => [
        'title' => 'Registrazione Nuovo Utente',
        'description' => 'Compila i dati per registrare un nuovo utente',
        'fields' => [
            'name' => [
                'label' => 'Nome Completo',
                'placeholder' => 'Inserisci il nome completo',
                'help' => 'Il nome verrà visualizzato pubblicamente',
                'validation' => [
                    'required' => 'Il nome è obbligatorio',
                    'min' => 'Il nome deve contenere almeno :min caratteri',
                ],
            ],
            'email' => [
                'label' => 'Indirizzo Email',
                'placeholder' => 'Inserisci l\'indirizzo email',
                'help' => 'L\'email verrà utilizzata per accedere al sistema',
                'validation' => [
                    'required' => 'L\'indirizzo email è obbligatorio',
                    'email' => 'Inserisci un indirizzo email valido',
                    'unique' => 'Questo indirizzo email è già registrato',
                ],
            ],
        ],
        'actions' => [
            'submit' => [
                'label' => 'Registra Utente',
                'loading' => 'Registrazione in corso...',
                'success' => 'Utente registrato con successo',
                'error' => 'Errore durante la registrazione',
            ],
            'cancel' => [
                'label' => 'Annulla',
                'confirmation' => 'Sei sicuro di voler annullare? Le modifiche andranno perse.',
            ],
        ],
    ],
],
```

## Testing delle Traduzioni

### Test di Completezza
```php
// Test per verificare che tutte le chiavi siano presenti
public function test_all_translation_keys_are_present()
{
    $italianKeys = $this->getTranslationKeys('it');
    $englishKeys = $this->getTranslationKeys('en');
    $germanKeys = $this->getTranslationKeys('de');
    
    $this->assertEquals($italianKeys, $englishKeys);
    $this->assertEquals($italianKeys, $germanKeys);
}
```

### Test di Validazione
```php
// Test per verificare la struttura espansa
public function test_translations_have_expanded_structure()
{
    $translations = require lang_path('it/components.php');
    
    $this->assertArrayHasKey('label', $translations['components']['button']['primary']);
    $this->assertArrayHasKey('placeholder', $translations['components']['form']['input']['email']);
    $this->assertArrayHasKey('help', $translations['components']['form']['input']['email']);
}
```

## Deployment e Manutenzione

### Aggiornamento Traduzioni
```bash
# Verificare chiavi mancanti
php artisan translation:missing-keys --theme=sixteen

# Sincronizzare traduzioni
php artisan translation:sync-keys --theme=sixteen

# Compilare traduzioni per produzione
php artisan translation:compile --theme=sixteen
```

### Cache delle Traduzioni
```bash
# Pulire cache traduzioni
php artisan cache:clear --tags=translations

# Precaricare traduzioni
php artisan translation:preload --theme=sixteen
```

---

**Versione**: 1.0.0  
**Ultimo aggiornamento**: Gennaio 2025  
**Compatibilità**: Laravel 10+, Filament 3.x, Tailwind CSS 3.x 
