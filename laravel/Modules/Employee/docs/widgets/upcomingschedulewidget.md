# UpcomingScheduleWidget (PROSSIMI 7 GIORNI)

## Overview
Widget che mostra gli eventi, assenze e impegni dei prossimi 7 giorni. Include filtri per team/progetto e visualizzazione compatta degli eventi giornalieri.

## UI Components
- **Titolo**: "PROSSIMI 7 GIORNI"
- **Filtro dropdown**: Selezione team/progetto (es. "SVILUPPO")
- **Link azioni**: "Vedi presenze" per navigazione completa
- **Sezione eventi**: 
  - Indicatore giorno (OGGI, DOMANI, etc.)
  - Lista eventi per giorno con:
    - Avatar utente
    - Nome dipendente
    - Tipo evento (Assenza, Smart Working, Trasferte)
    - Orario (es. "dalle 14:00 alle 18:00")

## Data Requirements

### Database Tables
- `absences` / `leave_requests`
  - `id`
  - `employee_id`
  - `type` (sick, vacation, smart_working, business_trip)
  - `start_date`
  - `end_date`
  - `start_time`
  - `end_time`
  - `status` (approved, pending, rejected)
  - `notes`

- `teams` / `departments`
  - `id`
  - `name`
  - `code` (es. SVILUPPO)

### Models
```php
class Absence extends Model
{
    public function employee(): BelongsTo
    public function team(): BelongsTo
    public function getTypeLabeltAttribute(): string // Traduzione tipo
    public function getTimeRangeAttribute(): string // "dalle 09:00 alle 13:00"
    public function scopeUpcoming(): Builder // Prossimi 7 giorni
    public function scopeByTeam(): Builder
}

class Team extends Model
{
    public function employees(): HasMany
    public function absences(): HasManyThrough
}
```

## Widget Implementation

### Class: UpcomingScheduleWidget extends XotBaseWidget
- **Sort**: 2 (terzo widget)
- **Polling**: 5min (aggiornamento eventi)
- **View**: `employee::filament.widgets.upcoming-schedule-widget`

### Methods
- `mount()`: Inizializza filtri e carica eventi
- `getUpcomingEvents()`: Eventi prossimi 7 giorni
- `getTeamOptions()`: Opzioni dropdown team
- `filterByTeam(string $team)`: Filtra eventi per team
- `getDayLabel(Carbon $date)`: "OGGI", "DOMANI", "LUNEDÌ 9"

### Properties
- `array $upcomingEvents`: Eventi raggruppati per giorno
- `string $selectedTeam`: Team filtro attivo
- `array $teamOptions`: Opzioni disponibili
- `int $daysAhead`: Giorni da mostrare (default: 7)

## Frontend (Blade Template)

### Layout Structure
```blade
<x-filament-widgets::widget>
    <div class="p-6">
        <!-- Header con filtro -->
        <div class="header-section">
            <h3>PROSSIMI 7 GIORNI</h3>
            <select wire:model="selectedTeam">
                @foreach($teamOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <a href="{{ route('filament.presenze') }}">Vedi presenze</a>
        </div>

        <!-- Eventi per giorno -->
        @forelse($upcomingEvents as $day => $events)
            <div class="day-section">
                <h4 class="day-label">{{ $day }}</h4>
                
                @foreach($events as $event)
                    <div class="event-item">
                        <img src="{{ $event->employee->avatar }}" class="avatar" />
                        <div class="event-details">
                            <span class="name">{{ $event->employee->name }}</span>
                            <span class="type-badge">{{ $event->type_label }}</span>
                            <span class="time">{{ $event->time_range }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="empty-state">
                Nessun evento nei prossimi 7 giorni
            </div>
        @endforelse
    </div>
</x-filament-widgets::widget>
```

### Event Types & Styling
- **Assenza**: Badge rosso, icona 🏠
- **Smart Working**: Badge blu, icona 💻
- **Trasferte**: Badge verde, icona ✈️
- **Ferie**: Badge giallo, icona 🏖️

## Business Logic

### Data Processing
1. **Query eventi**: Prossimi 7 giorni dalla data corrente
2. **Filtro team**: Se selezionato, mostra solo dipendenti team
3. **Raggruppamento**: Eventi raggruppati per data
4. **Ordinamento**: Cronologico per data, poi per orario

### Day Labels Logic
```php
private function getDayLabel(Carbon $date): string
{
    if ($date->isToday()) return 'OGGI';
    if ($date->isTomorrow()) return 'DOMANI';
    
    return strtoupper($date->locale('it')->isoFormat('dddd D'));
}
```

### Team Filtering
- Default: Mostra tutti i team
- Se utente ha team assegnato: Preseleziona il suo team
- Manager: Può vedere tutti i team
- HR: Accesso completo

## Integration Points
- **Attendance System**: Link a presenze complete
- **Calendar Module**: Sincronizzazione eventi
- **HR Module**: Approvazione richieste
- **Team Management**: Gestione team/progetti

## Actions
- `viewFullCalendar()`: Apre calendario completo
- `filterByTeam(team)`: Filtra per team
- `exportSchedule()`: Esporta planning
- `requestLeave()`: Quick link richiesta assenza

## Permissions
- **Employee**: Vede eventi del proprio team
- **Manager**: Vede eventi team gestiti
- **HR**: Vede tutti gli eventi
- **Admin**: Accesso completo

## Performance Considerations
- **Caching**: Cache eventi per 5 minuti
- **Lazy Loading**: Carica avatar on-demand
- **Query Optimization**: Join ottimizzati
- **Pagination**: Max 50 eventi per vista

## Testing Strategy
- Unit test filtri team
- Integration test query eventi
- Feature test UI interactions
- Performance test con grandi dataset