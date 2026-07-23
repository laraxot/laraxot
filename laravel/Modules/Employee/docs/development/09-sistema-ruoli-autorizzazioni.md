# 09 - Sistema Ruoli e Autorizzazioni

## Panoramica
Sistema completo di gestione ruoli e autorizzazioni granulari per controllare l'accesso alle funzionalità del modulo Employee, con supporto per ruoli gerarchici, permessi dinamici e audit trail completo.

## Obiettivi
- Implementare sistema ruoli gerarchico
- Fornire controllo granulare delle autorizzazioni
- Gestire permessi dinamici basati su contesto
- Implementare audit trail completo
- Supportare delegation temporanea di autorizzazioni
- Integrare con sistema autenticazione aziendale

## Funzionalità da Implementare

### 1. Sistema Ruoli Gerarchico

#### 1.1 Definizione Ruoli e Gerarchia
**Obiettivo**: Creare sistema ruoli flessibile con ereditarietà gerarchica

**Implementazione Step-by-Step**:

1. **Creare il Model Role**
```php
// app/Models/Role.php
class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'level', // 1=employee, 2=supervisor, 3=manager, 4=director, 5=admin
        'parent_role_id',
        'is_system_role',
        'is_active',
        'department_id', // null per ruoli globali
        'max_subordinates',
        'can_delegate'
    ];

    protected $casts = [
        'is_system_role' => 'boolean',
        'is_active' => 'boolean',
        'can_delegate' => 'boolean'
    ];

    public function parentRole()
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }

    public function childRoles()
    {
        return $this->hasMany(Role::class, 'parent_role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withPivot(['granted_at', 'granted_by', 'expires_at'])
                    ->withTimestamps();
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Ottieni tutti i permessi inclusi quelli ereditati
    public function getAllPermissions(): Collection
    {
        $permissions = $this->permissions;
        
        if ($this->parentRole) {
            $permissions = $permissions->merge($this->parentRole->getAllPermissions());
        }
        
        return $permissions->unique('id');
    }
}
```

2. **Creare il Model Permission**
```php
// app/Models/Permission.php
class Permission extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category', // attendance, payroll, reports, admin
        'resource', // employee, leave_request, expense_report
        'action', // view, create, edit, delete, approve
        'scope', // own, team, department, all
        'is_system_permission',
        'requires_context'
    ];

    protected $casts = [
        'is_system_permission' => 'boolean',
        'requires_context' => 'boolean'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withPivot(['granted_at', 'granted_by', 'expires_at'])
                    ->withTimestamps();
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForResource($query, string $resource)
    {
        return $query->where('resource', $resource);
    }
}
```

3. **Creare Migration per roles**
```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->integer('level')->default(1);
    $table->foreignId('parent_role_id')->nullable()->constrained('roles');
    $table->boolean('is_system_role')->default(false);
    $table->boolean('is_active')->default(true);
    $table->foreignId('department_id')->nullable()->constrained();
    $table->integer('max_subordinates')->nullable();
    $table->boolean('can_delegate')->default(false);
    $table->timestamps();
    
    $table->index(['level', 'is_active']);
    $table->index(['department_id', 'is_active']);
});
```

4. **Creare Migration per permissions**
```php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('category'); // attendance, payroll, reports, admin
    $table->string('resource'); // employee, leave_request, expense_report
    $table->string('action'); // view, create, edit, delete, approve
    $table->enum('scope', ['own', 'team', 'department', 'all'])->default('own');
    $table->boolean('is_system_permission')->default(false);
    $table->boolean('requires_context')->default(false);
    $table->timestamps();
    
    $table->index(['category', 'resource', 'action']);
    $table->index(['scope']);
});
```

5. **Creare Filament Resource RoleResource**
```php
// app/Filament/Resources/RoleResource.php
class RoleResource extends XotBaseResource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Amministrazione';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informazioni Ruolo')->schema([
                TextInput::make('name')
                    ->label('Nome Ruolo')
                    ->required()
                    ->maxLength(100),
                    
                TextInput::make('slug')
                    ->label('Codice')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z0-9_-]+$/')
                    ->helperText('Solo lettere minuscole, numeri, trattini e underscore'),
                    
                Textarea::make('description')
                    ->label('Descrizione')
                    ->maxLength(500),
                    
                Select::make('level')
                    ->label('Livello Gerarchico')
                    ->options([
                        1 => 'Dipendente (1)',
                        2 => 'Supervisore (2)',
                        3 => 'Manager (3)',
                        4 => 'Direttore (4)',
                        5 => 'Amministratore (5)'
                    ])
                    ->required()
                    ->default(1)
            ]),
            
            Section::make('Configurazione Gerarchia')->schema([
                Select::make('parent_role_id')
                    ->label('Ruolo Genitore')
                    ->relationship('parentRole', 'name')
                    ->nullable()
                    ->helperText('Il ruolo erediterà i permessi dal ruolo genitore'),
                    
                Select::make('department_id')
                    ->label('Dipartimento')
                    ->relationship('department', 'name')
                    ->nullable()
                    ->helperText('Lasciare vuoto per ruolo globale'),
                    
                TextInput::make('max_subordinates')
                    ->label('Max Subordinati')
                    ->numeric()
                    ->nullable()
                    ->helperText('Numero massimo di dipendenti gestibili'),
                    
                Toggle::make('can_delegate')
                    ->label('Può Delegare')
                    ->helperText('Può delegare temporaneamente i propri permessi')
            ]),
            
            Section::make('Permessi')->schema([
                CheckboxList::make('permissions')
                    ->label('Permessi Assegnati')
                    ->relationship('permissions', 'name')
                    ->options(function () {
                        return Permission::all()
                            ->groupBy('category')
                            ->map(function ($permissions, $category) {
                                return $permissions->pluck('name', 'id');
                            })
                            ->toArray();
                    })
                    ->columns(2)
                    ->searchable()
            ]),
            
            Section::make('Stato')->schema([
                Toggle::make('is_active')
                    ->label('Attivo')
                    ->default(true),
                    
                Toggle::make('is_system_role')
                    ->label('Ruolo di Sistema')
                    ->helperText('I ruoli di sistema non possono essere eliminati')
                    ->disabled(fn ($record) => $record?->is_system_role)
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('level')
                    ->label('Livello')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'gray',
                        2 => 'info',
                        3 => 'warning',
                        4 => 'success',
                        5 => 'danger',
                    }),
                    
                TextColumn::make('department.name')
                    ->label('Dipartimento')
                    ->placeholder('Globale'),
                    
                TextColumn::make('employees_count')
                    ->label('Dipendenti')
                    ->counts('employees')
                    ->alignCenter(),
                    
                TextColumn::make('permissions_count')
                    ->label('Permessi')
                    ->counts('permissions')
                    ->alignCenter(),
                    
                IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
                    
                IconColumn::make('is_system_role')
                    ->label('Sistema')
                    ->boolean()
            ])
            ->filters([
                SelectFilter::make('level')
                    ->options([
                        1 => 'Dipendente',
                        2 => 'Supervisore',
                        3 => 'Manager',
                        4 => 'Direttore',
                        5 => 'Amministratore'
                    ]),
                    
                SelectFilter::make('department_id')
                    ->relationship('department', 'name'),
                    
                Filter::make('is_active')
                    ->toggle()
            ])
            ->actions([
                Action::make('clone_permissions')
                    ->label('Clona Permessi')
                    ->icon('heroicon-o-document-duplicate')
                    ->form([
                        Select::make('target_role_id')
                            ->label('Ruolo Destinazione')
                            ->options(Role::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                    ])
                    ->action(function (Role $record, array $data) {
                        $targetRole = Role::find($data['target_role_id']);
                        $permissions = $record->permissions->pluck('id');
                        $targetRole->permissions()->sync($permissions);
                    })
            ]);
    }
}
```

### 2. Controllo Autorizzazioni Granulare

#### 2.1 Service Autorizzazioni
**Obiettivo**: Implementare logica controllo autorizzazioni con contesto

**Implementazione**:

1. **Creare Service AuthorizationService**
```php
// app/Services/AuthorizationService.php
class AuthorizationService
{
    public function can(Employee $employee, string $permission, $resource = null): bool
    {
        $role = $employee->role;
        
        if (!$role || !$role->is_active) {
            return false;
        }
        
        // Controlla permesso diretto
        if ($this->hasDirectPermission($role, $permission)) {
            return $this->checkScope($employee, $permission, $resource);
        }
        
        // Controlla permessi ereditati
        return $this->hasInheritedPermission($role, $permission) && 
               $this->checkScope($employee, $permission, $resource);
    }
    
    private function hasDirectPermission(Role $role, string $permission): bool
    {
        return $role->permissions()
                   ->where('slug', $permission)
                   ->where(function ($query) {
                       $query->whereNull('expires_at')
                             ->orWhere('expires_at', '>', now());
                   })
                   ->exists();
    }
    
    private function hasInheritedPermission(Role $role, string $permission): bool
    {
        if (!$role->parentRole) {
            return false;
        }
        
        return $this->hasDirectPermission($role->parentRole, $permission) ||
               $this->hasInheritedPermission($role->parentRole, $permission);
    }
    
    private function checkScope(Employee $employee, string $permission, $resource): bool
    {
        $permissionModel = Permission::where('slug', $permission)->first();
        
        if (!$permissionModel) {
            return false;
        }
        
        return match($permissionModel->scope) {
            'own' => $this->checkOwnScope($employee, $resource),
            'team' => $this->checkTeamScope($employee, $resource),
            'department' => $this->checkDepartmentScope($employee, $resource),
            'all' => true,
            default => false
        };
    }
    
    private function checkOwnScope(Employee $employee, $resource): bool
    {
        if (!$resource) return true;
        
        // Se il resource è un Employee, controlla che sia lo stesso
        if ($resource instanceof Employee) {
            return $resource->id === $employee->id;
        }
        
        // Se il resource ha un employee_id, controlla che corrisponda
        if (isset($resource->employee_id)) {
            return $resource->employee_id === $employee->id;
        }
        
        return false;
    }
    
    private function checkTeamScope(Employee $employee, $resource): bool
    {
        if (!$resource) return true;
        
        // Ottieni i membri del team gestiti dall'employee
        $teamMembers = Employee::where('manager_id', $employee->id)->pluck('id');
        $teamMembers->push($employee->id); // Includi se stesso
        
        if ($resource instanceof Employee) {
            return $teamMembers->contains($resource->id);
        }
        
        if (isset($resource->employee_id)) {
            return $teamMembers->contains($resource->employee_id);
        }
        
        return false;
    }
    
    private function checkDepartmentScope(Employee $employee, $resource): bool
    {
        if (!$resource) return true;
        
        if ($resource instanceof Employee) {
            return $resource->department_id === $employee->department_id;
        }
        
        if (isset($resource->employee)) {
            return $resource->employee->department_id === $employee->department_id;
        }
        
        return false;
    }
    
    public function getAccessibleEmployees(Employee $currentEmployee, string $permission): Collection
    {
        $permissionModel = Permission::where('slug', $permission)->first();
        
        if (!$permissionModel || !$this->can($currentEmployee, $permission)) {
            return collect();
        }
        
        return match($permissionModel->scope) {
            'own' => collect([$currentEmployee]),
            'team' => Employee::where('manager_id', $currentEmployee->id)
                             ->orWhere('id', $currentEmployee->id)
                             ->get(),
            'department' => Employee::where('department_id', $currentEmployee->department_id)->get(),
            'all' => Employee::all(),
            default => collect()
        };
    }
}
```

### 3. Middleware Autorizzazioni

#### 3.1 Middleware per Filament
**Obiettivo**: Integrare controllo autorizzazioni in Filament

**Implementazione**:

1. **Creare Middleware EmployeeAuthorizationMiddleware**
```php
// app/Http/Middleware/EmployeeAuthorizationMiddleware.php
class EmployeeAuthorizationMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();
        
        if (!$user || !$user->employee) {
            abort(403, 'Accesso negato: dipendente non trovato');
        }
        
        $authService = app(AuthorizationService::class);
        
        if (!$authService->can($user->employee, $permission)) {
            abort(403, 'Accesso negato: permessi insufficienti');
        }
        
        return $next($request);
    }
}
```

2. **Creare Policy per Models**
```php
// app/Policies/EmployeePolicy.php
class EmployeePolicy
{
    public function viewAny(User $user): bool
    {
        return app(AuthorizationService::class)
            ->can($user->employee, 'employee.view');
    }
    
    public function view(User $user, Employee $employee): bool
    {
        return app(AuthorizationService::class)
            ->can($user->employee, 'employee.view', $employee);
    }
    
    public function create(User $user): bool
    {
        return app(AuthorizationService::class)
            ->can($user->employee, 'employee.create');
    }
    
    public function update(User $user, Employee $employee): bool
    {
        return app(AuthorizationService::class)
            ->can($user->employee, 'employee.edit', $employee);
    }
    
    public function delete(User $user, Employee $employee): bool
    {
        return app(AuthorizationService::class)
            ->can($user->employee, 'employee.delete', $employee);
    }
}
```

### 4. Audit Trail e Logging

#### 4.1 Sistema Audit Completo
**Obiettivo**: Tracciare tutte le azioni per compliance e sicurezza

**Implementazione**:

1. **Creare Model AuditLog**
```php
// app/Models/AuditLog.php
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'action',
        'resource_type',
        'resource_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'session_id',
        'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'performed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
```

2. **Creare Service AuditService**
```php
// app/Services/AuditService.php
class AuditService
{
    public function log(string $action, $resource, array $oldValues = [], array $newValues = []): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'employee_id' => auth()->user()?->employee?->id,
            'action' => $action,
            'resource_type' => get_class($resource),
            'resource_id' => $resource->id ?? null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'performed_at' => now()
        ]);
    }
    
    public function getAuditTrail($resource, int $limit = 50): Collection
    {
        return AuditLog::where('resource_type', get_class($resource))
                      ->where('resource_id', $resource->id)
                      ->with(['user', 'employee'])
                      ->orderBy('performed_at', 'desc')
                      ->limit($limit)
                      ->get();
    }
}
```

## Checklist Implementazione

### Phase 1: Base Models e Database
- [ ] Creare migrations per roles, permissions, role_permissions
- [ ] Implementare Models con relazioni gerarchiche
- [ ] Creare Seeders per ruoli e permessi base

### Phase 2: Core Authorization
- [ ] Implementare AuthorizationService
- [ ] Creare Policies per tutti i Models
- [ ] Configurare Middleware autorizzazioni

### Phase 3: UI Filament
- [ ] Creare RoleResource e PermissionResource
- [ ] Implementare gestione permessi granulari
- [ ] Dashboard gestione autorizzazioni

### Phase 4: Audit e Compliance
- [ ] Implementare AuditService completo
- [ ] Creare dashboard audit trail
- [ ] Sistema alerting per azioni sensibili

### Phase 5: Advanced Features
- [ ] Delegation temporanea permessi
- [ ] Integrazione SSO aziendale
- [ ] API per sistemi esterni

## Note Tecniche

### Sicurezza
- Hash e salt per dati sensibili
- Rate limiting per azioni critiche
- Encryption per audit logs

### Performance
- Cache per controlli autorizzazioni frequenti
- Indicizzazione ottimale database
- Query optimization per scope checks

### Compliance
- Retention policy per audit logs
- GDPR compliance per dati personali
- SOX compliance per controlli finanziari

Questo sistema fornirà controllo granulare e sicuro delle autorizzazioni con audit trail completo.
