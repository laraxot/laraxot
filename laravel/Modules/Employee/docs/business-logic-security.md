# Business Logic - Security & Authorization

## Panoramica

Il sistema di sicurezza del modulo Employee implementa un controllo degli accessi multi-livello basato su ruoli, gerarchia organizzativa e policy granulari. Garantisce che ogni utente possa accedere solo ai dati appropriati al suo ruolo e posizione.

## Authorization Architecture

### 1.1 Role-Based Access Control (RBAC)

```php
// Ruoli principali nel sistema
enum UserRoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';     // Accesso completo al sistema
    case HR_ADMIN = 'hr_admin';           // Gestione completa dipendenti
    case DEPARTMENT_MANAGER = 'dept_manager'; // Gestione dipartimento
    case TEAM_LEAD = 'team_lead';         // Gestione team
    case EMPLOYEE = 'employee';           // Dipendente standard
    case CONTRACTOR = 'contractor';       // Collaboratore esterno
}

// Gerarchia permessi
class RoleHierarchy
{
    private const HIERARCHY = [
        'super_admin' => ['hr_admin', 'dept_manager', 'team_lead', 'employee', 'contractor'],
        'hr_admin' => ['dept_manager', 'team_lead', 'employee', 'contractor'],
        'dept_manager' => ['team_lead', 'employee'],
        'team_lead' => ['employee'],
        'employee' => [],
        'contractor' => []
    ];
    
    public static function inheritsFrom(string $role, string $targetRole): bool
    {
        return in_array($targetRole, self::HIERARCHY[$role] ?? []);
    }
}
```

### 1.2 Hierarchical Access Control

```php
class HierarchicalAccessControl
{
    public function canAccessEmployee(User $accessor, Employee $target): bool
    {
        // Super admin può accedere a tutto
        if ($accessor->hasRole('super_admin')) {
            return true;
        }
        
        // HR admin può accedere a tutti i dipendenti
        if ($accessor->hasRole('hr_admin')) {
            return true;
        }
        
        // Dipendente può accedere ai propri dati
        if ($accessor->id === $target->id) {
            return true;
        }
        
        // Manager può accedere ai subordinati diretti e indiretti
        if ($this->isInReportingChain($accessor, $target)) {
            return true;
        }
        
        // Department manager può accedere a dipendenti del dipartimento
        if ($this->isInSameDepartmentHierarchy($accessor, $target)) {
            return true;
        }
        
        return false;
    }
    
    private function isInReportingChain(User $manager, Employee $subordinate): bool
    {
        $current = $subordinate->manager;
        
        while ($current) {
            if ($current->id === $manager->id) {
                return true;
            }
            $current = $current->manager;
        }
        
        return false;
    }
    
    private function isInSameDepartmentHierarchy(User $accessor, Employee $target): bool
    {
        if (!$accessor->hasRole(['dept_manager', 'team_lead'])) {
            return false;
        }
        
        $accessorEmployee = Employee::find($accessor->id);
        if (!$accessorEmployee) {
            return false;
        }
        
        // Verifica se il target è nel dipartimento o sotto-dipartimenti dell'accessor
        $accessorDepartment = $accessorEmployee->department;
        $targetDepartment = $target->department;
        
        return $this->isDepartmentInHierarchy($targetDepartment, $accessorDepartment);
    }
    
    private function isDepartmentInHierarchy(?Department $target, ?Department $ancestor): bool
    {
        if (!$target || !$ancestor) {
            return false;
        }
        
        $current = $target;
        while ($current) {
            if ($current->id === $ancestor->id) {
                return true;
            }
            $current = $current->parent;
        }
        
        return false;
    }
}
```

## Policy-Based Authorization

### 2.1 WorkHour Policy

```php
class WorkHourPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(UserContract $user): bool
    {
        return $user->hasAnyPermission([
            'view_work_hours',
            'view_own_work_hours',
            'view_subordinate_work_hours'
        ]);
    }
    
    public function view(UserContract $user, WorkHour $workHour): bool
    {
        // Super admin e HR possono vedere tutto
        if ($user->hasRole(['super_admin', 'hr_admin'])) {
            return true;
        }
        
        // Dipendente può vedere le proprie timbrature
        if ($user->id === $workHour->employee_id) {
            return true;
        }
        
        // Manager può vedere timbrature dei subordinati
        if ($user->hasPermissionTo('view_subordinate_work_hours')) {
            $hierarchyControl = app(HierarchicalAccessControl::class);
            $employee = Employee::find($workHour->employee_id);
            
            return $employee && $hierarchyControl->canAccessEmployee($user, $employee);
        }
        
        return false;
    }
    
    public function create(UserContract $user): bool
    {
        // Solo dipendenti attivi possono creare timbrature
        if (!$user->hasRole(['employee', 'team_lead', 'dept_manager'])) {
            return false;
        }
        
        $employee = Employee::find($user->id);
        return $employee && $employee->status === EmployeeStatusEnum::ACTIVE;
    }
    
    public function update(UserContract $user, WorkHour $workHour): bool
    {
        // Controllo finestra temporale (24 ore)
        if ($workHour->created_at->diffInHours(now()) > 24) {
            return false;
        }
        
        // Dipendente può modificare le proprie timbrature
        if ($user->id === $workHour->employee_id) {
            return true;
        }
        
        // Manager può modificare timbrature dei subordinati se ha permesso
        if ($user->hasPermissionTo('edit_subordinate_work_hours')) {
            $hierarchyControl = app(HierarchicalAccessControl::class);
            $employee = Employee::find($workHour->employee_id);
            
            return $employee && $hierarchyControl->canAccessEmployee($user, $employee);
        }
        
        return false;
    }
    
    public function delete(UserContract $user, WorkHour $workHour): bool
    {
        // Solo HR admin e super admin possono cancellare
        if (!$user->hasRole(['super_admin', 'hr_admin'])) {
            return false;
        }
        
        // Non si possono cancellare timbrature approvate
        return $workHour->status !== WorkHourStatusEnum::APPROVED;
    }
    
    public function approve(UserContract $user, WorkHour $workHour): bool
    {
        // Solo manager e superiori possono approvare
        if (!$user->hasAnyRole(['team_lead', 'dept_manager', 'hr_admin', 'super_admin'])) {
            return false;
        }
        
        // Non può approvare le proprie timbrature
        if ($user->id === $workHour->employee_id) {
            return false;
        }
        
        // Manager può approvare subordinati
        $hierarchyControl = app(HierarchicalAccessControl::class);
        $employee = Employee::find($workHour->employee_id);
        
        return $employee && $hierarchyControl->canAccessEmployee($user, $employee);
    }
}
```

### 2.2 Employee Policy

```php
class EmployeePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(UserContract $user): bool
    {
        return $user->hasAnyPermission([
            'view_employees',
            'view_department_employees',
            'view_subordinate_employees'
        ]);
    }
    
    public function view(UserContract $user, Employee $employee): bool
    {
        $hierarchyControl = app(HierarchicalAccessControl::class);
        return $hierarchyControl->canAccessEmployee($user, $employee);
    }
    
    public function create(UserContract $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'hr_admin']);
    }
    
    public function update(UserContract $user, Employee $employee): bool
    {
        // Super admin e HR possono modificare tutto
        if ($user->hasRole(['super_admin', 'hr_admin'])) {
            return true;
        }
        
        // Dipendente può modificare alcuni propri dati
        if ($user->id === $employee->id) {
            return $user->hasPermissionTo('edit_own_profile');
        }
        
        // Manager può modificare dati dei subordinati (limitato)
        if ($user->hasPermissionTo('edit_subordinate_profiles')) {
            $hierarchyControl = app(HierarchicalAccessControl::class);
            return $hierarchyControl->canAccessEmployee($user, $employee);
        }
        
        return false;
    }
    
    public function delete(UserContract $user, Employee $employee): bool
    {
        // Solo super admin può eliminare dipendenti
        return $user->hasRole('super_admin');
    }
    
    public function changeStatus(UserContract $user, Employee $employee): bool
    {
        // HR admin può cambiare stati
        if ($user->hasRole(['super_admin', 'hr_admin'])) {
            return true;
        }
        
        // Department manager può sospendere subordinati
        if ($user->hasRole('dept_manager') && $user->hasPermissionTo('suspend_subordinates')) {
            $hierarchyControl = app(HierarchicalAccessControl::class);
            return $hierarchyControl->canAccessEmployee($user, $employee);
        }
        
        return false;
    }
}
```

## Data Access Control

### 3.1 Query Scoping

```php
trait HasEmployeeScope
{
    public function scopeAccessibleBy(Builder $query, User $user): Builder
    {
        // Super admin vede tutto
        if ($user->hasRole('super_admin')) {
            return $query;
        }
        
        // HR admin vede tutti i dipendenti
        if ($user->hasRole('hr_admin')) {
            return $query;
        }
        
        $conditions = collect();
        
        // Dipendente vede se stesso
        $conditions->push(['id' => $user->id]);
        
        // Manager vede subordinati
        if ($user->hasAnyRole(['dept_manager', 'team_lead'])) {
            $subordinateIds = $this->getSubordinateIds($user);
            if ($subordinateIds->isNotEmpty()) {
                $conditions->push(['id' => $subordinateIds->toArray()]);
            }
        }
        
        // Department manager vede dipendenti del dipartimento
        if ($user->hasRole('dept_manager')) {
            $employee = Employee::find($user->id);
            if ($employee && $employee->department) {
                $departmentEmployeeIds = $employee->department->allEmployees()->pluck('id');
                $conditions->push(['id' => $departmentEmployeeIds->toArray()]);
            }
        }
        
        return $query->where(function($q) use ($conditions) {
            foreach ($conditions as $condition) {
                if (is_array($condition['id'])) {
                    $q->orWhereIn('id', $condition['id']);
                } else {
                    $q->orWhere('id', $condition['id']);
                }
            }
        });
    }
    
    private function getSubordinateIds(User $user): Collection
    {
        $employee = Employee::find($user->id);
        return $employee ? $employee->allSubordinates()->pluck('id') : collect();
    }
}

// Utilizzo nei modelli
class Employee extends BaseModel
{
    use HasEmployeeScope;
}

class WorkHour extends BaseModel
{
    public function scopeAccessibleBy(Builder $query, User $user): Builder
    {
        // Super admin vede tutto
        if ($user->hasRole('super_admin')) {
            return $query;
        }
        
        // HR admin vede tutto
        if ($user->hasRole('hr_admin')) {
            return $query;
        }
        
        // Filtra per dipendenti accessibili
        $accessibleEmployeeIds = Employee::accessibleBy($user)->pluck('id');
        
        return $query->whereIn('employee_id', $accessibleEmployeeIds);
    }
}
```

### 3.2 Field-Level Security

```php
class EmployeeDataFilter
{
    public function filterSensitiveData(Employee $employee, User $accessor): array
    {
        $data = $employee->toArray();
        
        // Super admin e HR vedono tutto
        if ($accessor->hasRole(['super_admin', 'hr_admin'])) {
            return $data;
        }
        
        // Dipendente vede i propri dati completi
        if ($accessor->id === $employee->id) {
            return $data;
        }
        
        // Manager vede dati limitati dei subordinati
        if ($this->canAccessEmployee($accessor, $employee)) {
            return $this->getManagerView($data);
        }
        
        // Colleghi vedono solo dati pubblici
        return $this->getPublicView($data);
    }
    
    private function getManagerView(array $data): array
    {
        $allowedFields = [
            'id', 'name', 'email', 'department_id', 'position_id',
            'manager_id', 'hire_date', 'status', 'phone'
        ];
        
        return array_intersect_key($data, array_flip($allowedFields));
    }
    
    private function getPublicView(array $data): array
    {
        $allowedFields = [
            'id', 'name', 'department_id', 'position_id'
        ];
        
        return array_intersect_key($data, array_flip($allowedFields));
    }
}
```

## Security Audit & Logging

### 4.1 Audit Trail Implementation

```php
class LogSecurityAccessAction
{
    use QueueableAction;

    public function execute(User $user, string $resource, string $action, ?Model $target = null): void
    {
        SecurityAuditLog::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'resource' => $resource,
            'action' => $action,
            'target_type' => $target ? get_class($target) : null,
            'target_id' => $target?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
    
    public function logUnauthorizedAccess(User $user, string $resource, string $action, string $reason): void
    {
        SecurityViolationLog::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'resource' => $resource,
            'action' => $action,
            'violation_reason' => $reason,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
        
        // Notifica security team per violazioni ripetute
        $this->checkForSecurityThreats($user);
    }
    
    private function checkForSecurityThreats(User $user): void
    {
        $recentViolations = SecurityViolationLog::where('user_id', $user->id)
            ->where('timestamp', '>', now()->subHours(1))
            ->count();
            
        if ($recentViolations >= 5) {
            event(new SuspiciousActivityDetected($user, $recentViolations));
        }
    }
}
```

### 4.2 Middleware Security

```php
class EmployeeAccessMiddleware
{
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Log accesso
        app(SecurityAuditService::class)->logAccess(
            $user,
            'employee_module',
            $request->method() . ' ' . $request->path()
        );
        
        // Verifica permesso specifico se richiesto
        if ($permission && !$user->hasPermissionTo($permission)) {
            app(SecurityAuditService::class)->logUnauthorizedAccess(
                $user,
                'employee_module',
                $permission,
                'Missing permission'
            );
            
            return response()->json(['error' => 'Insufficient permissions'], 403);
        }
        
        // Rate limiting per API
        if ($request->is('api/*')) {
            $rateLimiter = app(RateLimiter::class);
            $key = 'api_access:' . $user->id;
            
            if ($rateLimiter->tooManyAttempts($key, 100)) { // 100 richieste per minuto
                return response()->json(['error' => 'Too many requests'], 429);
            }
            
            $rateLimiter->hit($key, 60);
        }
        
        return $next($request);
    }
}
```

## API Security

### 5.1 Token-Based Authentication

```php
class ApiAuthController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        
        if (!Auth::attempt($credentials)) {
            app(SecurityAuditService::class)->logUnauthorizedAccess(
                User::where('email', $credentials['email'])->first() ?? new User(),
                'api_auth',
                'login',
                'Invalid credentials'
            );
            
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
        $user = Auth::user();
        
        // Verifica che l'utente sia attivo
        if ($user instanceof Employee && $user->status !== EmployeeStatusEnum::ACTIVE) {
            return response()->json(['error' => 'Account not active'], 403);
        }
        
        // Genera token con scadenza
        $token = $user->createToken('api-token', ['employee-api'], now()->addHours(8));
        
        app(SecurityAuditService::class)->logAccess($user, 'api_auth', 'login');
        
        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'user' => app(EmployeeDataFilter::class)->filterSensitiveData($user, $user),
        ]);
    }
    
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        
        app(SecurityAuditService::class)->logAccess($user, 'api_auth', 'logout');
        
        return response()->json(['message' => 'Logged out successfully']);
    }
}
```

### 5.2 API Rate Limiting

```php
class GetApiRateLimitsAction
{
    use QueueableAction;

    public function execute(User $user): array
    {
        return match(true) {
            $user->hasRole('super_admin') => [
                'requests_per_minute' => 1000,
                'requests_per_hour' => 10000,
            ],
            $user->hasRole(['hr_admin', 'dept_manager']) => [
                'requests_per_minute' => 200,
                'requests_per_hour' => 2000,
            ],
            $user->hasRole(['team_lead', 'employee']) => [
                'requests_per_minute' => 100,
                'requests_per_hour' => 1000,
            ],
            default => [
                'requests_per_minute' => 50,
                'requests_per_hour' => 500,
            ]
        };
    }
}
```

## Data Encryption & Privacy

### 6.1 Sensitive Data Encryption

```php
class EmployeeDataEncryption
{
    private array $encryptedFields = [
        'social_security_number',
        'bank_account',
        'personal_phone',
        'emergency_contact_phone',
        'medical_notes',
    ];
    
    public function encryptSensitiveData(array $data): array
    {
        foreach ($this->encryptedFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $data[$field] = encrypt($data[$field]);
            }
        }
        
        return $data;
    }
    
    public function decryptSensitiveData(Employee $employee, User $accessor): Employee
    {
        // Solo utenti autorizzati possono vedere dati decriptati
        if (!$this->canViewSensitiveData($accessor, $employee)) {
            return $employee;
        }
        
        foreach ($this->encryptedFields as $field) {
            if (!empty($employee->$field)) {
                try {
                    $employee->$field = decrypt($employee->$field);
                } catch (DecryptException $e) {
                    Log::warning("Failed to decrypt {$field} for employee {$employee->id}");
                    $employee->$field = '[ENCRYPTED]';
                }
            }
        }
        
        return $employee;
    }
    
    private function canViewSensitiveData(User $accessor, Employee $employee): bool
    {
        // Solo HR admin e super admin possono vedere dati sensibili
        if ($accessor->hasRole(['super_admin', 'hr_admin'])) {
            return true;
        }
        
        // Dipendente può vedere i propri dati sensibili
        return $accessor->id === $employee->id;
    }
}
```

### 6.2 GDPR Compliance

```php
class ExportEmployeeDataAction
{
    use QueueableAction;

    public function execute(Employee $employee, User $requester): array
    {
        // Verifica autorizzazione
        if ($requester->id !== $employee->id && !$requester->hasRole(['super_admin', 'hr_admin'])) {
            throw new UnauthorizedException('Cannot export data for another employee');
        }
        
        return [
            'personal_data' => $this->getPersonalData($employee),
            'work_hours' => $this->getWorkHoursData($employee),
            'performance_reviews' => $this->getPerformanceData($employee),
            'audit_logs' => $this->getAuditLogs($employee),
            'exported_at' => now()->toISOString(),
            'exported_by' => $requester->email,
        ];
    }
    
    public function deleteEmployeeData(Employee $employee, User $requester, string $reason): void
    {
        // Solo super admin può eliminare dati
        if (!$requester->hasRole('super_admin')) {
            throw new UnauthorizedException('Insufficient permissions to delete employee data');
        }
        
        DB::transaction(function() use ($employee, $requester, $reason) {
            // Anonimizza dati invece di eliminare per mantenere integrità referenziale
            $employee->update([
                'name' => 'Deleted User',
                'email' => 'deleted_' . $employee->id . '@deleted.local',
                'phone' => null,
                'address' => null,
                'social_security_number' => null,
                'bank_account' => null,
                'emergency_contact_name' => null,
                'emergency_contact_phone' => null,
                'deleted_at' => now(),
                'deleted_by' => $requester->id,
                'deletion_reason' => $reason,
            ]);
            
            // Log eliminazione
            DataDeletionLog::create([
                'employee_id' => $employee->id,
                'deleted_by' => $requester->id,
                'reason' => $reason,
                'deleted_at' => now(),
            ]);
        });
    }
}
```

---

*Documento creato: 2025-01-06*  
*Versione: 1.0*  
*Stato: Completo*
