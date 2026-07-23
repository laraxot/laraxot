# TimeTrackingWidget - Specifica Implementazione

## Overview

Widget per la timbratura dipendenti con layout specifico a tre colonne per un'interfaccia utente ottimale. Segue rigorosamente le convenzioni Laraxot e estende XotBaseWidget.

## Layout Specificato

### Struttura a Tre Colonne

```
┌─────────────┬─────────────────────┬─────────────┐
│   SINISTRA  │       CENTRO        │   DESTRA    │
│             │                     │             │
│    09:21    │   Sessione attiva   │  ┌─────────┐ │
│   lunedì    │      •08:02        │  │ Timbra  │ │
│1 settembre  │                     │  │ uscita  │ │
│    2025     │                     │  └─────────┘ │
└─────────────┴─────────────────────┴─────────────┘
```

### Colonna Sinistra - Orario e Data
- **Orario corrente**: Grande formato HH:MM (es. 09:21)
- **Giorno della settimana**: In italiano (es. lunedì)
- **Data completa**: Formato lungo (es. 1 settembre 2025)
- **Aggiornamento**: Ogni secondo in tempo reale

### Colonna Centro - Timbature Giornata
- **Titolo**: "Sessione attiva" quando timbrato in entrata
- **Lista timbature**: Visualizza tutte le timbature del giorno corrente
- **Formato ora**: HH:MM con bullet point (•)
- **Stati possibili**:
  - "Nessuna timbratura" - se non ha ancora timbrato
  - "Sessione attiva" - se ha timbrato entrata ma non uscita
  - "Giornata completata" - se ha timbrato sia entrata che uscita

### Colonna Destra - Azione Principale
- **Pulsante dinamico**: Cambia in base allo stato attuale
- **Timbra entrata**: Pulsante verde quando non è timbrato
- **Timbra uscita**: Pulsante rosso quando è in sessione attiva
- **Dimensioni**: Pulsante grande e touch-friendly

## Implementazione Tecnica

### XotBaseWidget Compliance

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Modules\Employee\Models\WorkHour;
use Carbon\Carbon;

class TimeTrackingWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.time-tracking-widget';
    protected static ?string $pollingInterval = '1s'; // Aggiornamento ogni secondo
    
    // Proprietà per le tre colonne
    public string $currentTime = '';
    public string $currentDate = '';
    public array $todayEntries = [];
    public string $sessionStatus = '';
    public bool $canClockOut = false;
}
```

### Schema Database

Utilizza la tabella `time_entries` come specificato in `work_hour.md`:

```sql
- id: Primary key
- employee_id: Foreign key to employees
- type: ENUM('clock_in', 'clock_out', 'break_start', 'break_end')
- timestamp: DATETIME exact time
- status: ENUM('pending', 'approved', 'rejected')
```

### Logica di Business

1. **Determinazione Stato Sessione**:
   - Query ultima timbratura del giorno corrente
   - Se `clock_in` senza `clock_out` → "Sessione attiva"
   - Se nessuna timbratura → "Nessuna timbratura"
   - Se `clock_out` → "Giornata completata"

2. **Pulsante Dinamico**:
   - Stato iniziale → "Timbra entrata" (verde)
   - Dopo clock_in → "Timbra uscita" (rosso)
   - Dopo clock_out → disabilitato o "Nuova sessione"

3. **Aggiornamento Real-time**:
   - Polling ogni secondo per l'orario
   - Aggiornamento immediato dopo azioni di timbratura

## Vista Blade

### Struttura HTML

```blade
<x-filament-widgets::widget>
    <div class="grid grid-cols-3 gap-6 h-32">
        {{-- Colonna Sinistra: Orario e Data --}}
        <div class="flex flex-col justify-center items-center bg-gray-50 rounded-lg p-4">
            <div class="text-3xl font-bold text-gray-900">{{ $currentTime }}</div>
            <div class="text-sm text-gray-600 text-center">{{ $currentDate }}</div>
        </div>

        {{-- Colonna Centro: Timbature --}}
        <div class="flex flex-col justify-center bg-gray-50 rounded-lg p-4">
            <div class="text-lg font-semibold text-gray-800 mb-2">{{ $sessionStatus }}</div>
            <div class="space-y-1">
                @foreach($todayEntries as $entry)
                    <div class="text-sm text-gray-700">
                        <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        {{ $entry['time'] }}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Colonna Destra: Pulsante Azione --}}
        <div class="flex items-center justify-center bg-gray-50 rounded-lg p-4">
            <button 
                wire:click="{{ $canClockOut ? 'clockOut' : 'clockIn' }}"
                class="px-6 py-3 rounded-lg font-semibold text-white {{ $canClockOut ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}"
            >
                {{ $canClockOut ? __('employee::time-tracking.clock_out') : __('employee::time-tracking.clock_in') }}
            </button>
        </div>
    </div>
</x-filament-widgets::widget>
```

### Stili CSS

```css
.time-tracking-widget {
    min-height: 128px; /* h-32 */
}

.time-display {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', monospace;
    letter-spacing: -0.025em;
}

.clock-button {
    min-width: 120px;
    min-height: 48px;
    transition: all 0.2s ease;
}

.clock-button:active {
    transform: scale(0.95);
}
```

## Traduzioni

### File: `lang/it/time-tracking.php`

```php
return [
    'widget' => [
        'title' => 'Timbratura',
        'current_time' => 'Ora Attuale',
        'current_date' => 'Data Corrente',
    ],
    'session' => [
        'no_entries' => 'Nessuna timbratura',
        'active' => 'Sessione attiva',
        'completed' => 'Giornata completata',
    ],
    'actions' => [
        'clock_in' => 'Timbra entrata',
        'clock_out' => 'Timbra uscita',
        'break_start' => 'Inizia pausa',
        'break_end' => 'Fine pausa',
    ],
    'messages' => [
        'clock_in_success' => 'Entrata registrata con successo',
        'clock_out_success' => 'Uscita registrata con successo',
        'already_clocked_in' => 'Hai già timbrato l\'entrata',
        'must_clock_in_first' => 'Devi prima timbrare l\'entrata',
    ],
    'days' => [
        'monday' => 'lunedì',
        'tuesday' => 'martedì',
        'wednesday' => 'mercoledì',
        'thursday' => 'giovedì',
        'friday' => 'venerdì',
        'saturday' => 'sabato',
        'sunday' => 'domenica',
    ],
    'months' => [
        'january' => 'gennaio',
        'february' => 'febbraio',
        'march' => 'marzo',
        'april' => 'aprile',
        'may' => 'maggio',
        'june' => 'giugno',
        'july' => 'luglio',
        'august' => 'agosto',
        'september' => 'settembre',
        'october' => 'ottobre',
        'november' => 'novembre',
        'december' => 'dicembre',
    ],
];
```

## Responsività Mobile

### Adattamenti per Schermi Piccoli

```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
    {{-- Su mobile: stack verticale --}}
    {{-- Su tablet+: layout a 3 colonne --}}
</div>
```

### Ottimizzazioni Touch

- Pulsanti con area di tocco minima 44px
- Spaziatura adeguata tra elementi interattivi
- Feedback visivo immediato al tocco

## Performance e Caching

### Ottimizzazioni

1. **Query Efficienti**:
   - Una sola query per le timbature del giorno
   - Indici appropriati sulla tabella time_entries

2. **Aggiornamento Selettivo**:
   - Solo i dati che cambiano vengono aggiornati
   - Evitare re-render completo del widget

3. **Caching**:
   - Cache delle traduzioni giornaliere (giorni/mesi)
   - Cache della configurazione widget

## Testing

### Unit Tests

```php
class TimeTrackingWidgetTest extends TestCase
{
    /** @test */
    public function it_shows_current_time_and_date(): void
    {
        // Test formato orario e data
    }

    /** @test */
    public function it_shows_correct_session_status(): void
    {
        // Test stati sessione (attiva/completata/nessuna)
    }

    /** @test */
    public function it_handles_clock_in_action(): void
    {
        // Test azione timbra entrata
    }

    /** @test */
    public function it_handles_clock_out_action(): void
    {
        // Test azione timbra uscita
    }
}
```

### Feature Tests

```php
class TimeTrackingFeatureTest extends TestCase
{
    /** @test */
    public function employee_can_clock_in_and_out(): void
    {
        // Test completo del flusso di timbratura
    }
}
```

## Sicurezza

### Controlli di Autorizzazione

1. **Verifica Utente**: Solo utenti autenticati possono timbrare
2. **Verifica Employee**: Utente deve avere profilo dipendente associato
3. **Validazione Timestamp**: Prevenzione manipolazione orari
4. **Rate Limiting**: Limite tentativi timbratura per minuto

### Audit Trail

- Log di tutte le azioni di timbratura
- Registrazione IP e device info
- Tracciamento modifiche e approvazioni

## Integrazione Dashboard

### Registrazione Widget

```php
// Dashboard.php - Employee module
protected function getHeaderWidgets(): array
{
    return [
        TimeTrackingWidget::class,
        // altri widgets...
    ];
}
```

### Configurazione

```php
// config/employee.php
'time_tracking_widget' => [
    'polling_interval' => '1s',
    'show_seconds' => true,
    'date_format' => 'l j F Y', // formato data lungo italiano
    'time_format' => 'H:i:s',
    'enable_breaks' => true,
    'require_photo' => false,
    'require_gps' => false,
],
```

## Manutenzione

### Aggiornamenti Regolari

1. **Traduzioni**: Verifica accuratezza traduzioni italiane
2. **Performance**: Monitoraggio query e polling
3. **UX**: Feedback utente e ottimizzazioni interfaccia
4. **Sicurezza**: Review controlli autorizzazione

### Logging e Monitoring

- Log errori timbratura
- Metriche performance widget
- Statistiche utilizzo funzionalità
- Alert per anomalie (es. sessioni troppo lunghe)

---

**Questo documento definisce l'implementazione completa del TimeTrackingWidget seguendo rigorosamente le specifiche di layout fornite e le convenzioni Laraxot.**
