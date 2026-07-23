# Time Tracking Widget - Specifiche Implementazione

## Panoramica

Il TimeTrackingWidget è un widget dashboard che replica esattamente l'interfaccia mostrata nell'immagine utente, fornendo:
- Visualizzazione ora corrente e data (sinistra)
- Elenco timbrature della giornata (centro)
- Pulsante azione timbratura (destra)

## Layout Specifico dall'Immagine

### Struttura a 3 Colonne

#### 🕘 Colonna Sinistra - Ora e Data
```
09:21
lunedì 1 settembre 2025
```
- **Ora corrente**: Formato HH:mm, aggiornamento real-time
- **Data completa**: Giorno della settimana + data estesa in italiano
- **Styling**: Testo grande e prominente

#### 📋 Colonna Centro - Timbrature Giornaliere
```
Sessione attiva
● 08:02
```
- **Stato sessione**: "Sessione attiva" con pallino verde
- **Elenco timbrature**: Ora di ogni timbratura della giornata
- **Indicatori visivi**: Pallino colorato per stato (verde = attivo)

#### 🔴 Colonna Destra - Azione Corrente
```
🔴 Timbra uscita
```
- **Pulsante dinamico**: Cambia in base allo stato (entrata/uscita)
- **Colore semantico**: Rosso per uscita, verde per entrata
- **Icona**: Pallino colorato per immediata riconoscibilità

## Logica di Business

### Stati del Widget
1. **Non iniziato**: Nessuna timbratura oggi → Pulsante "Timbra entrata" (verde)
2. **Attivo**: Ultima timbratura = entrata → Pulsante "Timbra uscita" (rosso)
3. **Pausa**: Ultima timbratura = inizio pausa → Pulsante "Fine pausa" (giallo)
4. **Completato**: Ultima timbratura = uscita → Pulsante "Timbra entrata" (verde)

### Dati Utilizzati
- **User corrente**: `Auth::user()` per identificare il dipendente
- **Employee**: Relazione `user_id` per trovare il profilo dipendente
- **WorkHour**: Timbrature della giornata corrente filtrate per `employee_id`
- **full_name**: Mutator esistente `first_name + last_name` (NON nomi mock)

## Implementazione Tecnica

### Estensione Base
```php
class TimeTrackingWidget extends XotBaseWidget
{
    // Implementazione seguendo regole Laraxot
}
```

### Proprietà Vista
```php
protected static string $view = 'employee::filament.widgets.time-tracking-widget';
```

### Metodi Obbligatori
- `getFormSchema()`: Richiesto da XotBaseWidget (array vuoto per questo widget)
- `mount()`: Inizializzazione dati widget
- `updateTimeData()`: Aggiornamento stato in real-time
- `clockIn()`: Azione timbratura entrata
- `clockOut()`: Azione timbratura uscita

### Query Performance
```php
// Query ottimizzata per timbrature giornaliere
$todayEntries = WorkHour::where('employee_id', $employee->id)
    ->whereDate('timestamp', today())
    ->orderBy('timestamp', 'asc')
    ->get();
```

## Vista Blade

### Layout Grid
```blade
<div class="grid grid-cols-3 gap-6 items-center">
    {{-- Colonna sinistra: Ora e data --}}
    <div class="text-center">
        {{-- Ora corrente --}}
        {{-- Data estesa --}}
    </div>
    
    {{-- Colonna centro: Timbrature --}}
    <div class="space-y-2">
        {{-- Stato sessione --}}
        {{-- Lista timbrature --}}
    </div>
    
    {{-- Colonna destra: Azione --}}
    <div class="text-center">
        {{-- Pulsante dinamico --}}
    </div>
</div>
```

### Componenti Interattivi
- **Real-time clock**: `wire:poll.1s` per aggiornamento ora
- **Session polling**: `wire:poll.30s` per stato sessione
- **Action buttons**: `wire:click` per timbrature
- **Status indicators**: Pallini colorati CSS

## Traduzioni Richieste

### File: `lang/it/widgets.php`
```php
'time_tracking' => [
    'title' => 'Timbratura',
    'session_active' => 'Sessione attiva',
    'session_completed' => 'Giornata completata',
    'session_not_started' => 'Giornata non iniziata',
    'clock_in' => 'Timbra entrata',
    'clock_out' => 'Timbra uscita',
    'start_break' => 'Inizia pausa',
    'end_break' => 'Fine pausa',
    'current_time' => 'Ora attuale',
    'today_entries' => 'Timbrature di oggi',
    'no_entries' => 'Nessuna timbratura oggi',
]
```

## Styling CSS

### Colori Semantici
- **Verde**: Entrata, stato attivo, successo
- **Rosso**: Uscita, stop, attenzione
- **Giallo**: Pausa, warning, in attesa
- **Grigio**: Neutro, completato, inattivo

### Responsive Design
- **Desktop**: Layout 3 colonne
- **Tablet**: Layout 2 colonne (ora+azione, timbrature sotto)
- **Mobile**: Layout singola colonna verticale

### Animazioni
- **Pallino attivo**: Pulse animation per sessione attiva
- **Hover effects**: Sui pulsanti di azione
- **Transizioni**: Smooth per cambio stato

## Pattern Real-time

### Polling Strategy
```php
// Aggiornamento ora ogni secondo
wire:poll.1s="updateCurrentTime"

// Aggiornamento stato ogni 30 secondi  
wire:poll.30s="updateTimeData"
```

### State Management
```php
public string $currentTime = '';
public bool $isClockedIn = false;
public array $todayEntries = [];
public string $sessionStatus = 'not_started';
```

## Validazione e Sicurezza

### Controlli di Accesso
- Verificare che l'utente sia autenticato
- Verificare che esista un profilo Employee
- Validare che l'utente possa timbrare (permessi)

### Validazione Business
- Non permettere doppio clock-in
- Non permettere clock-out senza clock-in
- Gestire pause solo durante sessioni attive
- Logging di tutte le azioni per audit

## Testing Strategy

### Test Cases
1. **Visualizzazione ora**: Verifica formato e aggiornamento
2. **Stati sessione**: Test tutti gli stati possibili
3. **Azioni timbratura**: Test clock-in/out con validazioni
4. **Gestione errori**: Test scenari di errore
5. **Real-time updates**: Test polling e aggiornamenti

### Pattern Test (DatabaseTransactions)
```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

describe('TimeTrackingWidget', function () {
    uses(DatabaseTransactions::class);
    
    // Test implementation
});
```

## Integrazione Dashboard

### Posizione nel Dashboard
Il widget deve essere il PRIMO nella lista `getHeaderWidgets()` per massima visibilità:

```php
protected function getHeaderWidgets(): array
{
    return [
        \Modules\Employee\Filament\Widgets\TimeTrackingWidget::class, // PRIMO
        \Modules\Employee\Filament\Widgets\EmployeeOverviewWidget::class,
        // Altri widget...
    ];
}
```

### Dimensioni Widget
- **Larghezza**: Full width per layout 3 colonne
- **Altezza**: Compatta per non occupare troppo spazio
- **Responsive**: Adattabile a schermi piccoli

---

**Basato su**: Immagine interfaccia utente fornita
**Implementazione**: Widget interattivo real-time
**Priorità**: Alta - Widget principale per dipendenti
**Status**: Da implementare seguendo questa specifica

## Collegamenti

- [WorkHour Model](../architecture/model_architecture.md)
- [Time Tracking System](../features/work_hour.md)
- [Widget Guidelines](../implementation/widget_guidelines.md)
- [Testing Standards](../testing/testing_standards.md)

*Ultimo aggiornamento: Gennaio 2025*