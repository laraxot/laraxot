# Architettura Tecnica Modulo Employee

## 🏗️ Panoramica Architetturale

Il modulo Employee segue rigorosamente l'architettura Laraxot/PTVX con particolare attenzione alle regole critiche del progetto.

## 🚨 Regole Architetturali Fondamentali

### Regola XotBase (PRIORITÀ ASSOLUTA)
**MAI ESTENDERE CLASSI FILAMENT DIRETTAMENTE - SEMPRE USARE XOTBASE**

```php
// ❌ VIETATO
class EmployeeDashboard extends Filament\Pages\Dashboard
class EmployeeResource extends Filament\Resources\Resource

// ✅ OBBLIGATORIO
class EmployeeDashboard extends Modules\Xot\Filament\Pages\XotBaseDashboard
class EmployeeResource extends Modules\Xot\Filament\Resources\XotBaseResource
```

### Pattern di Sviluppo
- **Amministrazione**: Filament con classi XotBase
- **Frontend**: Folio + Volt + Filament Widgets
- **API**: RESTful per app mobile e integrazioni
- **Database**: Migrazioni con XotBaseMigration

## 📊 Schema Database

### Tabelle Principali

#### 1. Employees (Dipendenti)
```sql
employees:
- id (bigint, PK)
- user_id (bigint, FK -> users.id)
- employee_code (varchar, unique)
- first_name (varchar)
- last_name (varchar)
- email (varchar, unique)
- phone (varchar)
- hire_date (date)
- department_id (bigint, FK)
- position_id (bigint, FK)
- manager_id (bigint, FK -> employees.id)
- status (enum: active, inactive, terminated)
- contract_type (enum: full_time, part_time, contractor)
- salary_info (json, encrypted)
- created_at, updated_at
```

#### 2. Time Entries (Timbrature)
```sql
time_entries:
- id (bigint, PK)
- employee_id (bigint, FK)
- type (enum: clock_in, clock_out, break_start, break_end)
- timestamp (datetime)
- location_lat (decimal)
- location_lng (decimal)
- location_name (varchar)
- device_info (json)
- photo_path (varchar, nullable)
- notes (text, nullable)
- status (enum: pending, approved, rejected)
- approved_by (bigint, FK -> users.id, nullable)
- approved_at (datetime, nullable)
- created_at, updated_at
```

#### 3. Attendances (Presenze Elaborate)
```sql
attendances:
- id (bigint, PK)
- employee_id (bigint, FK)
- date (date)
- clock_in (time)
- clock_out (time)
- break_duration (integer) // minuti
- total_hours (decimal)
- overtime_hours (decimal)
- status (enum: present, absent, partial, holiday)
- notes (text, nullable)
- created_at, updated_at
```

#### 4. Leave Requests (Richieste Ferie/Permessi)
```sql
leave_requests:
- id (bigint, PK)
- employee_id (bigint, FK)
- leave_type_id (bigint, FK)
- start_date (date)
- end_date (date)
- days_requested (decimal)
- reason (text)
- status (enum: pending, approved, rejected)
- requested_at (datetime)
- reviewed_by (bigint, FK -> users.id, nullable)
- reviewed_at (datetime, nullable)
- review_notes (text, nullable)
- created_at, updated_at
```

#### 5. Payslips (Buste Paga)
```sql
payslips:
- id (bigint, PK)
- employee_id (bigint, FK)
- period_month (integer)
- period_year (integer)
- file_path (varchar, encrypted)
- file_hash (varchar)
- delivered_at (datetime, nullable)
- viewed_at (datetime, nullable)
- downloaded_at (datetime, nullable)
- signature_hash (varchar, nullable)
- created_at, updated_at
```

#### 6. Expense Reports (Note Spese)
```sql
expense_reports:
- id (bigint, PK)
- employee_id (bigint, FK)
- title (varchar)
- description (text)
- total_amount (decimal)
- currency (varchar, default 'EUR')
- status (enum: draft, submitted, approved, rejected, paid)
- submitted_at (datetime, nullable)
- approved_by (bigint, FK -> users.id, nullable)
- approved_at (datetime, nullable)
- created_at, updated_at
```

#### 7. Work Shifts (Turni)
```sql
work_shifts:
- id (bigint, PK)
- employee_id (bigint, FK)
- shift_template_id (bigint, FK, nullable)
- date (date)
- start_time (time)
- end_time (time)
- break_duration (integer) // minuti
- location_id (bigint, FK, nullable)
- status (enum: scheduled, confirmed, completed, cancelled)
- notes (text, nullable)
- created_at, updated_at
```

### Tabelle di Supporto

#### Departments (Dipartimenti)
```sql
departments:
- id (bigint, PK)
- name (varchar)
- description (text, nullable)
- manager_id (bigint, FK -> employees.id, nullable)
- parent_id (bigint, FK -> departments.id, nullable)
- created_at, updated_at
```

#### Positions (Posizioni/Ruoli)
```sql
positions:
- id (bigint, PK)
- title (varchar)
- description (text, nullable)
- department_id (bigint, FK)
- salary_range_min (decimal, nullable)
- salary_range_max (decimal, nullable)
- created_at, updated_at
```

#### Leave Types (Tipologie Assenze)
```sql
leave_types:
- id (bigint, PK)
- name (varchar)
- code (varchar, unique)
- description (text, nullable)
- max_days_per_year (integer, nullable)
- requires_approval (boolean, default true)
- is_paid (boolean, default true)
- color (varchar, nullable) // per calendario
- created_at, updated_at
```

## 🎯 Modelli Eloquent

### Struttura Base
Tutti i modelli devono estendere `XotBaseModel` seguendo le convenzioni del progetto.

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Modules\Xot\Models\XotBaseModel;

class Employee extends XotBaseModel
{
    protected string $connection = 'employee';
    
    protected $fillable = [
        'user_id',
        'employee_code',
        'first_name',
        'last_name',
        // ...
    ];
    
    protected $casts = [
        'hire_date' => 'date',
        'salary_info' => 'encrypted:array',
        'status' => EmployeeStatus::class,
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
    
    // ... altre relazioni
}
```

### Enums per Type Safety
```php
enum EmployeeStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case TERMINATED = 'terminated';
}

enum TimeEntryType: string
{
    case CLOCK_IN = 'clock_in';
    case CLOCK_OUT = 'clock_out';
    case BREAK_START = 'break_start';
    case BREAK_END = 'break_end';
}
```

## 🎨 Filament Resources

### Employee Resource
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Employee Management';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Form schema per Employee
        ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table->columns([
            // Table columns per Employee
        ]);
    }
}
```

### Dashboard
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBaseDashboard;

class EmployeeDashboard extends XotBaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Employee Dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeStatsWidget::class,
            AttendanceOverviewWidget::class,
            RecentActivitiesWidget::class,
        ];
    }
}
```

## 📱 Progressive Web App (PWA)

### Service Worker
```javascript
// resources/js/employee-sw.js
self.addEventListener('push', function(event) {
    const options = {
        body: event.data.text(),
        icon: '/images/employee-icon.png',
        badge: '/images/badge.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: '2'
        },
        actions: [
            {
                action: 'explore',
                title: 'View Details',
                icon: '/images/checkmark.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/images/xmark.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('Employee Notification', options)
    );
});
```

### Manifest
```json
{
    "name": "Employee Management",
    "short_name": "Employee",
    "description": "Employee Management System",
    "start_url": "/employee",
    "display": "standalone",
    "background_color": "#ffffff",
    "theme_color": "#000000",
    "icons": [
        {
            "src": "/images/employee-192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/images/employee-512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
```

## 🔐 Sicurezza e Privacy

### Crittografia Dati Sensibili
```php
// Model con attributi crittografati
class Employee extends XotBaseModel
{
    protected $casts = [
        'salary_info' => 'encrypted:array',
        'personal_data' => 'encrypted:array',
    ];
}

// Service per gestione sicura file
class SecureFileService
{
    public function storePayslip(UploadedFile $file, Employee $employee): string
    {
        $encryptedContent = encrypt($file->getContent());
        $hash = hash('sha256', $file->getContent());
        
        // Store con path crittografato
        $path = "payslips/{$employee->id}/" . Str::uuid() . '.encrypted';
        Storage::put($path, $encryptedContent);
        
        return $path;
    }
}
```

### Audit Trail
```php
class EmployeeAuditService
{
    public function logAction(string $action, Model $model, ?User $user = null): void
    {
        AuditLog::create([
            'user_id' => $user?->id ?? auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
}
```

## 📊 Analytics e Reporting

### Query Builder per Report
```php
class AttendanceReportService
{
    public function getMonthlyReport(int $employeeId, Carbon $month): array
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->selectRaw('
                COUNT(*) as total_days,
                SUM(total_hours) as total_hours,
                SUM(overtime_hours) as overtime_hours,
                AVG(total_hours) as avg_daily_hours
            ')
            ->first()
            ->toArray();
    }
}
```

### Widget Dashboard
```php
class EmployeeStatsWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.stats';
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', Employee::count())
                ->description('Active employees')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
            Stat::make('Present Today', $this->getPresentToday())
                ->description('Currently at work')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }
}
```

## 🔗 API Design

### RESTful Endpoints
```php
// routes/api.php
Route::prefix('employee')->group(function () {
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('time-entries', TimeEntryController::class);
    Route::apiResource('attendances', AttendanceController::class);
    
    // Endpoints specifici
    Route::post('time-entries/clock-in', [TimeEntryController::class, 'clockIn']);
    Route::post('time-entries/clock-out', [TimeEntryController::class, 'clockOut']);
    Route::get('employees/{employee}/payslips', [PayslipController::class, 'index']);
});
```

### API Resources
```php
class EmployeeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'employee_code' => $this->employee_code,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'position' => new PositionResource($this->whenLoaded('position')),
            'status' => $this->status->value,
            'hire_date' => $this->hire_date->format('Y-m-d'),
        ];
    }
}
```

## 🚀 Performance e Scalabilità

### Caching Strategy
```php
class EmployeeCacheService
{
    public function getEmployeeStats(int $employeeId): array
    {
        return Cache::remember(
            "employee.stats.{$employeeId}",
            now()->addHours(1),
            fn() => $this->calculateStats($employeeId)
        );
    }
}
```

### Queue Jobs
```php
class ProcessAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle(): void
    {
        // Elaborazione presenze in background
        $this->processTimeEntries();
        $this->calculateOvertimes();
        $this->generateReports();
    }
}
```

## 📝 Testing Strategy

### Feature Tests
```php
class TimeEntryTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_employee_can_clock_in(): void
    {
        $employee = Employee::factory()->create();
        
        $response = $this->actingAs($employee->user)
            ->postJson('/api/employee/time-entries/clock-in', [
                'location_lat' => 45.4642,
                'location_lng' => 9.1900,
            ]);
            
        $response->assertStatus(201);
        $this->assertDatabaseHas('time_entries', [
            'employee_id' => $employee->id,
            'type' => 'clock_in',
        ]);
    }
}
```

---

*Documento creato: 2025-07-30*  
*Versione: 1.0*  
*Stato: Architettura tecnica dettagliata*  
*Conformità: Regole XotBase e pattern Laraxot/PTVX*
