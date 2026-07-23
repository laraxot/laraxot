# WeeklyTimeTableWidget - Analisi Completa dall'Immagine dipendentincloud.it

## 📊 Analisi Visuale dell'Immagine

### Screenshot Reference
**URL**: `https://secure.dipendentincloud.it/it/app/timestamps/list`  
**Data**: Screenshot fornito dall'utente  
**Contenuto**: Interfaccia completa gestione timbrature settimanale

## 🎯 Elementi Identificati nell'Interfaccia

### 1. Header Navigation Bar
```
┌─────────────────────────────────────────────────────────────┐
│ Settimana corrente • ◄ 01 set 2025 → 07 set 2025 📅 ► │ 📥 Esporta │
└─────────────────────────────────────────────────────────────┘
```

**Elementi**:
- Dropdown "Settimana corrente" 
- Frecce navigazione settimana (◄ ►)
- Range date display: "01 set 2025 → 07 set 2025"
- Icona calendario per date picker
- Pulsante "Esporta" con icona download

### 2. Employee Summary Table
```
┌──────────────────────────────────────────────────────────────────────────────────────┐
│ Dipendente    │ Lavorate │ Aggiunte │ Ridotte │ Contratto │ Lun01│Mar02│...│Dom07 │
├──────────────────────────────────────────────────────────────────────────────────────┤
│ 👤 Sottana Marco │  11h 35m │ Nessuna  │ Nessuna │   28h     │  8h  │ 4h  │...│  0h  │
│    SVILUPPO      │          │          │         │           │      │     │   │     │
└──────────────────────────────────────────────────────────────────────────────────────┘
```

**Elementi**:
- Avatar dipendente (👤)
- Nome: "Sottana Marco"
- Dipartimento: "SVILUPPO"
- Summary ore:
  - Lavorate: "11h 35m"
  - Aggiunte: "Nessuna" 
  - Ridotte: "Nessuna"
  - Contratto: "28h"
- Colonne giorni settimana con ore giornaliere

### 3. Day Status Indicators
```
Lun 01: 8h
Mar 02: 4h  
Mer 03: 4h
Gio 04: 8h
Ven 05: 4h
Sab 06: 0h
Dom 07: 0h
```

**Con indicatori stato**:
- 🟠 "in corso" (arancione) - per Mar 02
- ⚪ Completato (grigio) - per altri giorni

### 4. Timeline Visualization (Parte Inferiore)
```
06:00 ├─────────────────────────────────────────────────────────┤
08:00 ├─█████─────────────────████████████████████████████████──┤ 
10:00 ├─█████─────────────────████████████████████████████████──┤
12:00 ├─█████─────────────────████████████████████████████████──┤
14:00 ├───────────────────────████████████████████████████████──┤
16:00 ├─██████████████████████████████████████████████████████──┤
18:00 ├─██████████████████████████████████████████████████████──┤
20:00 ├─────────────────────────────────────────────────────────┤
```

**Elementi Timeline**:
- Fasce orarie verticali: 06:00, 08:00, 10:00, 12:00, 14:00, 16:00, 18:00, 20:00
- Blocchi colorati per sessioni di lavoro:
  - **Verde**: 08:02-13:02 (sessione mattina)
  - **Arancione**: 14:26-17:40 (sessione pomeriggio)
- Linee rosse/arancioni per delimitare orari di lavoro
- Giorni settimana come colonne

### 5. Time Entry Details
**Sessioni Identificate**:
- **Lunedì 01**: 08:02-13:02 (5h) + 14:26-17:40 (3h 14m) = 8h 14m
- **Martedì 02**: Sessione in corso (indicatore arancione)
- **Altri giorni**: Sessioni complete o vuote

## 🏗️ Widget Architecture Required

### Core Widget Class
```php
namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class WeeklyTimeTableWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.weekly-time-table';
    
    protected static ?int $sort = 1;
    
    protected static ?string $maxHeight = '800px';
    
    // State management
    #[Reactive]
    public Carbon $weekStart;
    
    #[Reactive] 
    public Carbon $weekEnd;
    
    public bool $showToleranceThreshold = false;
    
    // Computed data
    public array $weekData = [];
    public array $timelineData = [];
    public array $employeeInfo = [];
    public array $summaryData = [];
}
```

### Required Actions Integration

#### 1. BuildWeeklyTimeTableAction ✓
- Già implementata
- Costruisce dati tabella settimanale
- Calcola ore per giorno

#### 2. BuildTimelineVisualizationAction (DA CREARE)
```php
class BuildTimelineVisualizationAction
{
    use QueueableAction;
    
    public function execute(int $userId, Carbon $weekStart, Carbon $weekEnd): array
    {
        return [
            'timeSlots' => $this->buildTimeSlots(),           // 06:00-20:00 slots
            'sessionBlocks' => $this->buildSessionBlocks(),   // Blocchi colorati
            'dayColumns' => $this->buildDayColumns(),         // Colonne giorni
            'workingHours' => $this->getWorkingHours(),       // Orari di lavoro standard
        ];
    }
}
```

#### 3. GetWeekSummaryDataAction (DA CREARE)
```php
class GetWeekSummaryDataAction
{
    use QueueableAction;
    
    public function execute(int $userId, Carbon $weekStart, Carbon $weekEnd): array
    {
        return [
            'workedHours' => '11h 35m',      // Dall'immagine
            'addedHours' => 'Nessuna',       // Dall'immagine  
            'reducedHours' => 'Nessuna',     // Dall'immagine
            'contractHours' => '28h',        // Dall'immagine
            'weeklyTarget' => 40,            // Ore settimanali target
            'completionRate' => 0.29,        // 11.58/40 = 29%
        ];
    }
}
```

## 🎨 UI/UX Implementation Details

### Color Scheme (dall'immagine)
- **Verde**: #10B981 (sessioni completate mattina)
- **Arancione**: #F59E0B (sessioni in corso/pomeriggio) 
- **Grigio**: #6B7280 (orari non lavorativi)
- **Blu**: #3B82F6 (elementi di navigazione)

### Typography
- **Font principale**: Inter UI (come dipendentincloud.it)
- **Numeri ore**: Font monospace per allineamento
- **Intestazioni**: Font medium, uppercase

### Layout Structure
```blade
{{-- Widget container --}}
<div class="bg-white rounded-lg shadow-sm border">
    {{-- Header con navigazione --}}
    <div class="border-b p-4">
        {{-- Navigazione settimana --}}
    </div>
    
    {{-- Tabella principale --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            {{-- Header giorni --}}
            <thead>
                <tr>
                    <th>Dipendente</th>
                    <th>Lavorate</th>
                    <th>Aggiunte</th>
                    <th>Ridotte</th> 
                    <th>Contratto</th>
                    @foreach($weekDays as $day)
                        <th>{{ $day['name'] }}<br>{{ $day['hours'] }}</th>
                    @endforeach
                </tr>
            </thead>
            
            {{-- Riga dipendente --}}
            <tbody>
                <tr>
                    <td>{{-- Info dipendente --}}</td>
                    <td>{{-- Summary ore --}}</td>
                    @foreach($weekDays as $day)
                        <td>{{-- Ore giorno + status --}}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    
    {{-- Timeline visualization --}}
    <div class="border-t">
        {{-- Griglia timeline con fasce orarie --}}
        <div class="timeline-grid">
            {{-- Implementazione complessa timeline --}}
        </div>
    </div>
</div>
```

## 🚀 Advanced Features dall'Immagine

### 1. Interactive Timeline
- **Zoom levels**: Orari dettagliati vs overview
- **Hover effects**: Tooltip con dettagli sessione
- **Click interactions**: Click per modificare timbratura
- **Visual indicators**: Colori per stati diversi

### 2. Status Management
- **In corso**: Sessione attiva (arancione con animazione)
- **Completato**: Sessione finita (verde)
- **Assente**: Nessuna timbratura (grigio)
- **Anomalia**: Timbrature incomplete (rosso)

### 3. Data Aggregation
- **Summary settimanale**: Totali ore lavorate/contrattuali
- **Daily breakdown**: Ore per singolo giorno
- **Variance calculation**: Differenza tra lavorate e contrattuali
- **Compliance indicators**: Soglie di tolleranza

## 📋 Implementation Checklist

### Widget Structure
- [ ] Crea WeeklyTimeTableWidget class
- [ ] Implementa view Blade complessa
- [ ] Integra Actions per dati
- [ ] Replica esatta interfaccia immagine

### Actions Required  
- [x] BuildWeeklyTimeTableAction (esistente)
- [ ] BuildTimelineVisualizationAction (da creare)
- [ ] GetWeekSummaryDataAction (da creare)
- [x] GetCurrentEmployeeDataAction (esistente)
- [x] ExportTimeDataAction (esistente)

### Page Integration
- [ ] Semplifica WorkHoursPage come plugin container
- [ ] Integra WeeklyTimeTableWidget nella page
- [ ] Mantieni TimeClockWidget per timbratura rapida
- [ ] Testa integrazione completa

### Styling & UX
- [ ] Replica colori esatti dall'immagine
- [ ] Implementa timeline visualization
- [ ] Aggiungi animazioni per stati "in corso"
- [ ] Responsive design per mobile

---

*Analisi completata: 2025-01-06*  
*Fonte: Screenshot dipendentincloud.it*  
*Target: Replica esatta funzionalità e UI*
