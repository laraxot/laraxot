# TasksWidget (COSE DA FARE)

## Overview
Widget che mostra le attività pendenti dell'utente corrente. Visualizza compiti assegnati, scadenze, buste paga da controllare e altre task personali.

## UI Components
- **Titolo**: "COSE DA FARE"
- **Lista task**: Ogni elemento mostra:
  - Icona dell'attività (📄 per busta paga, 📋 per task generici, etc.)
  - Testo descrittivo della task
  - Freccia per navigare al dettaglio
- **Stato vuoto**: Messaggio quando non ci sono task

## Data Requirements

### Database Tables
- `tasks` o `employee_tasks`
  - `id`
  - `employee_id` (FK to users)
  - `title`
  - `description`
  - `type` (payroll, general, deadline, etc.)
  - `priority` (low, medium, high)
  - `status` (pending, in_progress, completed)
  - `due_date`
  - `assigned_at`
  - `completed_at`

### Model: Task
```php
class Task extends Model
{
    public function employee(): BelongsTo
    public function getIconAttribute(): string // Icona basata su type
    public function getStatusBadgeAttribute(): string // Badge colore status
    public function scopePending(): Builder // Solo task pending
    public function scopeForEmployee(): Builder // Per dipendente specifico
}
```

## Widget Implementation

### Class: TasksWidget extends XotBaseWidget
- **Sort**: 1 (dopo TimeClockWidget)
- **Polling**: 30s (aggiornamento moderato)
- **View**: `employee::filament.widgets.tasks-widget`

### Methods
- `mount()`: Carica task dell'utente
- `getTasks()`: Recupera task pending ordinate per priorità/scadenza
- `markAsRead(int $taskId)`: Marca task come vista
- `getTaskIcon(string $type)`: Icona basata su tipo

### Properties
- `array $pendingTasks`: Lista task pending
- `int $tasksCount`: Contatore per badge

## Frontend (Blade Template)

### Layout
```blade
<x-filament-widgets::widget>
    <div class="p-6">
        <h3>COSE DA FARE</h3>
        
        @forelse($pendingTasks as $task)
            <div class="task-item">
                <span class="icon">{!! $task->icon !!}</span>
                <span class="text">{{ $task->title }}</span>
                <x-heroicon-o-chevron-right class="arrow" />
            </div>
        @empty
            <div class="empty-state">
                <p>Nessuna attività pendente</p>
            </div>
        @endforelse
    </div>
</x-filament-widgets::widget>
```

### Styling
- Lista items con hover effect
- Icone colorate per tipo
- Badge contatori
- Layout responsivo

## Business Logic
1. **Caricamento task**: Solo per utente autenticato
2. **Filtri**: Solo status = 'pending'
3. **Ordinamento**: Priorità DESC, due_date ASC
4. **Tipi task**:
   - `payroll`: Buste paga da verificare
   - `document`: Documenti da firmare
   - `training`: Corsi da completare
   - `general`: Task generiche

## Integration Points
- **Notifications**: Link a sistema notifiche
- **HR Module**: Integrazione per buste paga
- **Document System**: Per documenti da firmare
- **Training Module**: Per corsi obbligatori

## Actions
- `viewTask(taskId)`: Apre dettaglio task
- `completeTask(taskId)`: Marca come completata
- `dismissTask(taskId)`: Rimanda task

## Permissions
- Utente vede solo le sue task
- HR può assegnare task a dipendenti
- Manager possono vedere task del team

## Testing Strategy
- Unit test per business logic
- Feature test per UI interactions
- Database test per query performance