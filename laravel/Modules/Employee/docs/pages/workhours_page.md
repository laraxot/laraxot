# WorkHoursPage - Timbrature Implementation

## 📋 Page Overview

**WorkHoursPage** replicates the functionality of `https://secure.dipendentincloud.it/it/app/timestamps/list` - the main time tracking interface for employees.

## 🎯 Page Objectives

### 1. **Exact Replica of dipendentincloud.it Interface**
- Same layout and visual design
- Identical functionality and user experience
- Matching data presentation format

### 2. **Core Time Tracking Features**
- Weekly time entry overview
- Real-time clock display
- Work hours calculation
- Export functionality
- Date range navigation

## 🏗️ Page Architecture

### Class Structure
```php
class WorkHoursPage extends XotBasePage
{
    protected static string $view = 'employee::filament.pages.work-hours';
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $title = 'Timbrature';
    protected static ?string $navigationLabel = 'Timbrature';
    
    public Carbon $startDate;
    public Carbon $endDate;
    public bool $showToleranceThreshold = false;
}
```

### Data Flow Architecture
```
User Request → WorkHoursPage → Actions → Database → View Rendering
      ↑               ↓               ↓           ↓
   Livewire      Queueable       Eloquent     Blade Template
   Components     Actions         Models     with Tailwind CSS
```

## 🔧 Technical Implementation

### 1. **Page Initialization**
```php
public function mount(): void
{
    // Default alla settimana corrente come dipendentincloud.it
    $this->startDate = Carbon::now()->startOfWeek();
    $this->endDate = Carbon::now()->endOfWeek();
}
```

### 2. **Data Retrieval via Actions**
```php
protected function getViewData(): array
{
    $userId = (int) (Auth::id() ?? 0);
    
    return [
        'byDate' => app(BuildTimeEntriesForRangeAction::class)->execute($userId, $this->startDate, $this->endDate),
        'weekData' => app(BuildWeeklyTimeTableAction::class)->execute($userId, $this->startDate, $this->endDate),
        'currentTime' => Carbon::now()->format('H:i'),
        'todayDate' => Carbon::now()->locale('it')->isoFormat('dddd D MMMM YYYY'),
        'employee' => app(GetCurrentEmployeeDataAction::class)->execute($userId),
    ];
}
```

### 3. **Date Navigation Methods**
```php
public function previousWeek(): void
{
    $this->startDate = $this->startDate->subWeek();
    $this->endDate = $this->endDate->subWeek();
}

public function nextWeek(): void
{
    $this->startDate = $this->startDate->addWeek();
    $this->endDate = $this->endDate->addWeek();
}

public function currentWeek(): void
{
    $this->startDate = Carbon::now()->startOfWeek();
    $this->endDate = Carbon::now()->endOfWeek();
}
```

## 🎨 UI/UX Implementation

### Visual Design Requirements
- **Font**: Inter UI (same as dipendentincloud.it)
- **Colors**: Primary blue (#091e42), secondary grays
- **Layout**: Responsive grid system
- **Icons**: Heroicons matching original design

### Key UI Components

#### 1. **Header Section**
```blade
{{-- Current time and date --}}
<div class="text-center lg:text-left">
    <div class="text-4xl lg:text-5xl font-mono font-bold text-primary-600">
        {{ $currentTime }}
    </div>
    <div class="text-sm text-gray-600 font-medium">
        {{ $todayDate }}
    </div>
</div>
```

#### 2. **Status Indicators**
```blade
@switch($currentStatus)
    @case('clocked_in')
        <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
        <span>Clocked In</span>
        @break
    @case('clocked_out')
        <span class="h-2 w-2 rounded-full bg-red-500 mr-2"></span>
        <span>Clocked Out</span>
        @break
@endswitch
```

#### 3. **Weekly Time Table**
```blade
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entries</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Worked</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
        @foreach($weekData as $day)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                {{ $day['date']->format('D d/m') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                {{ $day['entries_count'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                {{ $this->formatMinutesToHours($day['worked_minutes']) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $day['status_class'] }}">
                    {{ $day['status_text'] }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
```

## 🔄 Actions Integration

### Required Queueable Actions

#### 1. **BuildTimeEntriesForRangeAction**
```php
class BuildTimeEntriesForRangeAction
{
    use QueueableAction;
    
    public function execute(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        return [
            'byDate' => $this->buildEntriesByDate($userId, $startDate, $endDate),
            'summary' => $this->calculateSummary($userId, $startDate, $endDate),
            'contracts' => $this->getContractHours($userId),
        ];
    }
}
```

#### 2. **BuildWeeklyTimeTableAction**
```php
class BuildWeeklyTimeTableAction
{
    use QueueableAction;
    
    public function execute(int $userId, Carbon $startDate, Carbon $endDate): array
    {
        $tableData = [];
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $tableData[] = $this->buildDayData($userId, $date);
        }
        
        return $tableData;
    }
}
```

#### 3. **GetCurrentEmployeeDataAction**
```php
class GetCurrentEmployeeDataAction
{
    use QueueableAction;
    
    public function execute(int $userId): array
    {
        return [
            'name' => $this->getEmployeeName($userId),
            'position' => $this->getEmployeePosition($userId),
            'department' => $this->getEmployeeDepartment($userId),
            'photo_url' => $this->getEmployeePhoto($userId),
        ];
    }
}
```

#### 4. **ExportTimeDataAction**
```php
class ExportTimeDataAction
{
    use QueueableAction;
    
    public function execute(int $userId, Carbon $startDate, Carbon $endDate, string $format): void
    {
        $data = $this->gatherExportData($userId, $startDate, $endDate);
        $filename = $this->generateFilename($userId, $startDate, $endDate, $format);
        
        $this->createExportFile($data, $filename, $format);
        $this->sendExportNotification($userId, $filename);
    }
}
```

## 📊 Data Structure

### Time Entry Data Format
```php
[
    'byDate' => [
        '2024-01-15' => [
            ['start' => '08:00', 'end' => '12:00', 'status' => 'worked'],
            ['start' => '13:00', 'end' => '17:00', 'status' => 'worked'],
        ],
        '2024-01-16' => [
            ['start' => '08:30', 'end' => null, 'status' => 'in_progress'],
        ],
    ],
    'summary' => [
        'workedMinutes' => 480,
        'addedMinutes' => 0,
        'reducedMinutes' => 0,
        'contractMinutes' => 480,
    ],
    'contracts' => [
        'standard' => 480,
        'overtime' => 0,
    ],
]
```

### Weekly Table Data Format
```php
[
    [
        'date' => Carbon('2024-01-15'),
        'entries_count' => 2,
        'worked_minutes' => 480,
        'status' => 'complete',
        'status_class' => 'bg-green-100 text-green-800',
        'status_text' => 'Complete',
    ],
    [
        'date' => Carbon('2024-01-16'),
        'entries_count' => 1,
        'worked_minutes' => 210,
        'status' => 'incomplete',
        'status_class' => 'bg-yellow-100 text-yellow-800',
        'status_text' => 'In Progress',
    ],
]
```

## 🎯 Functional Requirements

### 1. **Date Range Management**
- [x] Weekly view by default
- [x] Previous/Next week navigation
- [x] Current week reset
- [x] Custom date range support

### 2. **Time Display**
- [x] Real-time clock updates
- [x] Italian date formatting
- [x] 24-hour time format

### 3. **Data Presentation**
- [x] Daily time entries
- [x] Work hours calculation
- [x] Status indicators
- [x] Summary statistics

### 4. **Export Functionality**
- [x] Excel export
- [x] PDF export
- [x] Async processing
- [x] Notification system

### 5. **User Experience**
- [x] Responsive design
- [x] Loading states
- [x] Error handling
- [x] Accessibility compliance

## 🔧 Configuration

### Navigation Configuration
```php
protected static ?string $navigationGroup = 'Time Tracking';
protected static ?int $navigationSort = 1;
protected static ?string $navigationIcon = 'heroicon-o-clock';
```

### Page Permissions
```php
public static function canAccess(): bool
{
    return auth()->check() && auth()->user()->can('view_work_hours');
}
```

### Cache Configuration
```php
protected function getCacheKey(): string
{
    return sprintf('workhours_page_%d_%s_%s', 
        auth()->id(), 
        $this->startDate->format('Y-m-d'), 
        $this->endDate->format('Y-m-d')
    );
}

protected function getCacheDuration(): int
{
    return 300; // 5 minutes
}
```

## 🚀 Performance Optimization

### 1. **Database Optimization**
```php
// Use eager loading and proper indexing
WorkHour::with(['employee', 'approver'])
    ->where('user_id', $userId)
    ->whereBetween('timestamp', [$startDate, $endDate])
    ->orderBy('timestamp')
    ->get();
```

### 2. **Caching Strategy**
```php
// Cache weekly data to reduce database load
$cacheKey = $this->getCacheKey();
$data = Cache::remember($cacheKey, $this->getCacheDuration(), function() use ($userId, $startDate, $endDate) {
    return app(BuildTimeEntriesForRangeAction::class)->execute($userId, $startDate, $endDate);
});
```

### 3. **Queue Optimization**
```php
// Heavy operations go to queue
app(ExportTimeDataAction::class)
    ->onQueue('exports') // Specific queue for exports
    ->execute($userId, $this->startDate, $this->endDate, 'xlsx');
```

## 🧪 Testing Strategy

### Unit Tests
```php
class WorkHoursPageTest extends TestCase
{
    public function test_page_loads_with_default_week(): void
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->get(WorkHoursPage::getUrl())
            ->assertSuccessful();
    }
    
    public function test_date_navigation_works(): void
    {
        $user = User::factory()->create();
        $page = new WorkHoursPage();
        
        $page->previousWeek();
        $this->assertEquals(now()->subWeek()->startOfWeek(), $page->startDate);
        
        $page->nextWeek();
        $this->assertEquals(now()->startOfWeek(), $page->startDate);
    }
}
```

### Feature Tests
```php
class WorkHoursExportTest extends TestCase
{
    public function test_export_creates_file(): void
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)
            ->post(route('workhours.export'), [
                'start_date' => now()->startOfWeek()->format('Y-m-d'),
                'end_date' => now()->endOfWeek()->format('Y-m-d'),
                'format' => 'xlsx',
            ])
            ->assertJson(['message' => 'Export avviato']);
    }
}
```

## 🔍 Code Review Checklist

### Page Implementation
- [ ] Extends XotBasePage (not Filament Page directly)
- [ ] Uses Queueable Actions (not Services)
- [ ] Proper error handling
- [ ] Input validation
- [ ] Authorization checks

### UI/UX Quality
- [ ] Matches dipendentincloud.it design
- [ ] Responsive layout
- [ ] Accessibility compliant
- [ ] Italian language support

### Performance
- [ ] Database queries optimized
- [ ] Proper caching strategy
- [ ] Queue usage for heavy operations
- [ ] Memory usage optimized

### Testing
- [ ] Unit tests coverage
- [ ] Feature tests coverage
- [ ] Edge cases tested
- [ ] Error scenarios tested

---

**This page successfully replicates the dipendentincloud.it timestamps interface with identical functionality, improved performance, and proper Laraxot architecture using Queueable Actions instead of Services.**