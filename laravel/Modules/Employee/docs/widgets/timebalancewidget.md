# TimeBalanceWidget (LE MIE RIMANENZE DI SETTEMBRE)

## Overview
Widget che mostra il saldo delle ore/giorni disponibili per l'utente nel mese corrente. Include ferie, ROL, permessi, banca ore e permessi ex-fs con navigazione tra mesi (Mensile/Annuale).

## UI Components
- **Titolo**: "LE MIE RIMANENZE DI SETTEMBRE" (dinamico per mese)
- **Toggle view**: Tabs "Mensile" / "Annuale"
- **Lista saldi**: Ogni voce mostra:
  - Icona categoria (🏖️ ferie, 🏛️ ROL, etc.)
  - Nome categoria
  - Ore/giorni disponibili con barra progresso visiva
  - Colore indicativo (verde=OK, giallo=attenzione, rosso=esaurito)

## Data Requirements

### Database Tables
- `employee_balances` / `time_allowances`
  - `id`
  - `employee_id` (FK to users)
  - `type` (vacation, rol, sick_leave, bank_hours, ex_fs_permits)
  - `year`
  - `month` (nullable per saldi annuali)
  - `total_hours` / `total_days`
  - `used_hours` / `used_days`
  - `remaining_hours` / `remaining_days`
  - `last_updated`

### Balance Types
- `vacation`: Ferie annuali
- `rol`: ROL (Riduzione Orario Lavoro)
- `sick_leave`: Permessi malattia
- `bank_hours`: Banca ore (straordinari accumulati)
- `ex_fs_permits`: Permessi ex-festività soppresse
- `study_permits`: Permessi studio

### Model: EmployeeBalance
```php
class EmployeeBalance extends Model
{
    public function employee(): BelongsTo
    public function getTypeLabel(): string // Traduzione tipo
    public function getIcon(): string // Icona per UI
    public function getProgressPercentage(): float // % utilizzo
    public function getStatusColor(): string // Verde/Giallo/Rosso
    public function getRemainingFormatted(): string // "8h 53m"
    public function isLowBalance(): bool // < 20% rimanente
    public function scopeForMonth(): Builder
    public function scopeForYear(): Builder
}
```

## Widget Implementation

### Class: TimeBalanceWidget extends XotBaseWidget
- **Sort**: 4 (quinto widget)
- **Polling**: 1h (aggiornamento orario)
- **View**: `employee::filament.widgets.time-balance-widget`

### Methods
- `mount()`: Carica saldi attuali
- `getMonthlyBalances()`: Saldi mese corrente
- `getYearlyBalances()`: Saldi annuali
- `switchView(string $view)`: Toggle mensile/annuale
- `formatDuration(int $minutes)`: Formatta "8h 53m"
- `updateBalances()`: Ricalcola saldi (admin action)

### Properties
- `array $balances`: Saldi attuali
- `string $currentView`: 'monthly' o 'yearly'
- `string $currentMonth`: Mese visualizzato
- `int $currentYear`: Anno di riferimento
- `array $viewTabs`: Opzioni toggle view

## Frontend (Blade Template)

### Layout Structure
```blade
<x-filament-widgets::widget>
    <div class="p-6">
        <!-- Header con toggle -->
        <div class="widget-header">
            <h3>LE MIE RIMANENZE DI {{ strtoupper($currentMonth) }}</h3>
            
            <div class="view-toggle">
                <button 
                    class="tab {{ $currentView === 'monthly' ? 'active' : '' }}"
                    wire:click="switchView('monthly')"
                >
                    Mensile
                </button>
                <button 
                    class="tab {{ $currentView === 'yearly' ? 'active' : '' }}"
                    wire:click="switchView('yearly')"
                >
                    Annuale
                </button>
            </div>
        </div>

        <!-- Lista saldi -->
        <div class="balances-list">
            @foreach($balances as $balance)
                <div class="balance-item">
                    <div class="balance-header">
                        <span class="balance-icon">{!! $balance->icon !!}</span>
                        <span class="balance-name">{{ $balance->type_label }}</span>
                    </div>
                    
                    <div class="balance-amount {{ $balance->status_color }}">
                        {{ $balance->remaining_formatted }}
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div 
                                class="progress-fill {{ $balance->status_color }}"
                                style="width: {{ $balance->progress_percentage }}%"
                            ></div>
                        </div>
                        <span class="progress-text">
                            {{ number_format($balance->progress_percentage, 0) }}% utilizzato
                        </span>
                    </div>
                    
                    @if($balance->isLowBalance())
                        <div class="low-balance-warning">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                            Saldo in esaurimento
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Footer actions -->
        <div class="widget-footer">
            <button wire:click="viewFullReport" class="btn-link">
                Vedi report completo
            </button>
        </div>
    </div>
</x-filament-widgets::widget>
```

### Visual Elements
- **Icons per tipo**:
  - 🏖️ Ferie
  - 🏛️ ROL  
  - 🏥 Banca ore
  - 📚 Perm. ex-fs
  - 🕐 Permessi
- **Colori status**:
  - Verde: > 50% rimanente
  - Giallo: 20-50% rimanente  
  - Rosso: < 20% rimanente

## Business Logic

### Balance Calculation
```php
public function calculateRemainingBalance(): float
{
    return $this->total_hours - $this->used_hours;
}

public function getProgressPercentage(): float 
{
    if ($this->total_hours <= 0) return 0;
    return ($this->used_hours / $this->total_hours) * 100;
}

public function getStatusColor(): string
{
    $percentage = $this->getProgressPercentage();
    
    if ($percentage >= 80) return 'danger'; // Rosso
    if ($percentage >= 50) return 'warning'; // Giallo  
    return 'success'; // Verde
}
```

### Duration Formatting
```php
public function formatDuration(int $minutes): string
{
    if ($minutes < 0) {
        return '-' . $this->formatDuration(abs($minutes));
    }
    
    $hours = intval($minutes / 60);
    $mins = $minutes % 60;
    
    if ($hours === 0) {
        return "{$mins}m";
    }
    
    return "{$hours}h " . ($mins > 0 ? "{$mins}m" : "");
}
```

### Monthly vs Yearly Logic
- **Mensile**: Mostra progressione mese corrente
- **Annuale**: Saldo totale anno con proiezione
- **Auto-switch**: Passa ad annuale se mese terminato

## Integration Points
- **Time Tracking**: Aggiorna ore lavorate
- **Leave Requests**: Decrementa saldi approvati  
- **HR Module**: Gestione assegnazioni annuali
- **Payroll**: Integrazione con busta paga

## Actions & Interactions
- `viewFullReport()`: Report dettagliato utilizzo
- `requestLeave()`: Quick link richiesta ferie
- `switchView()`: Toggle mensile/annuale
- `exportBalance()`: Export PDF saldi

## Data Refresh Strategy
- **Real-time**: Update dopo approvazioni richieste
- **Batch**: Ricalcolo notturno saldi
- **Manual**: Button refresh per admin
- **Cache**: Cache 1h per performance

## Notifications & Alerts
- **Low Balance**: Alert quando < 20%
- **Expiring**: Notifica ferie in scadenza
- **Negative**: Warning per saldi negativi
- **Renewal**: Info rinnovo annuale

## Permissions & Access
- **Employee**: Solo i propri saldi
- **Manager**: Saldi del team (read-only)
- **HR**: Tutti i saldi + modifica
- **Admin**: Accesso completo + reset

## Responsive Design
- **Desktop**: Layout a colonne
- **Tablet**: Stack verticale
- **Mobile**: Cards compatte
- **Accessibility**: Screen reader support

## Testing Strategy
- Unit test calcoli saldi
- Feature test toggle views
- Integration test aggiornamenti automatici
- Performance test con molti dipendenti