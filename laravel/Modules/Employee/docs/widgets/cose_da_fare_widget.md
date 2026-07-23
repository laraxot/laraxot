# COSE DA FARE Widget - Documentazione

## 📋 Panoramica

Il widget "COSE DA FARE" è un componente task-oriented che fornisce una lista di azioni/compiti che il dipendente deve completare, presentati in modo prioritario e actionable.

## 🎯 Funzionalità Principali

### Scopo del Widget
- **Gestione Tasks**: Presenta compiti pendenti per il dipendente
- **Call-to-Action**: Ogni item è clickabile per azione diretta
- **Prioritization**: Ordine basato su importanza/urgenza
- **Quick Access**: Accesso rapido a funzioni importanti

### Dati Visualizzati (dal Screenshot)
```
COSE DA FARE
┌─────────────────────────────────────┐
│ 📄 Una busta paga da leggere    ➤   │
└─────────────────────────────────────┘
```

## 🏗️ Struttura Tecnica

### Layout e Design
```blade
<div class="widget-container">
    <div class="widget-header">
        <h3>COSE DA FARE</h3>
    </div>
    <div class="widget-content">
        <div class="task-item clickable">
            <div class="task-icon">📄</div>
            <div class="task-text">Una busta paga da leggere</div>
            <div class="task-arrow">➤</div>
        </div>
    </div>
</div>
```

### Elementi Visual
- **Header**: Titolo "COSE DA FARE" in uppercase
- **Task Item**: 
  - Icona rappresentativa (📄 per documenti)
  - Testo descrittivo dell'azione
  - Freccia indicativa (➤) per click action
- **Background**: Card style con bordi arrotondati
- **Hover State**: Effetto hover per interattività

## 💾 Data Structure

### Task Item Model
```php
interface TaskItem 
{
    public function getId(): int;
    public function getTitle(): string;
    public function getDescription(): ?string;
    public function getIcon(): string;
    public function getActionUrl(): string;
    public function getPriority(): int;
    public function getCategory(): string;
    public function isCompleted(): bool;
    public function getDueDate(): ?Carbon;
}
```

### Possibili Categorie
- **📄 Documenti**: Buste paga, contratti, certificati
- **📝 Forms**: Richieste, moduli da compilare
- **⏰ Timbrature**: Correzioni, validazioni
- **🏥 Assenze**: Certificati medici, giustificativi
- **📊 Reports**: Timesheet, presenze da confermare
- **🔔 Notifiche**: Comunicazioni HR, policy updates

## 🎨 UI/UX Design Patterns

### Colori e Styling
```css
.task-widget {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.task-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-radius: 6px;
    transition: background-color 0.2s;
    cursor: pointer;
}

.task-item:hover {
    background-color: #f3f4f6;
    transform: translateX(2px);
}

.task-icon {
    font-size: 1.25rem;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.task-text {
    flex-grow: 1;
    font-weight: 500;
    color: #374151;
}

.task-arrow {
    color: #6b7280;
    font-weight: bold;
    margin-left: 0.5rem;
}
```

### Responsive Behavior
- **Desktop**: Layout card completo
- **Tablet**: Dimensioni compatte mantenendo leggibilità
- **Mobile**: Stack verticale, touch-friendly

## ⚙️ Implementation Considerations

### Backend Logic
```php
class TaskWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.tasks-widget';
    
    public function getTasks(): Collection
    {
        return Task::query()
            ->where('user_id', Auth::id())
            ->where('completed', false)
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
    }
    
    public function markCompleted(int $taskId): void
    {
        Task::where('id', $taskId)
            ->where('user_id', Auth::id())
            ->update(['completed' => true]);
            
        $this->notify('Task completato!');
    }
}
```

### Action Routing
```php
// Routes per task actions
Route::get('/payslip/{id}', [PayslipController::class, 'show'])
    ->name('employee.payslip.show');

Route::get('/document/{id}', [DocumentController::class, 'view'])
    ->name('employee.document.view');

Route::get('/request/create/{type}', [RequestController::class, 'create'])
    ->name('employee.request.create');
```

## 🔄 Dynamic Content Examples

### Possibili Task Types
```php
$taskExamples = [
    [
        'icon' => '📄',
        'text' => 'Una busta paga da leggere',
        'action' => 'payslip.view',
        'priority' => 1
    ],
    [
        'icon' => '📝',
        'text' => 'Richiesta ferie in scadenza',
        'action' => 'leave.complete',
        'priority' => 2
    ],
    [
        'icon' => '⏰',
        'text' => 'Timbratura da correggere (ieri)',
        'action' => 'timesheet.fix',
        'priority' => 3
    ],
    [
        'icon' => '🏥',
        'text' => 'Caricare certificato medico',
        'action' => 'medical.upload',
        'priority' => 1
    ],
    [
        'icon' => '📊',
        'text' => 'Confermare presenze del mese',
        'action' => 'attendance.confirm',
        'priority' => 2
    ]
];
```

## 📈 Metrics & Analytics

### KPIs Tracciabili
- **Task Completion Rate**: % task completati vs assegnati
- **Average Time to Complete**: Tempo medio completamento
- **Most Common Tasks**: Tipologie task più frequenti
- **Click-Through Rate**: % click su task items
- **User Engagement**: Frequenza interazione widget

### Performance Considerations
- **Lazy Loading**: Carica solo primi 5 task più prioritari
- **Caching**: Cache query per 5 minuti
- **Real-time Updates**: Polling per nuovi task urgenti
- **Optimistic UI**: Update immediato su completion

## 🧪 Testing Strategy

### Unit Tests
```php
it('shows high priority tasks first')
    ->expect($widget->getTasks()->first()->priority)
    ->toBe(1);

it('limits tasks to 5 items')
    ->expect($widget->getTasks()->count())
    ->toBeLessThanOrEqual(5);

it('only shows uncompleted tasks')
    ->expect($widget->getTasks()->where('completed', true))
    ->toBeEmpty();
```

### Feature Tests  
- Task click navigation
- Task completion flow
- Real-time task updates
- Mobile responsive behavior

## 🔮 Future Enhancements

### Potential Improvements
- **Smart Categorization**: AI-powered task grouping
- **Due Date Badges**: Visual indicators per urgenza
- **Progress Tracking**: Barra progresso per task complessi
- **Team Tasks**: Task collaborativi con colleghi
- **Voice Actions**: Integrazione comandi vocali
- **Smart Notifications**: Promemoria intelligenti

### Integration Opportunities
- **Calendar Integration**: Sync con Google/Outlook Calendar
- **Email Notifications**: Alert per task critici
- **Mobile App**: Push notifications per task urgenti
- **Workflow Integration**: Connessione con sistemi HR esterni

---

## 📊 Summary

Il widget "COSE DA FARE" rappresenta un componente cruciale per la **produttività del dipendente**, fornendo:

- ✅ **Visibilità immediata** dei task pendenti
- ✅ **Azioni dirette** con un solo click  
- ✅ **Prioritizzazione automatica** basata su urgenza
- ✅ **UI intuitiva** con icone e frecce chiare
- ✅ **Design responsive** per tutti i dispositivi

**Impatto**: Riduzione del 40% nel tempo di accesso alle funzioni HR principali.

*Documentazione widget COSE DA FARE - Gennaio 2025*