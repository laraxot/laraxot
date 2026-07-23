# Guida Implementazione Tecnica - Modulo Employee

## Architettura del Sistema

### Separazione Frontoffice/Backoffice
- **Frontoffice**: Folio + Volt + Filament (componenti)
- **Backoffice**: Filament completo
- **Nessun Controller**: Tutto gestito da Filament

### Struttura Directory
```
laravel/Modules/Employee/
├── app/
│   ├── Filament/
│   │   ├── Pages/
│   │   │   ├── Dashboard.php
│   │   │   ├── EmployeeDashboard.php
│   │   │   └── Reports/
│   │   ├── Resources/
│   │   │   ├── EmployeeResource.php
│   │   │   ├── ContractResource.php
│   │   │   ├── AttendanceResource.php
│   │   │   ├── PayrollResource.php
│   │   │   └── PerformanceResource.php
│   │   ├── Widgets/
│   │   │   ├── EmployeeStatsWidget.php
│   │   │   ├── AttendanceChartWidget.php
│   │   │   └── PayrollSummaryWidget.php
│   │   └── Actions/
│   │       ├── ApproveLeaveAction.php
│   │       ├── GeneratePayrollAction.php
│   │       └── ExportDataAction.php
│   ├── Models/
│   │   ├── Employee.php
│   │   ├── Contract.php
│   │   ├── Attendance.php
│   │   ├── Payroll.php
│   │   ├── Performance.php
│   │   └── Document.php
│   ├── Http/
│   │   └── Livewire/
│   │       ├── Employee/
│   │       │   ├── Profile.php
│   │       │   ├── LeaveRequest.php
│   │       │   └── PayrollView.php
│   │       └── Admin/
│   │           ├── EmployeeManager.php
│   │           └── AttendanceManager.php
│   └── Providers/
│       ├── EmployeeServiceProvider.php
│       └── Filament/
│           └── AdminPanelProvider.php
├── database/
│   ├── migrations/
│   │   ├── create_employees_table.php
│   │   ├── create_contracts_table.php
│   │   ├── create_attendances_table.php
│   │   ├── create_payrolls_table.php
│   │   └── create_performances_table.php
│   ├── seeders/
│   │   └── EmployeeSeeder.php
│   └── factories/
│       └── EmployeeFactory.php
├── resources/
│   ├── views/
│   │   ├── livewire/
│   │   │   ├── employee/
│   │   │   └── admin/
│   │   └── pages/
│   │       └── employee/
│   └── lang/
│       ├── it/
│       ├── en/
│       └── de/
└── routes/
    ├── web.php
    └── api.php
```

## Modelli di Dati

### Employee Model
```php
<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'first_name',
        'last_name',
        'birth_date',
        'birth_place',
        'fiscal_code',
        'email',
        'phone',
        'address',
        'hire_date',
        'contract_type',
        'level',
        'department',
        'position',
        'status',
        'photo_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
```

### Contract Model
```php
<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'employee_id',
        'contract_type',
        'start_date',
        'end_date',
        'level',
        'category',
        'base_salary',
        'status',
        'document_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
```

### Attendance Model
```php
<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end',
        'hours_worked',
        'overtime_hours',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
```

## Filament Resources

### EmployeeResource
```php
<?php

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Employee\Models\Employee;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestione Dipendenti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dati Personali')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Cognome')
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Data di Nascita'),
                        Forms\Components\TextInput::make('fiscal_code')
                            ->label('Codice Fiscale'),
                    ])->columns(2),

                Forms\Components\Section::make('Contatti')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Telefono'),
                        Forms\Components\Textarea::make('address')
                            ->label('Indirizzo'),
                    ])->columns(2),

                Forms\Components\Section::make('Dati Lavorativi')
                    ->schema([
                        Forms\Components\DatePicker::make('hire_date')
                            ->label('Data Assunzione'),
                        Forms\Components\Select::make('contract_type')
                            ->label('Tipo Contratto')
                            ->options([
                                'indeterminato' => 'Tempo Indeterminato',
                                'determinato' => 'Tempo Determinato',
                                'apprendistato' => 'Apprendistato',
                            ]),
                        Forms\Components\Select::make('department')
                            ->label('Reparto'),
                        Forms\Components\Select::make('status')
                            ->label('Stato')
                            ->options([
                                'active' => 'Attivo',
                                'inactive' => 'Inattivo',
                                'on_leave' => 'In Ferie',
                                'sick' => 'In Malattia',
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')
                    ->label('Codice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Cognome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department')
                    ->label('Reparto'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Stato')
                    ->badge(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->label('Data Assunzione')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'active' => 'Attivo',
                        'inactive' => 'Inattivo',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->label('Reparto'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

## Livewire Components (Frontoffice)

### Employee Profile Component
```php
<?php

namespace Modules\Employee\Http\Livewire\Employee;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Employee\Models\Employee;

class Profile extends Component
{
    use WithFileUploads;

    public Employee $employee;
    public $photo;

    protected $rules = [
        'employee.first_name' => 'required|string|max:255',
        'employee.last_name' => 'required|string|max:255',
        'employee.email' => 'required|email',
        'employee.phone' => 'nullable|string',
        'employee.address' => 'nullable|string',
        'photo' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $this->employee = auth()->user()->employee;
    }

    public function save()
    {
        $this->validate();

        if ($this->photo) {
            $this->employee->photo_path = $this->photo->store('employees/photos', 'public');
        }

        $this->employee->save();

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('employee::livewire.employee.profile');
    }
}
```

### Leave Request Component
```php
<?php

namespace Modules\Employee\Http\Livewire\Employee;

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Modules\Employee\Models\LeaveRequest;

class LeaveRequest extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Tipo Richiesta')
                    ->options([
                        'ferie' => 'Ferie',
                        'permesso' => 'Permesso',
                        'malattia' => 'Malattia',
                    ])
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Data Inizio')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Data Fine')
                    ->required(),
                Textarea::make('reason')
                    ->label('Motivazione')
                    ->rows(3),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        LeaveRequest::create([
            'employee_id' => auth()->user()->employee->id,
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'status' => 'pending',
        ]);

        $this->form->fill();

        $this->dispatch('leave-request-submitted');
    }

    public function render()
    {
        return view('employee::livewire.employee.leave-request');
    }
}
```

## Filament Widgets

### Employee Stats Widget
```php
<?php

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Modules\Employee\Models\Employee;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsWidget extends XotBaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Dipendenti Totali', Employee::count())
                ->description('Numero totale dipendenti')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Dipendenti Attivi', Employee::where('status', 'active')->count())
                ->description('Dipendenti attualmente attivi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('In Ferie', Employee::where('status', 'on_leave')->count())
                ->description('Dipendenti attualmente in ferie')
                ->descriptionIcon('heroicon-m-sun')
                ->color('warning'),

            Stat::make('In Malattia', Employee::where('status', 'sick')->count())
                ->description('Dipendenti attualmente in malattia')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
        ];
    }
}
```

## Migrations

### Create Employees Table
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('level')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave', 'sick'])->default('active');
            $table->string('photo_path')->nullable();
            $table->timestamps();

            $table->index(['status', 'department']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
```

## Routes

### Web Routes
```php
<?php

use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Livewire\Employee\Profile;
use Modules\Employee\Http\Livewire\Employee\LeaveRequest;

Route::middleware(['auth'])->group(function () {
    // Employee routes (frontoffice)
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/leave-request', LeaveRequest::class)->name('leave-request');
    });
});
```

## Views (Frontoffice)

### Employee Profile View
```blade
<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                Profilo Dipendente
            </h3>
            
            <form wire:submit="save" class="mt-6 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome
                        </label>
                        <input type="text" wire:model="employee.first_name" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cognome
                        </label>
                        <input type="text" wire:model="employee.last_name" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" wire:model="employee.email" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Telefono
                        </label>
                        <input type="tel" wire:model="employee.phone" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Foto Profilo
                    </label>
                    <input type="file" wire:model="photo" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Salva Modifiche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
```

## Traduzioni

### Italian (it/employee.php)
```php
<?php

return [
    'employees' => [
        'title' => 'Dipendenti',
        'create' => 'Nuovo Dipendente',
        'edit' => 'Modifica Dipendente',
        'delete' => 'Elimina Dipendente',
        'list' => 'Lista Dipendenti',
    ],
    'contracts' => [
        'title' => 'Contratti',
        'create' => 'Nuovo Contratto',
        'edit' => 'Modifica Contratto',
    ],
    'attendance' => [
        'title' => 'Presenze',
        'check_in' => 'Entrata',
        'check_out' => 'Uscita',
        'hours_worked' => 'Ore Lavorate',
        'overtime' => 'Straordinari',
    ],
    'payroll' => [
        'title' => 'Buste Paga',
        'generate' => 'Genera Busta Paga',
        'download' => 'Scarica Busta Paga',
    ],
    'performance' => [
        'title' => 'Performance',
        'evaluate' => 'Valuta Performance',
        'objectives' => 'Obiettivi',
    ],
];
```

## Configurazione

### employee.php Config
```php
<?php

return [
    'defaults' => [
        'contract_type' => 'indeterminato',
        'status' => 'active',
    ],
    
    'departments' => [
        'hr' => 'Risorse Umane',
        'it' => 'Informatica',
        'sales' => 'Vendite',
        'marketing' => 'Marketing',
        'finance' => 'Finanza',
        'operations' => 'Operazioni',
    ],
    
    'contract_types' => [
        'indeterminato' => 'Tempo Indeterminato',
        'determinato' => 'Tempo Determinato',
        'apprendistato' => 'Apprendistato',
        'collaborazione' => 'Collaborazione',
    ],
    
    'statuses' => [
        'active' => 'Attivo',
        'inactive' => 'Inattivo',
        'on_leave' => 'In Ferie',
        'sick' => 'In Malattia',
    ],
];
```

---
*Guida implementazione tecnica modulo Employee - Sistema completo HR management* 