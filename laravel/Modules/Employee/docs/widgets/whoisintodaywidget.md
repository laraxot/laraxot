# WhoIsInTodayWidget (CHI C'È OGGI)

## Overview
Widget che mostra la presenza odierna dei dipendenti con filtro per team/progetto. Visualizza contatori presenza/assenza e dettagli staff presente con avatar e informazioni.

## UI Components
- **Titolo**: "CHI C'È OGGI" 
- **Filtro dropdown**: Selezione team (es. "SVILUPPO")
- **Link azione**: "Vedi dettaglio" per vista completa
- **Contatori presenza**:
  - Badge verde: "13 presenti" con icone dipendenti
  - Badge rosso: "2 assenti" con icone dipendenti
- **Lista presenti**: Griglia avatar con nomi visibili al hover

## Data Requirements

### Database Tables
- `daily_attendance` / `attendance_status`
  - `id`
  - `employee_id` (FK to users)
  - `date` (oggi)
  - `status` (present, absent, late, early_leave, remote)
  - `check_in_time`
  - `check_out_time` 
  - `location` (office, remote, client_site)
  - `notes`

- `employee_teams` / `team_assignments`
  - `employee_id`
  - `team_id`
  - `role` (member, lead, manager)
  - `is_active`

### Attendance Statuses
- `present`: In ufficio
- `remote`: Smart working
- `absent`: Assente (ferie, malattia)
- `late`: In ritardo
- `early_leave`: Uscita anticipata
- `client_site`: Presso cliente

### Models
```php
class DailyAttendance extends Model
{
    public function employee(): BelongsTo
    public function team(): BelongsTo
    public function getStatusLabel(): string
    public function getStatusColor(): string
    public function getLocationIcon(): string
    public function scopeToday(): Builder
    public function scopePresent(): Builder
    public function scopeAbsent(): Builder
}

class Employee extends User
{
    public function attendance(): HasMany
    public function teams(): BelongsToMany
    public function todayAttendance(): HasOne
    public function getAvatarUrlAttribute(): string
    public function getCurrentLocationAttribute(): string
}
```

## Widget Implementation

### Class: WhoIsInTodayWidget extends XotBaseWidget
- **Sort**: 5 (ultimo widget)
- **Polling**: 30s (aggiornamento presenze)
- **View**: `employee::filament.widgets.who-is-in-today-widget`

### Methods
- `mount()`: Inizializza dati presenza odierna
- `getTodayAttendance()`: Recupera presenze oggi
- `getTeamStats()`: Contatori per team selezionato  
- `filterByTeam(string $team)`: Filtra per team
- `getPresentEmployees()`: Lista presenti con dettagli
- `getAbsentEmployees()`: Lista assenti
- `refreshAttendance()`: Refresh manuale dati

### Properties
- `array $presentEmployees`: Dipendenti presenti
- `array $absentEmployees`: Dipendenti assenti
- `int $presentCount`: Contatore presenti
- `int $absentCount`: Contatore assenti
- `string $selectedTeam`: Team filtro attivo
- `array $teamOptions`: Opzioni dropdown team

## Frontend (Blade Template)

### Layout Structure
```blade
<x-filament-widgets::widget>
    <div class="p-6">
        <!-- Header con filtro -->
        <div class="widget-header">
            <h3>CHI C'È OGGI</h3>
            
            <select wire:model="selectedTeam" class="team-filter">
                <option value="">Tutti</option>
                @foreach($teamOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            
            <a href="{{ route('attendance.detail') }}" class="detail-link">
                Vedi dettaglio
            </a>
        </div>

        <!-- Contatori presenza -->
        <div class="attendance-counters">
            <div class="counter-card present">
                <div class="counter-number">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-green-600" />
                    {{ $presentCount }} presenti
                </div>
                
                <div class="employee-avatars">
                    @foreach($presentEmployees->take(8) as $employee)
                        <div class="avatar-wrapper" title="{{ $employee->name }}">
                            <img 
                                src="{{ $employee->avatar_url }}" 
                                class="avatar"
                                alt="{{ $employee->name }}"
                            />
                            @if($employee->current_location === 'remote')
                                <span class="location-badge remote">🏠</span>
                            @endif
                        </div>
                    @endforeach
                    
                    @if($presentCount > 8)
                        <div class="more-count">+{{ $presentCount - 8 }}</div>
                    @endif
                </div>
            </div>

            <div class="counter-card absent">
                <div class="counter-number">
                    <x-heroicon-o-x-circle class="w-5 h-5 text-red-600" />
                    {{ $absentCount }} assenti
                </div>
                
                <div class="employee-avatars">
                    @foreach($absentEmployees->take(6) as $employee)
                        <div class="avatar-wrapper grayscale" title="{{ $employee->name }}">
                            <img 
                                src="{{ $employee->avatar_url }}" 
                                class="avatar"
                                alt="{{ $employee->name }}"
                            />
                        </div>
                    @endforeach
                    
                    @if($absentCount > 6)
                        <div class="more-count">+{{ $absentCount - 6 }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="quick-actions">
            <button wire:click="refreshAttendance" class="btn-refresh">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                Aggiorna
            </button>
            
            <button wire:click="markAttendance" class="btn-action">
                Segna presenza
            </button>
        </div>
    </div>
</x-filament-widgets::widget>
```

### Visual Elements
- **Avatar presenti**: Colorati con badge location
- **Avatar assenti**: Grayscale con tooltip motivo
- **Location badges**:
  - 🏢 Ufficio (default)
  - 🏠 Remote/Smart working
  - 📍 Cliente/Trasferta
  - 🏥 Malattia (per assenti)

## Business Logic

### Attendance Detection
```php
public function detectAttendanceStatus(Employee $employee): string
{
    $workHours = $employee->todayWorkHours();
    $currentTime = now();
    
    if ($workHours->where('type', 'clock_in')->exists()) {
        if ($workHours->where('type', 'clock_out')->exists()) {
            return 'early_leave'; // Uscito prima del normale
        }
        return 'present';
    }
    
    if ($currentTime->hour >= 10) {
        return 'absent'; // Non ancora timbrato dopo le 10
    }
    
    return 'not_arrived'; // Ancora presto
}
```

### Team Filtering
- **Default**: Mostra tutti i dipendenti attivi
- **Team specific**: Filtra per team selezionato
- **Permission based**: Manager vedono il loro team
- **HR**: Accesso a tutti i team

### Real-time Updates
- **WebSocket**: Push updates per presenze
- **Polling**: Fallback ogni 30 secondi
- **Manual refresh**: Button per aggiornamento

## Integration Points
- **Time Tracking**: Sincronizzazione con timbrature
- **Calendar**: Eventi e meeting programmati
- **Leave System**: Assenze programmate
- **HR Dashboard**: Statistiche dettagliate

## Actions & Interactions
- `filterByTeam(team)`: Cambia filtro team
- `viewEmployeeDetail(id)`: Dettaglio dipendente
- `markAttendance()`: Quick attendance per manager
- `exportAttendance()`: Export presenze giornaliere
- `sendReminder(id)`: Promemoria timbratura

## Performance Optimizations
- **Eager Loading**: Carica relations in batch
- **Cache Strategy**: Cache 5min con invalidazione
- **Image Optimization**: Avatar thumbnail CDN
- **Query Efficiency**: Single query con joins

## Responsive Behavior
- **Desktop**: Layout a due colonne con avatar grandi
- **Tablet**: Stack verticale con avatar medi
- **Mobile**: Lista compatta con contatori
- **Touch**: Tap per dettagli employee

## Security & Privacy
- **Data Access**: Solo dipendenti visibili per ruolo
- **Avatar Privacy**: Placeholder per utenti senza foto
- **Location Tracking**: Opt-in per location precisa
- **Audit Trail**: Log accessi ai dati presenza

## Error Handling
- **Network Issues**: Offline indicator con retry
- **Missing Data**: Placeholder per avatar mancanti
- **Permission Errors**: Graceful degradation
- **Timeout**: Loading states con timeout

## Testing Strategy
- Unit test contatori presenza
- Integration test filtri team
- Feature test UI interactions
- Performance test con 100+ dipendenti
- E2E test workflow completo presenza