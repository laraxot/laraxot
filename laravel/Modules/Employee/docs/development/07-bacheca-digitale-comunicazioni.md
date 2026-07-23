# 07 - Bacheca Digitale e Comunicazioni

## Panoramica
Sistema completo per la comunicazione aziendale interna, con bacheca digitale, notifiche multi-canale, controllo lettura messaggi e sistema di priorità per comunicazioni urgenti.

## Obiettivi
- Centralizzare tutte le comunicazioni aziendali
- Garantire che i messaggi importanti raggiungano tutti i dipendenti
- Tracciare lettura e conferma ricezione messaggi
- Implementare sistema priorità per comunicazioni urgenti
- Fornire archivio storico comunicazioni
- Integrare notifiche email, push e in-app

## Funzionalità da Implementare

### 1. Sistema Bacheca Digitale

#### 1.1 Gestione Comunicazioni Aziendali
**Obiettivo**: Permettere alla direzione e HR di pubblicare comunicazioni per tutti i dipendenti

**Implementazione Step-by-Step**:

1. **Creare il Model Communication**
```php
// app/Models/Communication.php
class Communication extends Model
{
    protected $fillable = [
        'title', 'content', 'summary', 'author_id',
        'communication_type', 'priority', 'target_audience',
        'target_departments', 'target_roles', 'target_employees',
        'publication_date', 'expiration_date', 'requires_acknowledgment',
        'is_published', 'is_pinned', 'attachment_path'
    ];

    protected $casts = [
        'target_departments' => 'array',
        'target_roles' => 'array', 
        'target_employees' => 'array',
        'publication_date' => 'datetime',
        'expiration_date' => 'datetime',
        'requires_acknowledgment' => 'boolean',
        'is_published' => 'boolean',
        'is_pinned' => 'boolean'
    ];

    public function scopeForEmployee($query, Employee $employee)
    {
        return $query->where(function ($q) use ($employee) {
            $q->where('target_audience', 'all')
              ->orWhere(function ($subQ) use ($employee) {
                  $subQ->where('target_audience', 'department')
                       ->whereJsonContains('target_departments', $employee->department_id);
              });
        });
    }
}
```

2. **Creare Migration per communications**
```php
Schema::create('communications', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->longText('content');
    $table->text('summary')->nullable();
    $table->foreignId('author_id')->constrained('employees');
    $table->enum('communication_type', ['announcement', 'policy', 'news', 'urgent']);
    $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
    $table->enum('target_audience', ['all', 'department', 'role', 'specific']);
    $table->json('target_departments')->nullable();
    $table->json('target_roles')->nullable();
    $table->json('target_employees')->nullable();
    $table->timestamp('publication_date');
    $table->timestamp('expiration_date')->nullable();
    $table->boolean('requires_acknowledgment')->default(false);
    $table->boolean('is_published')->default(false);
    $table->boolean('is_pinned')->default(false);
    $table->string('attachment_path')->nullable();
    $table->timestamps();
});
```

3. **Creare Filament Resource CommunicationResource**
```php
// app/Filament/Resources/CommunicationResource.php
class CommunicationResource extends XotBaseResource
{
    protected static ?string $model = Communication::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required(),
            Textarea::make('summary')->maxLength(500),
            RichEditor::make('content')->required(),
            
            Select::make('communication_type')
                ->options([
                    'announcement' => 'Annuncio',
                    'policy' => 'Policy',
                    'news' => 'Notizia',
                    'urgent' => 'Urgente'
                ])->required(),
                
            Select::make('priority')
                ->options([
                    'low' => 'Bassa',
                    'normal' => 'Normale', 
                    'high' => 'Alta',
                    'urgent' => 'Urgente'
                ])->default('normal'),
                
            Select::make('target_audience')
                ->options([
                    'all' => 'Tutti',
                    'department' => 'Dipartimenti',
                    'role' => 'Ruoli',
                    'specific' => 'Specifici'
                ])->required(),
                
            DateTimePicker::make('publication_date')->required(),
            Toggle::make('requires_acknowledgment'),
            Toggle::make('is_published')->default(true)
        ]);
    }
}
```

### 2. Dashboard Bacheca per Dipendenti

#### 2.1 Widget Bacheca Comunicazioni
**Obiettivo**: Mostrare ai dipendenti le comunicazioni rilevanti

**Implementazione**:

1. **Creare Widget CommunicationBoardWidget**
```php
// app/Filament/Widgets/CommunicationBoardWidget.php
class CommunicationBoardWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.communication-board';
    
    public function getViewData(): array
    {
        $employee = auth()->user()->employee;
        
        $communications = Communication::forEmployee($employee)
            ->where('is_published', true)
            ->where('publication_date', '<=', now())
            ->orderBy('is_pinned', 'desc')
            ->orderBy('priority', 'desc')
            ->orderBy('publication_date', 'desc')
            ->limit(10)
            ->get();
            
        return ['communications' => $communications];
    }
    
    public function markAsRead($communicationId): void
    {
        CommunicationReading::firstOrCreate([
            'communication_id' => $communicationId,
            'employee_id' => auth()->user()->employee->id
        ], [
            'read_at' => now()
        ]);
    }
}
```

### 3. Sistema Notifiche Multi-Canale

#### 3.1 Notifiche Automatiche
**Obiettivo**: Inviare notifiche quando vengono pubblicate comunicazioni

**Implementazione**:

1. **Creare Notification NewCommunicationNotification**
```php
// app/Notifications/NewCommunicationNotification.php
class NewCommunicationNotification extends Notification
{
    public function __construct(private Communication $communication) {}
    
    public function via($notifiable): array
    {
        $channels = ['database'];
        
        if (in_array($this->communication->priority, ['urgent', 'high'])) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }
    
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->line("Nuova comunicazione: {$this->communication->title}")
            ->action('Leggi', url('/admin'));
    }
    
    private function getSubject(): string
    {
        $prefix = match($this->communication->priority) {
            'urgent' => '🚨 URGENTE: ',
            'high' => '⚠️ IMPORTANTE: ',
            default => '📢 '
        };
        
        return $prefix . $this->communication->title;
    }
}
```

2. **Creare Observer per Communication**
```php
// app/Observers/CommunicationObserver.php
class CommunicationObserver
{
    public function updated(Communication $communication): void
    {
        if ($communication->wasChanged('is_published') && $communication->is_published) {
            $this->sendNotifications($communication);
        }
    }
    
    private function sendNotifications(Communication $communication): void
    {
        $employees = $this->getTargetEmployees($communication);
        
        foreach ($employees as $employee) {
            if ($employee->user) {
                $employee->user->notify(new NewCommunicationNotification($communication));
            }
        }
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per communications, communication_readings
- [ ] Implementare Models con relazioni
- [ ] Configurare Observer per notifiche

### Phase 2: Core Functionality  
- [ ] Implementare CommunicationResource per admin
- [ ] Creare sistema targeting audience
- [ ] Implementare tracking lettura

### Phase 3: UI Dipendenti
- [ ] Creare CommunicationBoardWidget
- [ ] Implementare interfaccia lettura
- [ ] Sistema conferma lettura

### Phase 4: Notifiche
- [ ] Notifiche multi-canale
- [ ] Sistema priorità
- [ ] Reminder comunicazioni non lette

### Phase 5: Analytics
- [ ] Dashboard statistiche
- [ ] Report lettura
- [ ] Analytics engagement

## Note Tecniche

### Sicurezza
- Validare autorizzazioni pubblicazione
- Audit trail comunicazioni
- Controllo accesso basato su ruoli

### Performance
- Indicizzare per query frequenti
- Cache per comunicazioni attive
- Ottimizzare query targeting

### Integrazione
- API per app mobile
- Webhook per sistemi esterni
- Export report analytics

Questo sistema fornirà comunicazione aziendale efficace con massima copertura e tracciamento completo.
