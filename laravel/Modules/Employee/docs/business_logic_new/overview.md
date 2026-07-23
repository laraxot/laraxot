# Business Logic Overview - Employee Module

## 📚 Executive Summary

Il modulo Employee implementa un sistema HR completo usando **esclusivamente Spatie QueueableActions** per tutta la business logic, seguendo rigorosamente le convenzioni Laraxot.

## 🚨 REGOLA CRITICA LARAXOT

**MAI utilizzare Services - SEMPRE utilizzare Spatie QueueableActions**

Tutti gli esempi in questo documento utilizzano il pattern Actions con il trait `QueueableAction`.

## 🏗️ Architettura Business Logic

### Core Business Entities

```
Employee (Dipendente) → Actions
├── CreateEmployeeAction
├── UpdateEmployeeAction
├── TransferEmployeeAction
├── ChangeEmployeeStatusAction
└── DeleteEmployeeAction

WorkHour (Timbratura) → Actions  
├── ClockInAction
├── ClockOutAction
├── StartBreakAction
├── EndBreakAction
├── ValidateTimeSequenceAction
└── CalculateDailyHoursAction

Department (Reparto) → Actions
├── CreateDepartmentAction
├── AssignEmployeeAction
├── GetDepartmentMetricsAction
└── ReorganizeDepartmentAction
```

## 🎯 Core Business Domains

### 1. Time Tracking Actions

```php
namespace Modules\Employee\Actions\TimeTracking;

use Spatie\QueueableAction\QueueableAction;

class ClockInAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, ?array $location = null): WorkHour
    {
        // Validazione business rules
        app(ValidateClockInAction::class)->execute($employeeId, $timestamp);
        
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_IN,
            'timestamp' => $timestamp,
            'location_lat' => $location['lat'] ?? null,
            'location_lng' => $location['lng'] ?? null,
            'status' => WorkHourStatusEnum::PENDING,
        ]);
    }
}

class ValidateTimeSequenceAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, WorkHourTypeEnum $type): bool
    {
        $lastEntry = app(GetLastWorkHourEntryAction::class)->execute($employeeId, $timestamp);
        $expectedAction = app(GetNextExpectedActionAction::class)->execute($lastEntry);
        
        if ($type !== $expectedAction) {
            throw new InvalidTimeSequenceException(
                "Expected: {$expectedAction->value}, got: {$type->value}"
            );
        }
        
        return true;
    }
}

class CalculateDailyHoursAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): float
    {
        $entries = app(GetDailyWorkHourEntriesAction::class)->execute($employeeId, $date);
        
        return $this->calculateFromEntries($entries);
    }
    
    private function calculateFromEntries(Collection $entries): float
    {
        $totalMinutes = 0;
        $sessionStart = null;
        
        foreach ($entries as $entry) {
            switch ($entry->type) {
                case WorkHourTypeEnum::CLOCK_IN:
                    $sessionStart = $entry->timestamp;
                    break;
                    
                case WorkHourTypeEnum::CLOCK_OUT:
                    if ($sessionStart) {
                        $totalMinutes += $sessionStart->diffInMinutes($entry->timestamp);
                        $sessionStart = null;
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_START:
                    if ($sessionStart) {
                        $totalMinutes += $sessionStart->diffInMinutes($entry->timestamp);
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_END:
                    $sessionStart = $entry->timestamp;
                    break;
            }
        }
        
        return round($totalMinutes / 60, 2);
    }
}
```

### 2. Employee Management Actions

```php
namespace Modules\Employee\Actions\EmployeeManagement;

class CreateEmployeeAction
{
    use QueueableAction;

    public function execute(EmployeeData $data, User $creator): Employee
    {
        return DB::transaction(function() use ($data, $creator) {
            // Crea user base
            $user = app(CreateUserAction::class)->execute($data->getUserData());
            
            // Crea employee record
            $employee = Employee::create([
                'id' => $user->id,
                'employee_number' => app(GenerateEmployeeNumberAction::class)->execute(),
                'department_id' => $data->departmentId,
                'manager_id' => $data->managerId,
                'position_id' => $data->positionId,
                'hire_date' => $data->hireDate ?? today(),
                'status' => EmployeeStatusEnum::ACTIVE,
                'created_by' => $creator->id,
            ]);
            
            // Inizia onboarding
            app(InitiateOnboardingAction::class)->execute($employee);
            
            event(new EmployeeCreated($employee, $creator));
            
            return $employee;
        });
    }
}

class TransferEmployeeAction
{
    use QueueableAction;

    public function execute(Employee $employee, Department $newDepartment, User $actor, ?string $reason = null): void
    {
        // Validazioni
        app(ValidateDepartmentTransferAction::class)->execute($employee, $newDepartment);
        
        DB::transaction(function() use ($employee, $newDepartment, $actor, $reason) {
            $oldDepartment = $employee->department;
            
            // Audit trail
            app(CreateTransferRecordAction::class)->execute($employee, $oldDepartment, $newDepartment, $actor, $reason);
            
            // Aggiorna assegnazione
            $employee->update(['department_id' => $newDepartment->id]);
            
            // Gestione manager
            app(HandleManagerReassignmentAction::class)->execute($employee, $newDepartment);
            
            event(new EmployeeTransferred($employee, $oldDepartment, $newDepartment, $actor));
        });
    }
}

class ChangeEmployeeStatusAction
{
    use QueueableAction;

    public function execute(Employee $employee, EmployeeStatusEnum $newStatus, User $actor, ?string $reason = null): void
    {
        // Validazione transizione
        app(ValidateStatusTransitionAction::class)->execute($employee->status, $newStatus);
        
        // Storia cambiamenti
        app(CreateStatusHistoryAction::class)->execute($employee, $newStatus, $actor, $reason);
        
        $employee->update(['status' => $newStatus]);
        
        event(new EmployeeStatusChanged($employee, $newStatus, $actor));
    }
}
```

### 3. Analytics & Reporting Actions

```php
namespace Modules\Employee\Actions\Analytics;

class GenerateAttendanceReportAction
{
    use QueueableAction;

    public function execute(Department $department, Carbon $startDate, Carbon $endDate): AttendanceReportData
    {
        $employees = $department->allEmployees();
        $metrics = [];
        
        foreach ($employees as $employee) {
            $metrics[] = app(CalculateEmployeeMetricsAction::class)
                ->execute($employee, $startDate, $endDate);
        }
        
        return new AttendanceReportData(
            department: $department,
            startDate: $startDate,
            endDate: $endDate,
            employeeMetrics: $metrics,
            generatedAt: now()
        );
    }
}

class CalculateEmployeeMetricsAction
{
    use QueueableAction;

    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate): EmployeeMetricsData
    {
        return new EmployeeMetricsData(
            employeeId: $employee->id,
            attendanceRate: app(CalculateAttendanceRateAction::class)->execute($employee, $startDate, $endDate),
            punctualityScore: app(CalculatePunctualityScoreAction::class)->execute($employee, $startDate, $endDate),
            overtimeHours: app(CalculateOvertimeHoursAction::class)->execute($employee, $startDate, $endDate),
            totalWorkHours: app(CalculateTotalWorkHoursAction::class)->execute($employee, $startDate, $endDate)
        );
    }
}

class ExportTimeDataAction
{
    use QueueableAction;

    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate, string $format = 'xlsx'): string
    {
        $timeData = app(GetEmployeeTimeDataAction::class)->execute($employee, $startDate, $endDate);
        
        return match($format) {
            'xlsx' => app(ExportToExcelAction::class)->execute($timeData),
            'pdf' => app(ExportToPdfAction::class)->execute($timeData),
            'csv' => app(ExportToCsvAction::class)->execute($timeData),
            default => throw new InvalidExportFormatException("Unsupported format: {$format}")
        };
    }
}
```

## 🔒 Security Actions

### Authorization Actions

```php
namespace Modules\Employee\Actions\Security;

class ValidateEmployeeAccessAction
{
    use QueueableAction;

    public function execute(User $accessor, Employee $target): bool
    {
        // Super admin può accedere a tutto
        if ($accessor->hasRole('super_admin')) {
            return true;
        }
        
        // Dipendente può accedere ai propri dati
        if ($accessor->id === $target->id) {
            return true;
        }
        
        // Manager può accedere ai subordinati
        return app(CheckHierarchicalAccessAction::class)->execute($accessor, $target);
    }
}

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
}
```

## 🚀 Performance Optimization

### Caching Actions

```php
class GetCachedEmployeeStatsAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): EmployeeStatsData
    {
        $cacheKey = "employee_stats:{$employeeId}:{$date->format('Y-m-d')}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function() use ($employeeId, $date) {
            return app(CalculateEmployeeStatsAction::class)->execute($employeeId, $date);
        });
    }
}

class InvalidateEmployeeCacheAction
{
    use QueueableAction;

    public function execute(int $employeeId, ?Carbon $date = null): void
    {
        $pattern = $date 
            ? "employee_stats:{$employeeId}:{$date->format('Y-m-d')}"
            : "employee_stats:{$employeeId}:*";
            
        Cache::flush(); // In production, use more specific cache invalidation
    }
}
```

### Batch Processing Actions

```php
class ProcessBulkTimeEntriesAction
{
    use QueueableAction;

    public function execute(array $timeEntries): array
    {
        $results = [];
        
        foreach ($timeEntries as $entryData) {
            try {
                $results[] = app(CreateTimeEntryAction::class)->execute($entryData);
            } catch (Exception $e) {
                Log::error('Bulk time entry failed', [
                    'data' => $entryData,
                    'error' => $e->getMessage()
                ]);
                $results[] = ['error' => $e->getMessage(), 'data' => $entryData];
            }
        }
        
        return $results;
    }
}
```

## 📋 Action Checklist

### Pre-Implementation
- [ ] Verificare che non esista già un'Action simile
- [ ] Definire chiaramente la single responsibility
- [ ] Progettare input/output con Data Objects
- [ ] Pianificare gestione errori ed eccezioni

### Implementation
- [ ] Utilizzare QueueableAction trait
- [ ] Implementare type hints completi
- [ ] Aggiungere PHPDoc dettagliato
- [ ] Gestire eccezioni specifiche
- [ ] Implementare logging appropriato

### Post-Implementation
- [ ] Scrivere test completi (sync + async)
- [ ] Documentare nel README del modulo
- [ ] Aggiornare usage nei Controller/Resources
- [ ] Verificare performance e ottimizzazioni

---

*Documento creato: 2025-01-06*  
*Pattern: Spatie QueueableActions*  
*Compliance: Laraxot conventions*