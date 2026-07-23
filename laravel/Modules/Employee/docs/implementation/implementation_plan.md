# Piano di Implementazione - Modulo Employee

## Panoramica

Questo documento definisce il piano di implementazione dettagliato per replicare le funzionalità di dipendentincloud.it nel modulo Employee.

## Fase 1: Core HR (Priorità ALTA)

### 1.1 Modelli di Base

#### Employee Model
```php
// laravel/Modules/Employee/app/Models/Employee.php
class Employee extends Model
{
    // Dati personali
    protected $fillable = [
        'matricola', 'nome', 'cognome', 'email', 'telefono',
        'data_nascita', 'luogo_nascita', 'sesso', 'stato_civile',
        'indirizzo', 'città', 'cap', 'provincia',
        'data_assunzione', 'data_cessazione', 'stato',
        'department_id', 'location_id', 'role_id', 'manager_id'
    ];

    // Relazioni
    public function department() { return $this->belongsTo(Department::class); }
    public function location() { return $this->belongsTo(Location::class); }
    public function role() { return $this->belongsTo(Role::class); }
    public function manager() { return $this->belongsTo(Employee::class, 'manager_id'); }
    public function contracts() { return $this->hasMany(Contract::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function salaries() { return $this->hasMany(Salary::class); }
    public function documents() { return $this->hasMany(Document::class); }
}
```

#### Contract Model
```php
// laravel/Modules/Employee/app/Models/Contract.php
class Contract extends Model
{
    protected $fillable = [
        'employee_id', 'tipo_contratto', 'data_inizio', 'data_fine',
        'retribuzione_base', 'indennità', 'note', 'stato'
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
}
```

#### Attendance Model
```php
// laravel/Modules/Employee/app/Models/Attendance.php
class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'data', 'ora_entrata', 'ora_uscita',
        'tipo_presenza', 'note', 'approvato', 'approvato_da'
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
}
```

### 1.2 Resources Filament

#### EmployeeResource
```php
// laravel/Modules/Employee/app/Filament/Resources/EmployeeResource.php
class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestione Dipendenti';

    public static function getFormSchema(): array
    {
        return [
            'matricola' => Forms\Components\TextInput::make('matricola')
                ->required()
                ->unique(ignoreRecord: true),
            'nome' => Forms\Components\TextInput::make('nome')
                ->required(),
            'cognome' => Forms\Components\TextInput::make('cognome')
                ->required(),
            'email' => Forms\Components\TextInput::make('email')
                ->email()
                ->required(),
            'telefono' => Forms\Components\TextInput::make('telefono'),
            'data_nascita' => Forms\Components\DatePicker::make('data_nascita'),
            'data_assunzione' => Forms\Components\DatePicker::make('data_assunzione')
                ->required(),
            'department_id' => Forms\Components\Select::make('department_id')
                ->relationship('department', 'nome')
                ->searchable(),
            'role_id' => Forms\Components\Select::make('role_id')
                ->relationship('role', 'nome')
                ->searchable(),
            'manager_id' => Forms\Components\Select::make('manager_id')
                ->relationship('manager', 'nome')
                ->searchable(),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('matricola')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('nome')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('cognome')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('email')
                ->searchable(),
            Tables\Columns\TextColumn::make('department.nome')
                ->sortable(),
            Tables\Columns\TextColumn::make('data_assunzione')
                ->date()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('stato')
                ->colors([
                    'success' => 'attivo',
                    'danger' => 'inattivo',
                ]),
        ];
    }
}
```

### 1.3 Dashboard Principale

#### EmployeeDashboard
```php
// laravel/Modules/Employee/app/Filament/Pages/Dashboard.php
class Dashboard extends XotBaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'employee::filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            EmployeeStatsWidget::class,
            RecentHiresWidget::class,
            ContractExpiryWidget::class,
            DepartmentOverviewWidget::class,
        ];
    }
}
```

### 1.4 Widget Dashboard

#### EmployeeStatsWidget
```php
// laravel/Modules/Employee/app/Filament/Widgets/EmployeeStatsWidget.php
class EmployeeStatsWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.employee-stats';

    public function getFormSchema(): array
    {
        return [
            // Schema vuoto per widget statistiche
        ];
    }

    protected function getViewData(): array
    {
        return [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('stato', 'attivo')->count(),
            'new_hires_month' => Employee::whereMonth('data_assunzione', now()->month)->count(),
            'departments_count' => Department::count(),
        ];
    }
}
```

## Fase 2: Gestione Organizzativa (Priorità MEDIA)

### 2.1 Modelli Organizzativi

#### Department Model
```php
// laravel/Modules/Employee/app/Models/Department.php
class Department extends Model
{
    protected $fillable = [
        'nome', 'descrizione', 'manager_id', 'location_id', 'stato'
    ];

    public function employees() { return $this->hasMany(Employee::class); }
    public function manager() { return $this->belongsTo(Employee::class, 'manager_id'); }
    public function location() { return $this->belongsTo(Location::class); }
    public function roles() { return $this->hasMany(Role::class); }
}
```

#### Location Model
```php
// laravel/Modules/Employee/app/Models/Location.php
class Location extends Model
{
    protected $fillable = [
        'nome', 'indirizzo', 'città', 'provincia', 'cap', 'stato'
    ];

    public function departments() { return $this->hasMany(Department::class); }
    public function employees() { return $this->hasManyThrough(Employee::class, Department::class); }
}
```

### 2.2 Resources Organizzative

#### DepartmentResource
```php
// laravel/Modules/Employee/app/Filament/Resources/DepartmentResource.php
class DepartmentResource extends XotBaseResource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Organizzazione';

    public static function getFormSchema(): array
    {
        return [
            'nome' => Forms\Components\TextInput::make('nome')
                ->required(),
            'descrizione' => Forms\Components\Textarea::make('descrizione'),
            'manager_id' => Forms\Components\Select::make('manager_id')
                ->relationship('manager', 'nome')
                ->searchable(),
            'location_id' => Forms\Components\Select::make('location_id')
                ->relationship('location', 'nome')
                ->searchable(),
        ];
    }
}
```

## Fase 3: Gestione Presenze (Priorità ALTA)

### 3.1 AttendanceResource
```php
// laravel/Modules/Employee/app/Filament/Resources/AttendanceResource.php
class AttendanceResource extends XotBaseResource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Presenze';

    public static function getFormSchema(): array
    {
        return [
            'employee_id' => Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'nome')
                ->searchable()
                ->required(),
            'data' => Forms\Components\DatePicker::make('data')
                ->required(),
            'ora_entrata' => Forms\Components\TimePicker::make('ora_entrata'),
            'ora_uscita' => Forms\Components\TimePicker::make('ora_uscita'),
            'tipo_presenza' => Forms\Components\Select::make('tipo_presenza')
                ->options([
                    'normale' => 'Lavoro normale',
                    'straordinario' => 'Straordinario',
                    'ferie' => 'Ferie',
                    'permesso' => 'Permesso',
                    'malattia' => 'Malattia',
                ])
                ->required(),
            'note' => Forms\Components\Textarea::make('note'),
        ];
    }
}
```

### 3.2 AttendanceCalendar Page
```php
// laravel/Modules/Employee/app/Filament/Pages/AttendanceCalendar.php
class AttendanceCalendar extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'employee::filament.pages.attendance-calendar';

    public function getFormSchema(): array
    {
        return [
            // Schema per filtri calendario
        ];
    }
}
```

## Fase 4: Self-Service Dipendenti

### 4.1 EmployeeSelfService Page
```php
// laravel/Modules/Employee/app/Filament/Pages/EmployeeSelfService.php
class EmployeeSelfService extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'employee::filament.pages.employee-self-service';

    public function mount(): void
    {
        // Verifica che l'utente sia un dipendente
        $this->employee = Employee::where('user_id', auth()->id())->first();
        
        if (!$this->employee) {
            redirect()->route('employee.dashboard');
        }
    }

    public function getFormSchema(): array
    {
        return [
            'nome' => Forms\Components\TextInput::make('nome')
                ->disabled(),
            'cognome' => Forms\Components\TextInput::make('cognome')
                ->disabled(),
            'email' => Forms\Components\TextInput::make('email')
                ->email(),
            'telefono' => Forms\Components\TextInput::make('telefono'),
            'indirizzo' => Forms\Components\TextInput::make('indirizzo'),
        ];
    }
}
```

### 4.2 LeaveRequest Page
```php
// laravel/Modules/Employee/app/Filament/Pages/LeaveRequest.php
class LeaveRequest extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static string $view = 'employee::filament.pages.leave-request';

    public function getFormSchema(): array
    {
        return [
            'tipo_permesso' => Forms\Components\Select::make('tipo_permesso')
                ->options([
                    'ferie' => 'Ferie',
                    'permesso' => 'Permesso',
                    'malattia' => 'Malattia',
                ])
                ->required(),
            'data_inizio' => Forms\Components\DatePicker::make('data_inizio')
                ->required(),
            'data_fine' => Forms\Components\DatePicker::make('data_fine')
                ->required(),
            'motivazione' => Forms\Components\Textarea::make('motivazione'),
        ];
    }
}
```

## Fase 5: Gestione Documenti

### 5.1 Document Model
```php
// laravel/Modules/Employee/app/Models/Document.php
class Document extends Model
{
    protected $fillable = [
        'employee_id', 'tipo', 'nome', 'file_path',
        'data_creazione', 'data_scadenza', 'stato'
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
}
```

### 5.2 DocumentResource
```php
// laravel/Modules/Employee/app/Filament/Resources/DocumentResource.php
class DocumentResource extends XotBaseResource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Documenti';

    public static function getFormSchema(): array
    {
        return [
            'employee_id' => Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'nome')
                ->searchable()
                ->required(),
            'tipo' => Forms\Components\Select::make('tipo')
                ->options([
                    'contratto' => 'Contratto di lavoro',
                    'documento_personale' => 'Documento personale',
                    'certificato' => 'Certificato',
                    'busta_paga' => 'Busta paga',
                    'altro' => 'Altro',
                ])
                ->required(),
            'nome' => Forms\Components\TextInput::make('nome')
                ->required(),
            'file_path' => Forms\Components\FileUpload::make('file_path')
                ->directory('employee-documents')
                ->acceptedFileTypes(['pdf', 'doc', 'docx', 'jpg', 'png'])
                ->required(),
            'data_scadenza' => Forms\Components\DatePicker::make('data_scadenza'),
        ];
    }
}
```

## Migrazioni Database

### 1. Create Employees Table
```php
// laravel/Modules/Employee/database/migrations/2025_07_30_000001_create_employees_table.php
class CreateEmployeesTable extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('matricola')->unique();
            $table->string('nome');
            $table->string('cognome');
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->date('data_nascita')->nullable();
            $table->string('luogo_nascita')->nullable();
            $table->enum('sesso', ['M', 'F'])->nullable();
            $table->string('stato_civile')->nullable();
            $table->text('indirizzo')->nullable();
            $table->string('città')->nullable();
            $table->string('cap')->nullable();
            $table->string('provincia')->nullable();
            $table->date('data_assunzione');
            $table->date('data_cessazione')->nullable();
            $table->enum('stato', ['attivo', 'inattivo', 'licenziato'])->default('attivo');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();
        });
    }
}
```

### 2. Create Departments Table
```php
// laravel/Modules/Employee/database/migrations/2025_07_30_000002_create_departments_table.php
class CreateDepartmentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descrizione')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->enum('stato', ['attivo', 'inattivo'])->default('attivo');
            $table->timestamps();
        });
    }
}
```

## Viste Blade

### 1. Dashboard View
```blade
{{-- laravel/Modules/Employee/resources/views/filament/pages/dashboard.blade.php --}}
<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        {{-- KPI Cards --}}
        <x-filament::card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-heroicon-o-users class="h-8 w-8 text-gray-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Dipendenti Totali</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalEmployees }}</p>
                </div>
            </div>
        </x-filament::card>
        
        {{-- Altri widget... --}}
    </div>
    
    {{-- Widgets --}}
    @foreach($this->getWidgets() as $widget)
        @livewire($widget)
    @endforeach
</x-filament-panels::page>
```

### 2. Employee Self-Service View
```blade
{{-- laravel/Modules/Employee/resources/views/filament/pages/employee-self-service.blade.php --}}
<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Profilo Dipendente --}}
        <div class="lg:col-span-2">
            <x-filament::card>
                <x-filament::card.header>
                    <x-filament::card.heading>Il Mio Profilo</x-filament::card.heading>
                </x-filament::card.header>
                
                <x-filament::card.content>
                    {{ $this->form }}
                </x-filament::card.content>
            </x-filament::card>
        </div>
        
        {{-- Sidebar con informazioni rapide --}}
        <div>
            <x-filament::card>
                <x-filament::card.header>
                    <x-filament::card.heading>Informazioni Rapide</x-filament::card.heading>
                </x-filament::card.header>
                
                <x-filament::card.content>
                    {{-- Presenze del mese, ferie residue, etc. --}}
                </x-filament::card.content>
            </x-filament::card>
        </div>
    </div>
</x-filament-panels::page>
```

## Conclusione

Questo piano di implementazione fornisce una roadmap completa per replicare le funzionalità di dipendentincloud.it nel modulo Employee. L'implementazione sarà modulare e scalabile, permettendo di aggiungere funzionalità progressive.

---

*Documento creato il: 2025-07-30*
*Modulo: Employee*
*Stato: PIANO COMPLETATO*
*Priorità: ALTA* 