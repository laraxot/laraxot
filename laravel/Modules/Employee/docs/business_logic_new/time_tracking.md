# Time Tracking Business Logic - Actions Implementation

## 📚 Overview

Il sistema di Time Tracking è implementato completamente tramite **Spatie QueueableActions**, seguendo una state machine per gestire le timbrature dei dipendenti con integrità dei dati e business rules robuste.

## 🚨 CRITICAL: No Services - Only Actions

Tutti gli esempi utilizzano il pattern QueueableAction secondo le convenzioni Laraxot.

## 🎯 Time Tracking Actions Architecture

### Core Time Tracking Actions

```php
namespace Modules\Employee\Actions\TimeTracking;

use Spatie\QueueableAction\QueueableAction;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Enums\WorkHourStatusEnum;
```

### 1. Primary Clock Actions

#### ClockInAction
```php
class ClockInAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, ?array $location = null): WorkHour
    {
        // Validazione pre-condizioni
        app(ValidateClockInPreconditionsAction::class)->execute($employeeId, $timestamp);
        
        // Verifica sequenza
        app(ValidateTimeSequenceAction::class)->execute($employeeId, $timestamp, WorkHourTypeEnum::CLOCK_IN);
        
        // Verifica duplicati
        app(ValidateNoDuplicateEntryAction::class)->execute($employeeId, $timestamp, WorkHourTypeEnum::CLOCK_IN);
        
        // Crea timbratura
        $workHour = WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_IN,
            'timestamp' => $timestamp,
            'location_lat' => $location['lat'] ?? null,
            'location_lng' => $location['lng'] ?? null,
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => 'Clock in via system',
        ]);
        
        // Invalida cache
        app(InvalidateEmployeeCacheAction::class)->execute($employeeId, $timestamp);
        
        // Trigger eventi
        event(new WorkHourCreated($workHour));
        
        return $workHour;
    }
}
```

#### ClockOutAction
```php
class ClockOutAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp): WorkHour
    {
        // Validazioni
        app(ValidateClockOutPreconditionsAction::class)->execute($employeeId, $timestamp);
        app(ValidateTimeSequenceAction::class)->execute($employeeId, $timestamp, WorkHourTypeEnum::CLOCK_OUT);
        
        // Verifica che ci sia un clock in precedente
        $lastClockIn = app(GetLastClockInAction::class)->execute($employeeId, $timestamp);
        if (!$lastClockIn) {
            throw new NoActiveSessionException('Cannot clock out without an active session');
        }
        
        $workHour = WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::CLOCK_OUT,
            'timestamp' => $timestamp,
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => 'Clock out via system',
        ]);
        
        // Calcola ore della sessione
        app(CalculateSessionHoursAction::class)->execute($lastClockIn, $workHour);
        
        app(InvalidateEmployeeCacheAction::class)->execute($employeeId, $timestamp);
        
        event(new WorkHourCreated($workHour));
        
        return $workHour;
    }
}
```

### 2. Break Management Actions

#### StartBreakAction
```php
class StartBreakAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, ?string $breakType = 'regular'): WorkHour
    {
        // Verifica che sia in sessione attiva
        app(ValidateActiveSessionAction::class)->execute($employeeId, $timestamp);
        
        app(ValidateTimeSequenceAction::class)->execute($employeeId, $timestamp, WorkHourTypeEnum::BREAK_START);
        
        return WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::BREAK_START,
            'timestamp' => $timestamp,
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => "Break start - {$breakType}",
        ]);
    }
}

class EndBreakAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp): WorkHour
    {
        // Verifica che ci sia una pausa attiva
        $activeBreak = app(GetActiveBreakAction::class)->execute($employeeId, $timestamp);
        if (!$activeBreak) {
            throw new NoActiveBreakException('Cannot end break without an active break');
        }
        
        $workHour = WorkHour::create([
            'employee_id' => $employeeId,
            'type' => WorkHourTypeEnum::BREAK_END,
            'timestamp' => $timestamp,
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => 'Break end',
        ]);
        
        // Calcola durata pausa
        app(CalculateBreakDurationAction::class)->execute($activeBreak, $workHour);
        
        return $workHour;
    }
}
```

### 3. Validation Actions

#### ValidateTimeSequenceAction
```php
class ValidateTimeSequenceAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, WorkHourTypeEnum $requestedType): bool
    {
        $lastEntry = app(GetLastWorkHourEntryAction::class)->execute($employeeId, $timestamp);
        $expectedType = app(GetNextExpectedActionAction::class)->execute($lastEntry);
        
        if ($requestedType !== $expectedType) {
            $lastType = $lastEntry ? $lastEntry->type->value : 'none';
            
            throw new InvalidTimeSequenceException(
                "Invalid sequence. Last: {$lastType}, expected: {$expectedType->value}, requested: {$requestedType->value}"
            );
        }
        
        return true;
    }
}

class ValidateWorkingHoursAction
{
    use QueueableAction;

    public function execute(Carbon $timestamp): bool
    {
        // Orari consentiti: 06:00 - 22:00
        if ($timestamp->hour < 6 || $timestamp->hour > 22) {
            throw new InvalidWorkingHoursException(
                "Time entry at {$timestamp->format('H:i')} is outside working hours (06:00-22:00)"
            );
        }
        
        return true;
    }
}

class ValidateNoDuplicateEntryAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $timestamp, WorkHourTypeEnum $type): bool
    {
        $existingEntry = WorkHour::query()
            ->where('employee_id', $employeeId)
            ->where('timestamp', $timestamp)
            ->where('type', $type)
            ->first();

        if ($existingEntry) {
            throw new DuplicateTimeEntryException(
                "Entry already exists for {$timestamp->format('Y-m-d H:i')} with type {$type->value}"
            );
        }
        
        return true;
    }
}
```

### 4. Calculation Actions

#### CalculateDailyHoursAction
```php
class CalculateDailyHoursAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): float
    {
        $entries = app(GetDailyWorkHourEntriesAction::class)->execute($employeeId, $date);
        
        if ($entries->isEmpty()) {
            return 0.0;
        }
        
        return $this->calculateFromEntries($entries);
    }
    
    private function calculateFromEntries(Collection $entries): float
    {
        $totalMinutes = 0;
        $sessionStart = null;
        $breakStart = null;
        
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
                        $breakStart = $entry->timestamp;
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_END:
                    if ($breakStart) {
                        $sessionStart = $entry->timestamp;
                        $breakStart = null;
                    }
                    break;
            }
        }
        
        return round($totalMinutes / 60, 2);
    }
}

class CalculateBreakDurationAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): int
    {
        $entries = app(GetDailyBreakEntriesAction::class)->execute($employeeId, $date);
        
        $totalBreakMinutes = 0;
        $breakStart = null;
        
        foreach ($entries as $entry) {
            if ($entry->type === WorkHourTypeEnum::BREAK_START) {
                $breakStart = $entry->timestamp;
            } elseif ($entry->type === WorkHourTypeEnum::BREAK_END && $breakStart) {
                $totalBreakMinutes += $breakStart->diffInMinutes($entry->timestamp);
                $breakStart = null;
            }
        }
        
        return $totalBreakMinutes;
    }
}
```

### 5. Widget Integration Actions

#### GetTimeClockDataAction
```php
class GetTimeClockDataAction
{
    use QueueableAction;

    public function execute(int $employeeId): TimeClockData
    {
        $todayEntries = app(GetTodayWorkHourEntriesAction::class)->execute($employeeId);
        $sessions = app(BuildWorkSessionsAction::class)->execute($todayEntries);
        $currentStatus = app(GetCurrentWorkStatusAction::class)->execute($todayEntries);
        
        return new TimeClockData(
            currentTime: Carbon::now()->format('H:i'),
            todayDate: Carbon::now()->locale('it')->isoFormat('dddd D MMMM YYYY'),
            todayEntries: $todayEntries->map(fn($entry) => [
                'time' => $entry->timestamp->format('H:i'),
                'type' => $entry->type->value,
            ])->toArray(),
            sessions: $sessions,
            isClockedIn: $currentStatus['is_clocked_in'],
            sessionStatus: $currentStatus['session_status']
        );
    }
}

class BuildWorkSessionsAction
{
    use QueueableAction;

    public function execute(Collection $entries): array
    {
        $sessions = [];
        $currentSession = null;
        
        foreach ($entries as $entry) {
            switch ($entry->type) {
                case WorkHourTypeEnum::CLOCK_IN:
                    if ($currentSession && !isset($currentSession['out'])) {
                        $sessions[] = $currentSession;
                    }
                    
                    $currentSession = [
                        'status' => 'active',
                        'in' => $entry->timestamp->format('H:i'),
                        'out' => null,
                        'breaks' => []
                    ];
                    break;
                    
                case WorkHourTypeEnum::CLOCK_OUT:
                    if ($currentSession) {
                        $currentSession['out'] = $entry->timestamp->format('H:i');
                        $currentSession['status'] = 'completed';
                        $sessions[] = $currentSession;
                        $currentSession = null;
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_START:
                    if ($currentSession) {
                        $currentSession['breaks'][] = [
                            'start' => $entry->timestamp->format('H:i'),
                            'end' => null
                        ];
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_END:
                    if ($currentSession && !empty($currentSession['breaks'])) {
                        $lastBreakIndex = count($currentSession['breaks']) - 1;
                        if (!isset($currentSession['breaks'][$lastBreakIndex]['end'])) {
                            $currentSession['breaks'][$lastBreakIndex]['end'] = $entry->timestamp->format('H:i');
                        }
                    }
                    break;
            }
        }
        
        if ($currentSession) {
            $sessions[] = $currentSession;
        }
        
        return $sessions;
    }
}
```

## 🔄 Action Workflows

### Complete Day Workflow

```php
class ProcessCompleteDayAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): DailyWorkSummary
    {
        // 1. Ottieni tutte le timbrature del giorno
        $entries = app(GetDailyWorkHourEntriesAction::class)->execute($employeeId, $date);
        
        // 2. Valida integrità sequenza
        app(ValidateDayIntegrityAction::class)->execute($entries);
        
        // 3. Calcola metriche
        $totalHours = app(CalculateDailyHoursAction::class)->execute($employeeId, $date);
        $breakDuration = app(CalculateBreakDurationAction::class)->execute($employeeId, $date);
        $sessions = app(BuildWorkSessionsAction::class)->execute($entries);
        
        // 4. Determina stato giornata
        $dayStatus = app(DetermineDayStatusAction::class)->execute($entries);
        
        return new DailyWorkSummary(
            employeeId: $employeeId,
            date: $date,
            totalHours: $totalHours,
            breakDuration: $breakDuration,
            sessions: $sessions,
            status: $dayStatus,
            entries: $entries
        );
    }
}
```

### Approval Workflow

```php
class ApproveTimeEntryAction
{
    use QueueableAction;

    public function execute(WorkHour $workHour, User $approver, ?string $notes = null): WorkHour
    {
        // Validazione autorizzazione
        app(ValidateApprovalPermissionAction::class)->execute($approver, $workHour);
        
        // Aggiorna stato
        $workHour->update([
            'status' => WorkHourStatusEnum::APPROVED,
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);
        
        // Log audit
        app(LogWorkHourApprovalAction::class)->execute($workHour, $approver);
        
        // Notifica dipendente
        app(NotifyEmployeeOfApprovalAction::class)->execute($workHour);
        
        event(new WorkHourApproved($workHour, $approver));
        
        return $workHour;
    }
}

class RejectTimeEntryAction
{
    use QueueableAction;

    public function execute(WorkHour $workHour, User $rejector, string $reason): WorkHour
    {
        app(ValidateRejectionPermissionAction::class)->execute($rejector, $workHour);
        
        $workHour->update([
            'status' => WorkHourStatusEnum::REJECTED,
            'rejected_by' => $rejector->id,
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);
        
        app(LogWorkHourRejectionAction::class)->execute($workHour, $rejector, $reason);
        app(NotifyEmployeeOfRejectionAction::class)->execute($workHour, $reason);
        
        event(new WorkHourRejected($workHour, $rejector, $reason));
        
        return $workHour;
    }
}
```

## 📊 Analytics Actions

### Attendance Analytics

```php
class CalculateAttendanceRateAction
{
    use QueueableAction;

    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate): float
    {
        $workDays = app(GetWorkDaysCountAction::class)->execute($startDate, $endDate);
        
        $attendedDays = WorkHour::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('type', WorkHourTypeEnum::CLOCK_IN)
            ->distinct(DB::raw('DATE(timestamp)'))
            ->count();
            
        return $workDays > 0 ? round(($attendedDays / $workDays) * 100, 2) : 0;
    }
}

class CalculatePunctualityScoreAction
{
    use QueueableAction;

    public function execute(Employee $employee, Carbon $startDate, Carbon $endDate): float
    {
        $clockIns = WorkHour::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('type', WorkHourTypeEnum::CLOCK_IN)
            ->get();
            
        if ($clockIns->isEmpty()) {
            return 0;
        }
        
        $onTimeCount = $clockIns->filter(function($clockIn) {
            return $clockIn->timestamp->hour <= 9; // Puntuale se entro le 9:00
        })->count();
        
        return round(($onTimeCount / $clockIns->count()) * 100, 2);
    }
}

class GenerateDailyAttendanceReportAction
{
    use QueueableAction;

    public function execute(Department $department, Carbon $date): DailyAttendanceReport
    {
        $employees = $department->allEmployees();
        $attendanceData = [];
        
        foreach ($employees as $employee) {
            $summary = app(ProcessCompleteDayAction::class)->execute($employee->id, $date);
            
            $attendanceData[] = [
                'employee' => $employee,
                'summary' => $summary,
                'metrics' => app(CalculateEmployeeMetricsAction::class)->execute($employee, $date, $date),
            ];
        }
        
        return new DailyAttendanceReport(
            department: $department,
            date: $date,
            attendanceData: $attendanceData,
            generatedAt: now()
        );
    }
}
```

## 🚀 Performance Optimization Actions

### Caching Actions

```php
class GetCachedDailyStatsAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): EmployeeDailyStats
    {
        $cacheKey = "daily_stats:{$employeeId}:{$date->format('Y-m-d')}";
        
        return Cache::remember($cacheKey, now()->addHours(1), function() use ($employeeId, $date) {
            return new EmployeeDailyStats(
                totalHours: app(CalculateDailyHoursAction::class)->execute($employeeId, $date),
                breakDuration: app(CalculateBreakDurationAction::class)->execute($employeeId, $date),
                firstEntry: app(GetFirstEntryAction::class)->execute($employeeId, $date),
                lastEntry: app(GetLastEntryAction::class)->execute($employeeId, $date),
                sessionCount: app(GetSessionCountAction::class)->execute($employeeId, $date)
            );
        });
    }
}

class BulkCalculateStatsAction
{
    use QueueableAction;

    public function execute(Collection $employees, Carbon $startDate, Carbon $endDate): Collection
    {
        return $employees->map(function($employee) use ($startDate, $endDate) {
            return app(CalculateEmployeeMetricsAction::class)
                ->execute($employee, $startDate, $endDate);
        });
    }
}
```

## 🔧 Widget Integration

### TimeClockWidget Actions

```php
// Nel widget, uso delle Actions
class TimeClockWidget extends XotBaseWidget
{
    public function clockIn(): void
    {
        try {
            $userId = Auth::id();
            
            app(ClockInAction::class)->execute(
                employeeId: (int) $userId,
                timestamp: Carbon::now()
            );
            
            $this->updateData();
            $this->notifySuccess('Entrata registrata alle ' . Carbon::now()->format('H:i'));
            
        } catch (InvalidTimeSequenceException $e) {
            $this->notifyWarning($e->getMessage());
        } catch (Exception $e) {
            $this->notifyError('Errore durante la timbratura: ' . $e->getMessage());
        }
    }
    
    public function clockOut(): void
    {
        try {
            $userId = Auth::id();
            
            app(ClockOutAction::class)->execute(
                employeeId: (int) $userId,
                timestamp: Carbon::now()
            );
            
            $this->updateData();
            $this->notifySuccess('Uscita registrata alle ' . Carbon::now()->format('H:i'));
            
        } catch (NoActiveSessionException $e) {
            $this->notifyWarning('Devi prima timbrare l\'entrata');
        } catch (Exception $e) {
            $this->notifyError('Errore durante la timbratura: ' . $e->getMessage());
        }
    }
    
    private function updateData(): void
    {
        $userId = Auth::id();
        if (!$userId) {
            return;
        }
        
        $clockData = app(GetTimeClockDataAction::class)->execute((int) $userId);
        
        $this->currentTime = $clockData->currentTime;
        $this->todayDate = $clockData->todayDate;
        $this->todayEntries = $clockData->todayEntries;
        $this->sessions = $clockData->sessions;
        $this->isClockedIn = $clockData->isClockedIn;
        $this->sessionStatus = $clockData->sessionStatus;
    }
}
```

## 🎯 Action Best Practices

### 1. Single Responsibility
```php
// ✅ CORRETTO - Una responsabilità specifica
class ClockInAction
{
    use QueueableAction;
    
    public function execute(int $employeeId, Carbon $timestamp): WorkHour
    {
        // Solo logica per clock in
    }
}

// ❌ ERRATO - Troppe responsabilità
class TimeTrackingAction
{
    public function execute(string $action, int $employeeId): mixed
    {
        return match($action) {
            'clock_in' => $this->clockIn($employeeId),
            'clock_out' => $this->clockOut($employeeId),
            // ...
        };
    }
}
```

### 2. Data Objects Integration
```php
class CreateTimeEntryAction
{
    use QueueableAction;

    // ✅ CORRETTO - Usa Data Objects
    public function execute(TimeEntryData $data): WorkHour
    {
        return WorkHour::create($data->toArray());
    }
    
    // ❌ ERRATO - Array non tipizzati
    public function execute(array $data): WorkHour
    {
        return WorkHour::create($data);
    }
}
```

### 3. Error Handling
```php
class ProcessTimeEntryAction
{
    use QueueableAction;

    public function execute(TimeEntryData $data): WorkHour
    {
        try {
            // Validazioni multiple tramite Actions
            app(ValidateTimeSequenceAction::class)->execute($data->employeeId, $data->timestamp, $data->type);
            app(ValidateWorkingHoursAction::class)->execute($data->timestamp);
            app(ValidateNoDuplicateEntryAction::class)->execute($data->employeeId, $data->timestamp, $data->type);
            
            return WorkHour::create($data->toArray());
            
        } catch (BusinessRuleException $e) {
            Log::warning('Business rule violation in time entry', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Unexpected error in time entry creation', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new TimeEntryCreationException('Failed to create time entry', 0, $e);
        }
    }
}
```

---

*Documento creato: 2025-01-06*  
*Pattern: Spatie QueueableActions*  
*Compliance: Laraxot conventions*