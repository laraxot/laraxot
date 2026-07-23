# Implementazione Tecnica Modulo Employee

## Architettura del Sistema

### 1. **Modelli di Dati (Models)**

#### Employee Model
```php
class Employee extends Model
{
    protected $fillable = [
        'user_id', 'employee_code', 'personal_data', 'contact_data',
        'work_data', 'documents', 'photo_url', 'status',
        'department_id', 'manager_id', 'position_id', 'salary_data'
    ];

    protected $casts = [
        'personal_data' => 'array',
        'contact_data' => 'array',
        'work_data' => 'array',
        'documents' => 'array',
        'salary_data' => 'array',
    ];

    // Relazioni
    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function manager() { return $this->belongsTo(Employee::class, 'manager_id'); }
    public function subordinates() { return $this->hasMany(Employee::class, 'manager_id'); }
    public function position() { return $this->belongsTo(Position::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaves() { return $this->hasMany(Leave::class); }
    public function documents() { return $this->hasMany(Document::class); }
}
```

#### Department Model
```php
class Department extends Model
{
    protected $fillable = [
        'name', 'description', 'parent_id', 'manager_id', 'location_id', 'status'
    ];

    // Relazioni gerarchiche
    public function parent() { return $this->belongsTo(Department::class, 'parent_id'); }
    public function children() { return $this->hasMany(Department::class, 'parent_id'); }
    public function employees() { return $this->hasMany(Employee::class); }
    public function manager() { return $this->belongsTo(Employee::class, 'manager_id'); }
}
```

### 2. **Risorse Filament (Resources)**

#### EmployeeResource
```php
class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gestione Dipendenti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Step 1: Dati Personali
                Forms\Components\Section::make('Dati Personali')
                    ->schema([
                        Forms\Components\TextInput::make('personal_data.first_name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('personal_data.last_name')
                            ->label('Cognome')
                            ->required(),
                        Forms\Components\DatePicker::make('personal_data.birth_date')
                            ->label('Data di Nascita'),
                        Forms\Components\TextInput::make('personal_data.birth_place')
                            ->label('Luogo di Nascita'),
                        Forms\Components\Select::make('personal_data.gender')
                            ->label('Sesso')
                            ->options(['M' => 'Maschio', 'F' => 'Femmina']),
                    ]),

                // Step 2: Dati di Contatto
                Forms\Components\Section::make('Dati di Contatto')
                    ->schema([
                        Forms\Components\TextInput::make('contact_data.email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('contact_data.phone')
                            ->label('Telefono'),
                        Forms\Components\TextInput::make('contact_data.address')
                            ->label('Indirizzo'),
                        Forms\Components\TextInput::make('contact_data.city')
                            ->label('Città'),
                        Forms\Components\TextInput::make('contact_data.postal_code')
                            ->label('CAP'),
                        Forms\Components\TextInput::make('contact_data.province')
                            ->label('Provincia'),
                    ]),

                // Step 3: Dati Lavorativi
                Forms\Components\Section::make('Dati Lavorativi')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->label('Codice Dipendente')
                            ->required(),
                        Forms\Components\DatePicker::make('work_data.hire_date')
                            ->label('Data di Assunzione'),
                        Forms\Components\Select::make('work_data.contract_type')
                            ->label('Tipo Contratto')
                            ->options([
                                'indeterminato' => 'Tempo Indeterminato',
                                'determinato' => 'Tempo Determinato',
                                'apprendistato' => 'Apprendistato',
                                'collaborazione' => 'Collaborazione',
                            ]),
                        Forms\Components\Select::make('department_id')
                            ->label('Dipartimento')
                            ->relationship('department', 'name'),
                        Forms\Components\Select::make('position_id')
                            ->label('Posizione')
                            ->relationship('position', 'name'),
                        Forms\Components\Select::make('manager_id')
                            ->label('Responsabile Diretto')
                            ->relationship('manager', 'personal_data->first_name'),
                    ]),

                // Step 4: Documenti
                Forms\Components\Section::make('Documenti')
                    ->schema([
                        Forms\Components\FileUpload::make('documents.identity_card')
                            ->label('Carta d\'Identità')
                            ->acceptedFileTypes(['application/pdf', 'image/*']),
                        Forms\Components\TextInput::make('documents.fiscal_code')
                            ->label('Codice Fiscale'),
                        Forms\Components\TextInput::make('documents.health_card')
                            ->label('Tessera Sanitaria'),
                    ]),

                // Step 5: Foto Profilo
                Forms\Components\Section::make('Foto Profilo')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_url')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->circleCropper(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('employee_code')
                    ->label('Codice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('personal_data.first_name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('personal_data.last_name')
                    ->label('Cognome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Dipartimento'),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('Posizione'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Stato')
                    ->colors([
                        'success' => 'attivo',
                        'warning' => 'inattivo',
                        'danger' => 'licenziato',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Dipartimento')
                    ->relationship('department', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'attivo' => 'Attivo',
                        'inattivo' => 'Inattivo',
                        'licenziato' => 'Licenziato',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

### 3. **Widget e Dashboard**

#### EmployeeStatsWidget
```php
class EmployeeStatsWidget extends XotBaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Dipendenti Totali', Employee::count())
                ->description('Numero totale dipendenti')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Dipendenti Attivi', Employee::where('status', 'attivo')->count())
                ->description('Dipendenti attualmente attivi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('In Ferie Oggi', Employee::whereHas('leaves', function($query) {
                $query->where('type', 'ferie')
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now())
                      ->where('status', 'approvato');
            })->count())
                ->description('Dipendenti in ferie oggi')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),

            Stat::make('Presenze Oggi', Attendance::where('date', today())->count())
                ->description('Presenze registrate oggi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
```

### 4. **Componenti Livewire/Volt**

#### EmployeeProfile Component
```php
class EmployeeProfile extends Component
{
    public Employee $employee;
    public $personalData;
    public $contactData;
    public $workData;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->personalData = $employee->personal_data;
        $this->contactData = $employee->contact_data;
        $this->workData = $employee->work_data;
    }

    public function updatePersonalData()
    {
        $this->validate([
            'personalData.first_name' => 'required|string|max:255',
            'personalData.last_name' => 'required|string|max:255',
            'personalData.birth_date' => 'nullable|date',
            'personalData.birth_place' => 'nullable|string|max:255',
        ]);

        $this->employee->update([
            'personal_data' => $this->personalData
        ]);

        $this->dispatch('employee-updated');
    }

    public function updateContactData()
    {
        $this->validate([
            'contactData.email' => 'required|email',
            'contactData.phone' => 'nullable|string|max:20',
            'contactData.address' => 'nullable|string|max:255',
            'contactData.city' => 'nullable|string|max:100',
            'contactData.postal_code' => 'nullable|string|max:10',
            'contactData.province' => 'nullable|string|max:2',
        ]);

        $this->employee->update([
            'contact_data' => $this->contactData
        ]);

        $this->dispatch('employee-updated');
    }

    public function render()
    {
        return view('employee::components.employee-profile');
    }
}
```

### 5. **API Endpoints**

#### EmployeeController
```php
class EmployeeController extends Controller
{
    public function index()
    {
        return EmployeeResource::collection(
            Employee::with(['department', 'position', 'manager'])
                ->paginate(20)
        );
    }

    public function show(Employee $employee)
    {
        return new EmployeeResource($employee->load([
            'department', 'position', 'manager', 'subordinates',
            'attendances', 'leaves', 'documents'
        ]));
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        
        return new EmployeeResource($employee);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());
        
        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        
        return response()->json(['message' => 'Dipendente eliminato']);
    }

    // Metodi specifici per presenze
    public function attendances(Employee $employee)
    {
        return AttendanceResource::collection(
            $employee->attendances()->paginate(30)
        );
    }

    public function leaves(Employee $employee)
    {
        return LeaveResource::collection(
            $employee->leaves()->paginate(20)
        );
    }

    public function documents(Employee $employee)
    {
        return DocumentResource::collection(
            $employee->documents()->paginate(20)
        );
    }
}
```

### 6. **Workflow e Business Logic**

#### LeaveRequestService
```php
class LeaveRequestService
{
    public function requestLeave(Employee $employee, array $data)
    {
        // Verifica disponibilità ferie
        $availableDays = $this->calculateAvailableDays($employee);
        
        if ($data['days_requested'] > $availableDays) {
            throw new InsufficientLeaveDaysException();
        }

        // Verifica sovrapposizioni
        $overlappingLeaves = $employee->leaves()
            ->where('status', 'approvato')
            ->where(function($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                      ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
            })->exists();

        if ($overlappingLeaves) {
            throw new OverlappingLeaveException();
        }

        // Crea richiesta
        $leave = $employee->leaves()->create([
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_requested' => $data['days_requested'],
            'reason' => $data['reason'],
            'status' => 'richiesta',
        ]);

        // Notifica manager
        if ($employee->manager) {
            $employee->manager->notify(new LeaveRequestNotification($leave));
        }

        return $leave;
    }

    public function approveLeave(Leave $leave, Employee $approver)
    {
        $leave->update([
            'status' => 'approvato',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        // Notifica dipendente
        $leave->employee->notify(new LeaveApprovedNotification($leave));

        return $leave;
    }

    public function rejectLeave(Leave $leave, Employee $rejecter, string $reason)
    {
        $leave->update([
            'status' => 'rifiutato',
            'approved_by' => $rejecter->id,
            'approved_at' => now(),
            'notes' => $reason,
        ]);

        // Notifica dipendente
        $leave->employee->notify(new LeaveRejectedNotification($leave, $reason));

        return $leave;
    }

    private function calculateAvailableDays(Employee $employee): int
    {
        $year = now()->year;
        $totalDays = 25; // Giorni standard
        
        $usedDays = $employee->leaves()
            ->where('type', 'ferie')
            ->where('status', 'approvato')
            ->whereYear('start_date', $year)
            ->sum('days_approved');

        return $totalDays - $usedDays;
    }
}
```

### 7. **Sicurezza e Policies**

#### EmployeePolicy
```php
class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('employee.view');
    }

    public function view(User $user, Employee $employee): bool
    {
        // Un dipendente può vedere solo se stesso o i suoi sottoposti
        if ($user->employee && $user->employee->id === $employee->id) {
            return true;
        }

        if ($user->employee && $employee->manager_id === $user->employee->id) {
            return true;
        }

        return $user->hasPermissionTo('employee.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('employee.create');
    }

    public function update(User $user, Employee $employee): bool
    {
        // Un dipendente può modificare solo se stesso
        if ($user->employee && $user->employee->id === $employee->id) {
            return true;
        }

        return $user->hasPermissionTo('employee.update');
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('employee.delete');
    }
}
```

### 8. **Configurazione e Personalizzazione**

#### EmployeeConfig
```php
class EmployeeConfig
{
    public static function getWorkingHours(): array
    {
        return [
            'monday' => ['start' => '09:00', 'end' => '18:00'],
            'tuesday' => ['start' => '09:00', 'end' => '18:00'],
            'wednesday' => ['start' => '09:00', 'end' => '18:00'],
            'thursday' => ['start' => '09:00', 'end' => '18:00'],
            'friday' => ['start' => '09:00', 'end' => '18:00'],
            'saturday' => ['start' => null, 'end' => null],
            'sunday' => ['start' => null, 'end' => null],
        ];
    }

    public static function getLeaveTypes(): array
    {
        return [
            'ferie' => 'Ferie',
            'permesso' => 'Permesso',
            'malattia' => 'Malattia',
            'maternita' => 'Maternità',
            'paternita' => 'Paternità',
            'altro' => 'Altro',
        ];
    }

    public static function getDocumentTypes(): array
    {
        return [
            'contratto' => 'Contratto di Lavoro',
            'carta_identita' => 'Carta d\'Identità',
            'codice_fiscale' => 'Codice Fiscale',
            'tessera_sanitaria' => 'Tessera Sanitaria',
            'certificato' => 'Certificato',
            'altro' => 'Altro',
        ];
    }
}
```

## Conclusione

Questa implementazione tecnica fornisce una base solida per replicare tutte le funzionalità di dipendentincloud.it nel modulo Employee, mantenendo la flessibilità e l'estensibilità del sistema modulare Laravel.
