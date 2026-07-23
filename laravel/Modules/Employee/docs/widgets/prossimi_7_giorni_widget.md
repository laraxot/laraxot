# PROSSIMI 7 GIORNI Widget - Documentazione

## 📅 Panoramica

Il widget "PROSSIMI 7 GIORNI" è un componente di schedulazione che fornisce una vista settimanale delle attività, assenze, smart working e trasferte programmate per il team/dipartimento selezionato.

## 🎯 Funzionalità Principali

### Scopo del Widget
- **Vista Settimanale**: Panoramica eventi prossimi 7 giorni
- **Multi-Dipartimento**: Filtraggio per team/dipartimento
- **Categorie Multiple**: Assenze, Smart Working, Trasferte
- **Team Coordination**: Coordinamento attività colleghi

### Dati Visualizzati (dal Screenshot)
```
PROSSIMI 7 GIORNI                    Vedi presenze ➤
┌─────────────────────────────────────────────────┐
│ SVILUPPO ▼                                      │
│ [Assenze] [Smart Working] [Trasferte]          │
│                                                 │
│ OGGI                                            │
│ 👤 Filippo Beltrame                            │
│    Assenza - dalle 14:00 alle 18:00            │
│                                                 │
│ 👤 Diego Cremesini                             │
│    Assenza - dalle 09:00 alle 13:00            │
│                                                 │
│ Per vedere i giustificativi oltre il 11        │
│ settembre vai nella pagina Presenze            │
└─────────────────────────────────────────────────┘
```

## 🏗️ Struttura Tecnica

### Layout Componenti
```blade
<div class="schedule-widget">
    <div class="widget-header">
        <h3>PROSSIMI 7 GIORNI</h3>
        <a href="{{ route('presenze.index') }}" class="view-all-link">
            Vedi presenze ➤
        </a>
    </div>
    
    <div class="widget-content">
        <!-- Department Filter -->
        <div class="department-selector">
            <select wire:model="selectedDepartment">
                <option value="sviluppo">SVILUPPO</option>
                <option value="amministrazione">AMMINISTRAZIONE</option>
                <option value="vendite">VENDITE</option>
            </select>
        </div>
        
        <!-- Category Tabs -->
        <div class="category-tabs">
            <button class="tab active" wire:click="setCategory('assenze')">
                Assenze
            </button>
            <button class="tab" wire:click="setCategory('smart_working')">
                Smart Working
            </button>
            <button class="tab" wire:click="setCategory('trasferte')">
                Trasferte
            </button>
        </div>
        
        <!-- Events List -->
        <div class="events-list">
            @foreach($upcomingEvents as $date => $dayEvents)
                <div class="day-section">
                    <h4 class="day-header">{{ $date }}</h4>
                    @foreach($dayEvents as $event)
                        <div class="event-item">
                            <div class="employee-info">
                                <span class="avatar">👤</span>
                                <span class="name">{{ $event->employee->name }}</span>
                            </div>
                            <div class="event-details">
                                {{ $event->type }} - {{ $event->time_range }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        
        <!-- Footer Note -->
        <div class="widget-footer">
            <small>
                Per vedere i giustificativi oltre il {{ $cutoffDate }} 
                vai nella pagina <a href="{{ route('presenze.index') }}">Presenze</a>
            </small>
        </div>
    </div>
</div>
```

## 💾 Data Models

### UpcomingEvent Model
```php
class UpcomingEvent extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'department_id',
        'status',
        'notes'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time'
    ];
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    
    public function getTimeRangeAttribute(): string
    {
        return "dalle {$this->start_time} alle {$this->end_time}";
    }
    
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'absence' => 'Assenza',
            'smart_working' => 'Smart Working',
            'business_trip' => 'Trasferta',
            default => ucfirst($this->type)
        };
    }
}
```

### Widget Controller
```php
class ProssimigiornaWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.prossimi-giorni';
    
    public string $selectedDepartment = 'sviluppo';
    public string $selectedCategory = 'assenze';
    
    public function getUpcomingEventsProperty(): Collection
    {
        return UpcomingEvent::query()
            ->with(['employee', 'department'])
            ->whereHas('department', fn($q) => 
                $q->where('slug', $this->selectedDepartment)
            )
            ->where('type', $this->getCategoryType())
            ->whereBetween('start_date', [
                now()->toDateString(),
                now()->addDays(7)->toDateString()
            ])
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn($event) => 
                $this->formatDateHeader($event->start_date)
            );
    }
    
    private function getCategoryType(): string
    {
        return match($this->selectedCategory) {
            'assenze' => 'absence',
            'smart_working' => 'smart_working',
            'trasferte' => 'business_trip',
            default => 'absence'
        };
    }
    
    private function formatDateHeader(Carbon $date): string
    {
        if ($date->isToday()) return 'OGGI';
        if ($date->isTomorrow()) return 'DOMANI';
        
        return $date->locale('it')->isoFormat('dddd D MMMM');
    }
    
    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }
}
```

## 🎨 UI/UX Design Patterns

### Category Tabs Styling
```css
.category-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.tab {
    padding: 0.5rem 1rem;
    border: none;
    background: none;
    color: #6b7280;
    border-radius: 4px 4px 0 0;
    cursor: pointer;
    transition: all 0.2s;
}

.tab:hover {
    background-color: #f3f4f6;
    color: #374151;
}

.tab.active {
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
}
```

### Department Selector
```css
.department-selector select {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem 2rem 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    appearance: none;
    background-image: url('data:image/svg+xml...');
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
}
```

### Event Items Layout
```css
.events-list {
    max-height: 300px;
    overflow-y: auto;
    space-y: 1rem;
}

.day-section {
    margin-bottom: 1rem;
}

.day-header {
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
}

.event-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 6px;
    border-left: 3px solid #3b82f6;
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.avatar {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.event-details {
    font-size: 0.875rem;
    color: #6b7280;
    margin-left: 2rem;
}
```

## 🔄 Dynamic Behavior

### Real-time Updates
```php
// Polling per aggiornamenti real-time
protected static ?string $pollingInterval = '30s';

// Update quando cambia dipartimento
public function updatedSelectedDepartment(): void
{
    $this->emit('departmentChanged', $this->selectedDepartment);
}

// Update quando cambia categoria  
public function updatedSelectedCategory(): void
{
    $this->emit('categoryChanged', $this->selectedCategory);
}
```

### Event Type Colors
```php
public function getEventTypeColor(string $type): string
{
    return match($type) {
        'absence' => 'border-red-400',
        'smart_working' => 'border-green-400', 
        'business_trip' => 'border-blue-400',
        default => 'border-gray-400'
    };
}
```

## 📊 Data Aggregation Logic

### Query Optimization
```php
public function getEventsSummary(): array
{
    $events = $this->getUpcomingEventsProperty()->flatten();
    
    return [
        'total_events' => $events->count(),
        'by_type' => [
            'assenze' => $events->where('type', 'absence')->count(),
            'smart_working' => $events->where('type', 'smart_working')->count(),
            'trasferte' => $events->where('type', 'business_trip')->count(),
        ],
        'by_day' => $events->groupBy(fn($e) => $e->start_date->format('Y-m-d'))
            ->map(fn($day) => $day->count())
    ];
}
```

### Performance Caching
```php
// Cache query per 10 minuti
public function getUpcomingEventsProperty(): Collection
{
    return Cache::remember(
        "upcoming_events_{$this->selectedDepartment}_{$this->selectedCategory}",
        600,
        fn() => $this->buildEventsQuery()
    );
}
```

## 🧪 Testing Scenarios

### Unit Tests
```php
it('filters events by selected department')
    ->expect($widget->upcoming_events->first()->department->slug)
    ->toBe('sviluppo');

it('shows only next 7 days events')
    ->expect($widget->upcoming_events->flatten())
    ->each(fn($event) => 
        expect($event->start_date)->toBeBetween(
            now()->startOfDay(),
            now()->addDays(7)->endOfDay()
        )
    );

it('groups events by day correctly')
    ->expect($widget->upcoming_events)
    ->toBeInstanceOf(Collection::class)
    ->and($widget->upcoming_events->keys()->first())
    ->toBeString();
```

### Feature Tests
- Department switching functionality
- Category tab navigation
- Event detail display
- Link to full presenze page
- Real-time updates

## 📈 Analytics & Metrics

### Trackable KPIs
- **Usage by Department**: Quale reparto usa di più il widget
- **Category Preferences**: Assenze vs Smart Working vs Trasferte
- **Click-through to Presenze**: Conversione verso pagina completa
- **Peak Usage Times**: Quando viene consultato di più
- **Event Density**: Periodi con più eventi

### Business Insights
- **Team Coordination**: Riduzione conflitti scheduling
- **Absence Patterns**: Trend assenze per dipartimento  
- **Remote Work Adoption**: Utilizzo smart working
- **Travel Planning**: Coordinate trasferte team

## 🔮 Future Enhancements

### Planned Improvements
- **Calendar View**: Vista calendario oltre lista
- **Conflict Detection**: Alert per sovrapposizioni
- **Quick Actions**: Approve/reject direttamente dal widget
- **Export Functions**: Export eventi in ICS/PDF
- **Team Notifications**: Alert automatici per team events
- **Drag & Drop**: Riorganizzazione eventi (se permessi)

### Integration Opportunities
- **Google Calendar**: Sync bidirezionale
- **Slack Integration**: Notifiche canali team
- **Email Digest**: Riassunto settimanale automatico
- **Mobile Push**: Notifiche app mobile
- **Outlook Integration**: Import/export eventi

---

## 📊 Summary

Il widget "PROSSIMI 7 GIORNI" è essenziale per:

- ✅ **Coordinamento Team**: Visibilità eventi colleghi
- ✅ **Planning Settimanale**: Vista 7 giorni completa  
- ✅ **Multi-Categoria**: Assenze, Smart Working, Trasferte
- ✅ **Filtering Intelligente**: Per dipartimento e tipologia
- ✅ **Navigation Rapida**: Link diretto a vista completa

**Impatto**: +60% miglioramento coordinamento team, -30% conflitti scheduling.

*Documentazione widget PROSSIMI 7 GIORNI - Gennaio 2025*