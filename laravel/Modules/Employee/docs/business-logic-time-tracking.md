# Business Logic - Time Tracking System

## Panoramica

Il sistema di Time Tracking implementa una state machine complessa per gestire le timbrature dei dipendenti, garantendo integrità dei dati e business rules corrette.

## State Machine Architecture

### 2.1 WorkHour State Machine

```php
enum WorkHourTypeEnum: string 
{
    case CLOCK_IN = 'clock_in';         // Entrata
    case CLOCK_OUT = 'clock_out';       // Uscita  
    case BREAK_START = 'break_start';   // Inizio pausa
    case BREAK_END = 'break_end';       // Fine pausa
}

enum WorkHourStatusEnum: string 
{
    case PENDING = 'pending';           // In attesa di approvazione
    case APPROVED = 'approved';         // Approvata
    case REJECTED = 'rejected';         // Rifiutata
    case CANCELLED = 'cancelled';       // Cancellata
}
```

### 2.2 Valid Time Entry Sequences

**Sequenze Valide:**
```
1. CLOCK_IN → CLOCK_OUT (Giornata semplice)
2. CLOCK_IN → BREAK_START → BREAK_END → CLOCK_OUT (Con pausa)
3. CLOCK_IN → BREAK_START → BREAK_END → BREAK_START → BREAK_END → CLOCK_OUT (Multiple pause)
4. CLOCK_IN → CLOCK_OUT → CLOCK_IN → CLOCK_OUT (Multiple sessioni)
```

**Transizioni Non Permesse:**
```
❌ CLOCK_OUT → BREAK_START (Non puoi iniziare pausa se non sei in sessione)
❌ BREAK_END → BREAK_END (Non puoi finire due pause consecutive)
❌ CLOCK_IN → CLOCK_IN (Non puoi entrare due volte consecutive)
```

## Core Business Logic Implementation

### 2.3 Next Action Calculation

```php
public static function getNextAction(int $employeeId, Carbon $timestamp): WorkHourTypeEnum
{
    $lastEntry = self::getLastEntryForEmployee($employeeId, $timestamp);
    
    if (!$lastEntry) {
        return WorkHourTypeEnum::CLOCK_IN; // Prima timbratura del giorno
    }
    
    return match($lastEntry->type) {
        WorkHourTypeEnum::CLOCK_IN => WorkHourTypeEnum::CLOCK_OUT,
        WorkHourTypeEnum::BREAK_START => WorkHourTypeEnum::BREAK_END,
        WorkHourTypeEnum::BREAK_END => WorkHourTypeEnum::CLOCK_OUT,
        WorkHourTypeEnum::CLOCK_OUT => WorkHourTypeEnum::CLOCK_IN, // Nuova sessione
    };
}

private static function getLastEntryForEmployee(int $employeeId, Carbon $timestamp): ?WorkHour
{
    return self::query()
        ->where('employee_id', $employeeId)
        ->whereDate('timestamp', $timestamp->toDateString())
        ->orderBy('timestamp', 'desc')
        ->first();
}
```

### 2.4 Validation Engine

#### Duplicate Prevention Logic
```php
private function validateDuplicateEntry(array $data): void
{
    $employeeId = (int) $data['employee_id'];
    $timestamp = Carbon::parse($data['timestamp']);
    
    $existingEntry = WorkHour::query()
        ->where('employee_id', $employeeId)
        ->where('timestamp', $timestamp)
        ->where('type', $data['type'])
        ->first();

    if ($existingEntry) {
        throw new DuplicateTimeEntryException(
            "Entry already exists for {$timestamp->format('Y-m-d H:i')} with type {$data['type']}"
        );
    }
}
```

#### Working Hours Validation
```php
private function validateWorkingHours(Carbon $timestamp): void
{
    // Orari consentiti: 06:00 - 22:00
    if ($timestamp->hour < 6 || $timestamp->hour > 22) {
        throw new InvalidWorkingHoursException(
            "Time entry at {$timestamp->format('H:i')} is outside working hours (06:00-22:00)"
        );
    }
}
```

#### Sequence Validation
```php
private function validateSequence(array $data): void
{
    $employeeId = (int) $data['employee_id'];
    $timestamp = Carbon::parse($data['timestamp']);
    $requestedType = WorkHourTypeEnum::from($data['type']);
    
    $expectedAction = WorkHour::getNextAction($employeeId, $timestamp);
    
    if ($requestedType !== $expectedAction) {
        $lastEntry = WorkHour::getLastEntryForEmployee($employeeId, $timestamp);
        $lastType = $lastEntry ? $lastEntry->type->value : 'none';
        
        throw new InvalidTimeSequenceException(
            "Invalid sequence. Last entry: {$lastType}, expected: {$expectedAction->value}, got: {$requestedType->value}"
        );
    }
}
```

## Session Management Logic

### 2.5 Session Construction

```php
class TimeClockWidget 
{
    /**
     * Costruisce le sessioni del giorno accoppiando entrate/uscite.
     *
     * @param Collection<int, WorkHour> $entries
     */
    private function buildSessions(Collection $entries): void
    {
        $sessions = [];
        $currentSession = null;
        
        foreach ($entries as $entry) {
            switch ($entry->type) {
                case WorkHourTypeEnum::CLOCK_IN:
                    // Chiudi sessione precedente se aperta
                    if ($currentSession && !isset($currentSession['out'])) {
                        $sessions[] = $currentSession;
                    }
                    
                    // Inizia nuova sessione
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
        
        // Aggiungi sessione corrente se ancora aperta
        if ($currentSession) {
            $sessions[] = $currentSession;
        }
        
        $this->sessions = $sessions;
    }
}
```

### 2.6 Time Calculation Logic

```php
class WorkHourCalculator
{
    public static function calculateDailyHours(int $employeeId, Carbon $date): float
    {
        $entries = WorkHour::query()
            ->where('employee_id', $employeeId)
            ->whereDate('timestamp', $date)
            ->orderBy('timestamp')
            ->get();
            
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
                        // Conta tempo fino all'inizio pausa
                        $totalMinutes += $sessionStart->diffInMinutes($entry->timestamp);
                        $breakStart = $entry->timestamp;
                    }
                    break;
                    
                case WorkHourTypeEnum::BREAK_END:
                    if ($breakStart) {
                        // Riprendi conteggio dalla fine pausa
                        $sessionStart = $entry->timestamp;
                        $breakStart = null;
                    }
                    break;
            }
        }
        
        return round($totalMinutes / 60, 2); // Ore con 2 decimali
    }
    
    public static function calculateBreakDuration(int $employeeId, Carbon $date): int
    {
        $entries = WorkHour::query()
            ->where('employee_id', $employeeId)
            ->whereDate('timestamp', $date)
            ->whereIn('type', [WorkHourTypeEnum::BREAK_START, WorkHourTypeEnum::BREAK_END])
            ->orderBy('timestamp')
            ->get();
            
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

## Widget Real-Time Logic

### 2.7 TimeClockWidget Core Logic

```php
class TimeClockWidget extends XotBaseWidget
{
    // Polling ogni secondo per aggiornamenti real-time
    protected static ?string $pollingInterval = '1s';
    
    public function updateData(): void
    {
        // Aggiorna ora e data
        $this->currentTime = Carbon::now()->format('H:i');
        $this->todayDate = Carbon::now()->locale('it')->isoFormat('dddd D MMMM YYYY');
        
        $userId = Auth::id();
        if ($userId === null) {
            $this->resetData();
            return;
        }
        
        $this->loadTodayEntries((string) $userId);
        $this->updateSessionStatus();
    }
    
    private function loadTodayEntries(string $userId): void
    {
        $entries = WorkHour::query()
            ->where('employee_id', $userId)
            ->whereDate('timestamp', today())
            ->orderBy('timestamp', 'asc')
            ->get();
            
        // Costruisci array per display
        $this->todayEntries = $entries->map(function (WorkHour $entry): array {
            return [
                'time' => $entry->timestamp->format('H:i'),
                'type' => $entry->type->value,
            ];
        })->values()->all();
        
        // Costruisci sessioni per analytics
        $this->buildSessions($entries);
    }
    
    public function clockIn(): void
    {
        if ($this->isClockedIn) {
            $this->notifyWarning('Sei già in sessione');
            return;
        }
        
        $this->createTimeEntry(WorkHourTypeEnum::CLOCK_IN, 'Entrata registrata');
    }
    
    public function clockOut(): void
    {
        if (!$this->isClockedIn) {
            $this->notifyWarning('Devi prima timbrare l\'entrata');
            return;
        }
        
        $this->createTimeEntry(WorkHourTypeEnum::CLOCK_OUT, 'Uscita registrata');
    }
    
    private function createTimeEntry(WorkHourTypeEnum $type, string $successMessage): void
    {
        $userId = Auth::id();
        
        try {
            WorkHour::query()->create([
                'employee_id' => $userId,
                'type' => $type,
                'timestamp' => Carbon::now(),
                'status' => WorkHourStatusEnum::PENDING,
                'notes' => $successMessage . ' da dashboard widget',
            ]);
            
            $this->updateData();
            $this->notifySuccess($successMessage . ' alle ' . Carbon::now()->format('H:i'));
            
        } catch (Exception $e) {
            $this->notifyError('Errore durante la timbratura: ' . $e->getMessage());
        }
    }
}
```

## Analytics & Reporting Logic

### 2.8 Attendance Analytics

```php
class AttendanceOverviewWidget
{
    protected function getStats(): array
    {
        $today = today();
        
        return [
            'present_today' => $this->getPresentTodayCount(),
            'still_working' => $this->getStillWorkingCount(), 
            'total_hours_today' => $this->getTotalHoursToday(),
            'average_start_time' => $this->getAverageStartTime(),
            'late_arrivals' => $this->getLateArrivalsCount(),
        ];
    }
    
    private function getPresentTodayCount(): int
    {
        return WorkHour::query()
            ->whereDate('timestamp', today())
            ->where('type', WorkHourTypeEnum::CLOCK_IN)
            ->distinct('employee_id')
            ->count('employee_id');
    }
    
    private function getStillWorkingCount(): int
    {
        // Trova dipendenti con ultima timbratura CLOCK_IN o BREAK_END
        $employees = User::query()
            ->whereHas('workHours', function($query) {
                $query->whereDate('timestamp', today());
            })
            ->get();
            
        $stillWorking = 0;
        
        foreach ($employees as $employee) {
            $lastEntry = WorkHour::query()
                ->where('employee_id', $employee->id)
                ->whereDate('timestamp', today())
                ->latest('timestamp')
                ->first();
                
            if ($lastEntry && in_array($lastEntry->type, [
                WorkHourTypeEnum::CLOCK_IN,
                WorkHourTypeEnum::BREAK_END
            ])) {
                $stillWorking++;
            }
        }
        
        return $stillWorking;
    }
    
    private function getTotalHoursToday(): float
    {
        $employees = User::query()
            ->whereHas('workHours', function($query) {
                $query->whereDate('timestamp', today());
            })
            ->get();
            
        $totalHours = 0;
        
        foreach ($employees as $employee) {
            $totalHours += WorkHourCalculator::calculateDailyHours($employee->id, today());
        }
        
        return round($totalHours, 2);
    }
}
```

## Performance Optimizations

### 2.9 Caching Strategy

```php
class GetDailyStatsAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): array
    {
        $cacheKey = "work_hour_stats:{$employeeId}:{$date->format('Y-m-d')}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function() use ($employeeId, $date) {
            return [
                'total_hours' => app(CalculateDailyHoursAction::class)->execute($employeeId, $date),
                'break_duration' => app(CalculateBreakDurationAction::class)->execute($employeeId, $date),
                'first_entry' => app(GetFirstEntryAction::class)->execute($employeeId, $date),
                'last_entry' => app(GetLastEntryAction::class)->execute($employeeId, $date),
                'session_count' => app(GetSessionCountAction::class)->execute($employeeId, $date),
            ];
        });
    }
}

class InvalidateCacheAction
{
    use QueueableAction;

    public function execute(int $employeeId, Carbon $date): void
    {
        $cacheKey = "work_hour_stats:{$employeeId}:{$date->format('Y-m-d')}";
        Cache::forget($cacheKey);
    }
}
```

### 2.10 Database Optimizations

```php
// Indici ottimizzati per query frequenti
Schema::table('work_hours', function (Blueprint $table) {
    $table->index(['employee_id', 'timestamp']); // Per query giornaliere
    $table->index(['timestamp', 'type']);        // Per analytics
    $table->index(['employee_id', 'type', 'timestamp']); // Query composite
});

// Query ottimizzate con eager loading
$todayEntries = WorkHour::query()
    ->with(['employee:id,name,email'])
    ->whereDate('timestamp', today())
    ->orderBy('timestamp')
    ->get();
```

## Error Handling & Edge Cases

### 2.11 Business Exception Handling

```php
class TimeTrackingExceptionHandler
{
    public function handleTimeEntryCreation(array $data): WorkHour
    {
        try {
            // Validazioni business
            $this->validateSequence($data);
            $this->validateWorkingHours(Carbon::parse($data['timestamp']));
            $this->validateDuplicateEntry($data);
            
            return WorkHour::create($data);
            
        } catch (InvalidTimeSequenceException $e) {
            // Log per analytics
            Log::warning('Invalid time sequence attempted', [
                'employee_id' => $data['employee_id'],
                'type' => $data['type'],
                'timestamp' => $data['timestamp'],
                'error' => $e->getMessage()
            ]);
            throw $e;
            
        } catch (DuplicateTimeEntryException $e) {
            // Potenziale attacco o errore UI
            Log::notice('Duplicate time entry attempted', [
                'employee_id' => $data['employee_id'],
                'data' => $data
            ]);
            throw $e;
        }
    }
}
```

---

*Documento creato: 2025-01-06*  
*Versione: 1.0*  
*Stato: Completo*
