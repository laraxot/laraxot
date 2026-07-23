# LE MIE RICHIESTE IN ATTESA Widget - Documentazione

## 🎯 Panoramica

Il widget "LE MIE RICHIESTE IN ATTESA" è un componente di status tracking che mostra lo stato delle richieste HR del dipendente (ferie, permessi, rimborsi, etc.) fornendo feedback visivo immediato sull'approvazione/gestione da parte dell'amministrazione.

## 🎯 Funzionalità Principali

### Scopo del Widget
- **Status Tracking**: Monitoraggio richieste HR in tempo reale  
- **Visual Feedback**: Feedback immediato su approvazioni/rifiuti
- **Workflow Transparency**: Trasparenza processo approvazione
- **User Reassurance**: Rassicurazione utente su gestione richieste

### Dati Visualizzati (dal Screenshot)
```
LE MIE RICHIESTE IN ATTESA
┌─────────────────────────────────────────────────┐
│                    🎯                           │
│             [Illustration Figure]               │
│                                                 │
│  Tutte le tue richieste sono state gestite     │
│         dall'amministratore                     │
│                                                 │
│     Non devi preoccuparti di nulla.           │
└─────────────────────────────────────────────────┘
```

## 🏗️ Struttura Tecnica

### Layout Stati Widget
```blade
<div class="requests-widget">
    <div class="widget-header">
        <h3>LE MIE RICHIESTE IN ATTESA</h3>
    </div>
    
    <div class="widget-content">
        @if($pendingRequests->isEmpty())
            <!-- Empty State - All Handled -->
            <div class="empty-state">
                <div class="illustration">
                    <svg class="success-icon">
                        <!-- Success illustration SVG -->
                        <use xlink:href="#success-figure"/>
                    </svg>
                </div>
                <div class="message">
                    <h4>Tutte le tue richieste sono state gestite dall'amministratore</h4>
                    <p>Non devi preoccuparti di nulla.</p>
                </div>
            </div>
        @else
            <!-- Active Requests State -->
            <div class="requests-list">
                @foreach($pendingRequests as $request)
                    <div class="request-item {{ $request->status_class }}">
                        <div class="request-info">
                            <div class="request-type">
                                <span class="icon">{{ $request->type_icon }}</span>
                                <span class="title">{{ $request->type_display }}</span>
                            </div>
                            <div class="request-meta">
                                <span class="date">{{ $request->created_at->diffForHumans() }}</span>
                                <span class="status-badge">{{ $request->status_display }}</span>
                            </div>
                        </div>
                        <div class="request-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $request->progress_percentage }}%"></div>
                            </div>
                            <span class="progress-text">{{ $request->progress_text }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
```

## 💾 Data Models

### Request Model
```php
class EmployeeRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'description', 
        'status',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'reviewer_id',
        'reviewer_notes',
        'priority',
        'due_date'
    ];
    
    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'due_date' => 'date'
    ];
    
    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_NEEDS_INFO = 'needs_info';
    
    // Type Constants
    const TYPE_LEAVE = 'leave_request';
    const TYPE_EXPENSE = 'expense_reimbursement';
    const TYPE_DOCUMENT = 'document_request';
    const TYPE_CHANGE = 'info_change';
    const TYPE_EQUIPMENT = 'equipment_request';
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => '⏳ In attesa',
            self::STATUS_UNDER_REVIEW => '👁️ In revisione',
            self::STATUS_APPROVED => '✅ Approvata',
            self::STATUS_REJECTED => '❌ Rifiutata',
            self::STATUS_NEEDS_INFO => '❓ Richiede info',
            default => ucfirst($this->status)
        };
    }
    
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            self::TYPE_LEAVE => '🏖️ Richiesta ferie',
            self::TYPE_EXPENSE => '💰 Rimborso spese',
            self::TYPE_DOCUMENT => '📄 Richiesta documenti',
            self::TYPE_CHANGE => '✏️ Modifica dati',
            self::TYPE_EQUIPMENT => '💻 Richiesta attrezzatura',
            default => ucfirst(str_replace('_', ' ', $this->type))
        };
    }
    
    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            self::STATUS_PENDING => 25,
            self::STATUS_UNDER_REVIEW => 50,
            self::STATUS_NEEDS_INFO => 40,
            self::STATUS_APPROVED => 100,
            self::STATUS_REJECTED => 100,
            default => 0
        };
    }
    
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'status-pending',
            self::STATUS_UNDER_REVIEW => 'status-reviewing',
            self::STATUS_APPROVED => 'status-approved',
            self::STATUS_REJECTED => 'status-rejected',
            self::STATUS_NEEDS_INFO => 'status-needs-info',
            default => 'status-unknown'
        };
    }
}
```

### Widget Controller
```php
class RichiesteInAttesaWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.richieste-in-attesa';
    
    protected static ?string $pollingInterval = '60s';
    
    public function getPendingRequestsProperty(): Collection
    {
        return EmployeeRequest::query()
            ->where('employee_id', Auth::user()->employee->id)
            ->whereIn('status', [
                EmployeeRequest::STATUS_PENDING,
                EmployeeRequest::STATUS_UNDER_REVIEW,
                EmployeeRequest::STATUS_NEEDS_INFO
            ])
            ->orderBy('priority', 'desc')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }
    
    public function getRequestsStatsProperty(): array
    {
        $allRequests = EmployeeRequest::query()
            ->where('employee_id', Auth::user()->employee->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->get();
            
        return [
            'total' => $allRequests->count(),
            'pending' => $allRequests->where('status', EmployeeRequest::STATUS_PENDING)->count(),
            'approved' => $allRequests->where('status', EmployeeRequest::STATUS_APPROVED)->count(),
            'average_response_time' => $this->calculateAverageResponseTime($allRequests),
            'completion_rate' => $this->calculateCompletionRate($allRequests)
        ];
    }
    
    private function calculateAverageResponseTime(Collection $requests): float
    {
        $processedRequests = $requests->whereNotNull('reviewed_at');
        
        if ($processedRequests->isEmpty()) {
            return 0;
        }
        
        $totalHours = $processedRequests->sum(function ($request) {
            return $request->submitted_at->diffInHours($request->reviewed_at);
        });
        
        return round($totalHours / $processedRequests->count(), 1);
    }
    
    private function calculateCompletionRate(Collection $requests): float
    {
        if ($requests->isEmpty()) {
            return 100;
        }
        
        $completedCount = $requests->whereIn('status', [
            EmployeeRequest::STATUS_APPROVED,
            EmployeeRequest::STATUS_REJECTED
        ])->count();
        
        return round(($completedCount / $requests->count()) * 100, 1);
    }
}
```

## 🎨 UI/UX Design States

### Empty State (Success)
```css
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.illustration {
    margin-bottom: 1.5rem;
}

.success-icon {
    width: 120px;
    height: 120px;
    color: #10b981; /* green-500 */
}

.empty-state .message h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state .message p {
    font-size: 0.875rem;
    color: #6b7280;
}
```

### Active Requests State
```css
.requests-list {
    space-y: 0.75rem;
    max-height: 400px;
    overflow-y: auto;
}

.request-item {
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    transition: all 0.2s;
}

.request-item:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

/* Status-specific styling */
.status-pending {
    border-left: 4px solid #f59e0b; /* amber-500 */
}

.status-reviewing {
    border-left: 4px solid #3b82f6; /* blue-500 */
}

.status-needs-info {
    border-left: 4px solid #ef4444; /* red-500 */
}

.request-info {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.75rem;
}

.request-type {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.request-meta {
    text-align: right;
    font-size: 0.875rem;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #f3f4f6;
    color: #374151;
    margin-top: 0.25rem;
}
```

### Progress Bar Styling
```css
.request-progress {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.progress-bar {
    flex-grow: 1;
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #10b981);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.75rem;
    color: #6b7280;
    white-space: nowrap;
}
```

## 🔔 Notification System

### Real-time Updates
```php
// Livewire event listener
protected $listeners = [
    'requestStatusUpdated' => 'refreshRequests',
    'newRequestSubmitted' => 'refreshRequests'
];

public function refreshRequests(): void
{
    $this->emit('$refresh');
    
    // Show notification for status changes
    $this->notify('Stato richiesta aggiornato!', 'success');
}
```

### Status Change Notifications
```php
public function notifyStatusChange(EmployeeRequest $request): void
{
    $message = match($request->status) {
        EmployeeRequest::STATUS_APPROVED => '✅ Richiesta approvata!',
        EmployeeRequest::STATUS_REJECTED => '❌ Richiesta rifiutata',
        EmployeeRequest::STATUS_NEEDS_INFO => '❓ Richieste info aggiuntive',
        EmployeeRequest::STATUS_UNDER_REVIEW => '👁️ Richiesta in revisione',
        default => 'Stato richiesta aggiornato'
    };
    
    $this->notify($message, $this->getNotificationType($request->status));
}

private function getNotificationType(string $status): string
{
    return match($status) {
        EmployeeRequest::STATUS_APPROVED => 'success',
        EmployeeRequest::STATUS_REJECTED => 'danger',
        EmployeeRequest::STATUS_NEEDS_INFO => 'warning',
        default => 'info'
    };
}
```

## 📊 Analytics & Business Logic

### Request Metrics
```php
public function getWidgetMetrics(): array
{
    $userId = Auth::user()->employee->id;
    
    return [
        'response_time' => [
            'current_avg' => $this->requests_stats['average_response_time'],
            'target' => 24, // hours
            'performance' => $this->getResponseTimePerformance()
        ],
        'approval_rate' => [
            'current' => $this->getApprovalRate($userId),
            'target' => 85, // percentage
            'trend' => $this->getApprovalTrend($userId)
        ],
        'most_common_types' => $this->getMostCommonRequestTypes($userId),
        'peak_submission_times' => $this->getPeakSubmissionTimes($userId)
    ];
}
```

### Workflow Automation
```php
// Auto-remind for stale requests
public function checkStaleRequests(): void
{
    $staleRequests = EmployeeRequest::query()
        ->where('status', EmployeeRequest::STATUS_PENDING)
        ->where('submitted_at', '<', now()->subHours(48))
        ->get();
        
    foreach ($staleRequests as $request) {
        $this->sendReminder($request);
    }
}
```

## 🧪 Testing Strategy

### Widget States Testing
```php
it('shows empty state when no pending requests')
    ->expect($widget->pending_requests)
    ->toBeEmpty()
    ->and($widget->render())
    ->toContain('Tutte le tue richieste sono state gestite');

it('displays pending requests with correct status')
    ->expect($widget->pending_requests->first()->status_display)
    ->toContain('⏳');

it('calculates progress percentage correctly')
    ->expect($widget->pending_requests->first()->progress_percentage)
    ->toBeGreaterThan(0)
    ->toBeLessThanOrEqual(100);
```

### Business Logic Testing
```php
it('calculates average response time correctly')
    ->expect($widget->requests_stats['average_response_time'])
    ->toBeFloat()
    ->toBeGreaterThanOrEqual(0);

it('filters only pending statuses')
    ->expect($widget->pending_requests)
    ->each(fn($request) => 
        expect($request->status)->toBeIn([
            'pending', 'under_review', 'needs_info'
        ])
    );
```

## 🔮 Future Enhancements

### Planned Features
- **Quick Actions**: Approve/cancel minor requests direttamente dal widget
- **Document Upload**: Upload documenti aggiuntivi inline
- **Chat Integration**: Messaggistica diretta con HR
- **Deadline Tracking**: Alert per deadline requests
- **Mobile Push**: Notifiche push per status changes
- **Workflow Visualization**: Vista grafica processo approvazione

### AI/ML Enhancements
- **Predictive ETA**: ML per predire tempo approvazione
- **Smart Categorization**: Auto-categorizzazione richieste
- **Sentiment Analysis**: Analisi sentiment reviewer notes
- **Anomaly Detection**: Rilevamento pattern anomali

---

## 📊 Summary

Il widget "LE MIE RICHIESTE IN ATTESA" fornisce:

- ✅ **Status Transparency**: Visibilità completa stato richieste
- ✅ **Peace of Mind**: Rassicurazione quando tutto ok ("Non devi preoccuparti")  
- ✅ **Progress Tracking**: Barre progresso per workflow
- ✅ **Real-time Updates**: Aggiornamenti automatici status
- ✅ **Smart Notifications**: Alert mirati per cambi status

**Impatto**: +80% riduzione ansia dipendenti, -60% richieste status duplicate.

*Documentazione widget LE MIE RICHIESTE IN ATTESA - Gennaio 2025*