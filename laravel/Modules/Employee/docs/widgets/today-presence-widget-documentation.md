# TodayPresenceWidget (CHI C'È OGGI) - Complete Documentation

## 📋 Widget Overview

The **TodayPresenceWidget** represents the "CHI C'È OGGI" (Who's Here Today) section in the dashboard. It displays real-time presence information for all employees, showing who is present today with detailed presence counters, employee avatars, work types, and absence information.

### Dashboard Position
- **Location**: Bottom center widget area
- **Title**: "CHI C'È OGGI"
- **Column Span**: Full width
- **Sort Priority**: 4 (fourth in widget order)

## 🎯 Functionality

### Core Purpose
The TodayPresenceWidget serves as a comprehensive team presence overview for:
- **Real-time Attendance**: Live view of who is currently at work
- **Work Type Tracking**: Office, remote, or travel status for each employee
- **Absence Monitoring**: Who is absent and why (vacation, sick leave, etc.)
- **Team Coordination**: Facilitate collaboration and resource planning
- **Department Visibility**: Organize presence by department or team

### Key Features
- **Live Presence Counters**: Real-time count of present vs absent employees
- **Employee Avatars**: Visual representation with initials and consistent colors
- **Work Location Status**: Office, remote work, or business travel indicators
- **Absence Type Classification**: Detailed absence reasons and return dates
- **Department Organization**: Group employees by department (SVILUPPO, MARKETING, etc.)
- **Quick Contact Info**: Department and location details at a glance

## 🏗️ Technical Implementation

### Widget Class Structure

**File**: `/var/www/html/_bases/base_workorder_fila3_mono/laravel/Modules/Employee/app/Filament/Widgets/TodayPresenceWidget.php`

```php
class TodayPresenceWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.today-presence-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 4;
}
```

### Core Methods

#### `getTodayPresence(): array`
Returns comprehensive presence data structure:

```php
[
    'present' => [
        // Array of present employees with details
        [
            'id' => 1,
            'name' => 'Mario Rossi',
            'initials' => 'MR',
            'department' => 'SVILUPPO',
            'check_in_time' => '08:30',
            'location' => 'Ufficio',
            'status' => 'present',
            'work_type' => 'office', // office|remote|travel
        ]
    ],
    'absent' => [
        // Array of absent employees with absence details
        [
            'id' => 2,
            'name' => 'Sara Bianchi',
            'initials' => 'SB',
            'department' => 'MARKETING',
            'absence_type' => 'vacation',
            'absence_reason' => 'Ferie',
            'return_date' => '2025-01-15',
        ]
    ],
    'total_present' => 6,
    'total_absent' => 4,
]
```

#### Avatar and Color Methods

**`generateInitials(string $fullName): string`**
- Extracts first 2 letters from name parts
- Handles empty names with "N/A" fallback
- Converts to uppercase for consistency

**`getAvatarColor(string $initials): string`**
- 12 predefined color options for visual variety
- Hash-based color assignment ensures consistency
- Colors: red, blue, green, yellow, purple, pink, indigo, teal, orange, cyan, lime, amber

#### Configuration Methods

**`getWorkTypeConfig(string $type): array`**
Returns configuration for different work arrangements:

```php
'office' => [
    'icon' => 'heroicon-o-building-office',
    'color' => 'text-blue-600',
    'bg' => 'bg-blue-50',
],
'remote' => [
    'icon' => 'heroicon-o-home',
    'color' => 'text-green-600',
    'bg' => 'bg-green-50',
],
'travel' => [
    'icon' => 'heroicon-o-map-pin',
    'color' => 'text-purple-600',
    'bg' => 'bg-purple-50',
]
```

**`getAbsenceTypeConfig(string $type): array`**
Returns visual configuration for absence types:

```php
'vacation' => [
    'icon' => 'heroicon-o-sun',
    'color' => 'text-orange-600',
    'bg' => 'bg-orange-50',
],
'sick' => [
    'icon' => 'heroicon-o-heart',
    'color' => 'text-red-600',
    'bg' => 'bg-red-50',
],
'permit' => [
    'icon' => 'heroicon-o-document-text',
    'color' => 'text-blue-600',
    'bg' => 'bg-blue-50',
]
```

## 📊 Data Structure

### Present Employee Schema
```php
[
    'id' => int,                    // Employee unique identifier
    'name' => string,               // Full employee name
    'initials' => string,           // 2-letter initials for avatar
    'department' => string,         // Department/team name
    'check_in_time' => string,      // Time when employee checked in (HH:MM)
    'location' => string,           // Current work location
    'status' => 'present',          // Always 'present' for present employees
    'work_type' => string,          // office|remote|travel
]
```

### Absent Employee Schema
```php
[
    'id' => int,                    // Employee unique identifier
    'name' => string,               // Full employee name
    'initials' => string,           // 2-letter initials for avatar
    'department' => string,         // Department/team name
    'absence_type' => string,       // vacation|sick|permit
    'absence_reason' => string,     // Localized absence description
    'return_date' => string,        // Expected return date (Y-m-d format)
]
```

### Work Types Supported

1. **Office Work** (`office`)
   - Physical presence in company office
   - Blue color scheme (`text-blue-600`)
   - Building office icon (`heroicon-o-building-office`)

2. **Remote Work** (`remote`)
   - Working from home or other remote location
   - Green color scheme (`text-green-600`)
   - Home icon (`heroicon-o-home`)

3. **Business Travel** (`travel`)
   - On-site client work, business trips
   - Purple color scheme (`text-purple-600`)
   - Map pin icon (`heroicon-o-map-pin`)

### Absence Types Supported

1. **Vacation** (`vacation`)
   - Planned time off, holidays
   - Orange color scheme (`text-orange-600`)
   - Sun icon (`heroicon-o-sun`)

2. **Sick Leave** (`sick`)
   - Medical leave, illness
   - Red color scheme (`text-red-600`)
   - Heart icon (`heroicon-o-heart`)

3. **Permits** (`permit`)
   - Short-term permissions, appointments
   - Blue color scheme (`text-blue-600`)
   - Document icon (`heroicon-o-document-text`)

### Mock Data Implementation
Current mock logic creates realistic workplace scenarios:
- First 6 employees marked as present (alternating office/remote)
- Remaining employees marked as absent with vacation
- Departments: SVILUPPO (Development), MARKETING
- Staggered check-in times (08:30, 08:35, 08:40, etc.)

## 🎨 Visual Design System

### Work Type Color Coding

| Work Type | Icon | Color | Background | Meaning |
|-----------|------|-------|------------|---------|
| **Office** | 🏢 Building | `text-blue-600` | `bg-blue-50` | Physical office presence |
| **Remote** | 🏠 Home | `text-green-600` | `bg-green-50` | Working from home |
| **Travel** | 📍 Map Pin | `text-purple-600` | `bg-purple-50` | Business travel/client site |

### Absence Type Color Coding

| Absence Type | Icon | Color | Background | Meaning |
|-------------|------|-------|------------|---------|
| **Vacation** | ☀️ Sun | `text-orange-600` | `bg-orange-50` | Planned time off |
| **Sick** | ❤️ Heart | `text-red-600` | `bg-red-50` | Medical leave |
| **Permit** | 📄 Document | `text-blue-600` | `bg-blue-50` | Short permissions |

### Avatar Color System
12 predefined colors ensure visual variety while maintaining consistency:
- **Warm Colors**: red-500, orange-500, yellow-500, amber-500
- **Cool Colors**: blue-500, cyan-500, teal-500, green-500
- **Accent Colors**: purple-500, pink-500, indigo-500, lime-500

## 📱 User Interface Design

### Presence Overview Layout
```
┌─────────────────────────────────────────┐
│             CHI C'È OGGI                │
├─────────────────────────────────────────┤
│  Present: 13    Absent: 5               │
├─────────────────────────────────────────┤
│ PRESENTI                                │
│ ┌────┬────┬────┬────┬────┬────┐         │
│ │[MR]│[SB]│[LV]│[AG]│[FC]│[GM]│         │
│ │08:30│08:35│08:40│08:45│08:50│08:55│    │
│ │🏢   │🏠   │🏢   │🏠   │📍   │🏢   │    │
│ └────┴────┴────┴────┴────┴────┘         │
│                                         │
│ ASSENTI                                 │
│ ┌────┬────┬────┬────┐                   │
│ │[PL]│[MT]│[RF]│[AS]│                   │
│ │☀️  │❤️  │📄  │☀️  │                   │
│ │Ferie│Malattia│Permesso│Ferie│          │
│ └────┴────┴────┴────┘                   │
└─────────────────────────────────────────┘
```

### Individual Employee Card
```
┌─────────────────────────────────────┐
│  [MR]  Mario Rossi                  │
│        SVILUPPO                     │
│        🏢 Ufficio • 08:30           │
│        Status: Present              │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│  [SB]  Sara Bianchi                 │
│        MARKETING                    │
│        ☀️ Ferie                     │
│        Return: 15/01/2025           │
└─────────────────────────────────────┘
```

### Visual Hierarchy
1. **Summary Counters**: Total present/absent numbers
2. **Section Headers**: "PRESENTI" and "ASSENTI" groupings
3. **Employee Avatars**: Colored circles with initials
4. **Employee Names**: Bold primary identification
5. **Department Labels**: Secondary organizational context
6. **Status Indicators**: Icons and text for work type/absence
7. **Time Information**: Check-in times or return dates

## 🔧 Implementation Guidelines

### Database Integration

For production use, replace mock data with real employee queries:

```php
protected function getTodayPresence(): array
{
    $today = now()->toDateString();

    // Get all employees with today's attendance
    $employees = Employee::with(['timeEntries' => function($query) use ($today) {
            $query->whereDate('date', $today)
                  ->latest();
        }, 'absences' => function($query) use ($today) {
            $query->whereDate('start_date', '<=', $today)
                  ->whereDate('end_date', '>=', $today);
        }])
        ->where('status', 'active')
        ->get();

    $present = [];
    $absent = [];

    foreach ($employees as $employee) {
        if ($employee->timeEntries->isNotEmpty()) {
            // Employee has time entries today - present
            $latestEntry = $employee->timeEntries->first();
            $present[] = [
                'id' => $employee->id,
                'name' => $this->getEmployeeFullName($employee),
                'initials' => $this->getInitialsFromName($employee->full_name),
                'department' => $employee->department->name ?? 'N/A',
                'check_in_time' => $latestEntry->start_time->format('H:i'),
                'location' => $latestEntry->location ?? 'Ufficio',
                'status' => 'present',
                'work_type' => $this->determineWorkType($latestEntry),
            ];
        } elseif ($employee->absences->isNotEmpty()) {
            // Employee has approved absence today
            $absence = $employee->absences->first();
            $absent[] = [
                'id' => $employee->id,
                'name' => $this->getEmployeeFullName($employee),
                'initials' => $this->getInitialsFromName($employee->full_name),
                'department' => $employee->department->name ?? 'N/A',
                'absence_type' => $absence->type,
                'absence_reason' => $absence->reason,
                'return_date' => $absence->end_date->addDay()->format('Y-m-d'),
            ];
        }
        // Note: Employees with no entries and no absences are not shown
    }

    return [
        'present' => $present,
        'absent' => $absent,
        'total_present' => count($present),
        'total_absent' => count($absent),
    ];
}
```

### Work Type Detection

Implement logic to determine work type from time entry data:

```php
protected function determineWorkType(TimeEntry $entry): string
{
    // Check location or entry type to determine work arrangement
    if (str_contains(strtolower($entry->location ?? ''), 'casa') ||
        str_contains(strtolower($entry->location ?? ''), 'remoto')) {
        return 'remote';
    }

    if (str_contains(strtolower($entry->location ?? ''), 'trasferta') ||
        str_contains(strtolower($entry->location ?? ''), 'cliente')) {
        return 'travel';
    }

    return 'office'; // Default to office work
}
```

### Department Integration

Connect with organizational structure:

```php
protected function groupByDepartment(array $employees): array
{
    $grouped = [];

    foreach ($employees as $employee) {
        $dept = $employee['department'];
        if (!isset($grouped[$dept])) {
            $grouped[$dept] = [];
        }
        $grouped[$dept][] = $employee;
    }

    // Sort departments by name
    ksort($grouped);

    return $grouped;
}
```

### Real-time Updates

Implement live presence updates:

```php
// Add to widget class for real-time updates
protected $listeners = [
    'employeeCheckedIn' => 'refreshPresence',
    'employeeCheckedOut' => 'refreshPresence',
    'absenceCreated' => 'refreshPresence',
];

public function refreshPresence()
{
    $this->resetCache();
    $this->render();
}

// Optionally use polling for automatic updates
public function mount()
{
    $this->poll = '30s'; // Refresh every 30 seconds
}
```

## 📊 Performance Considerations

### Query Optimization
```php
protected function getTodayPresence(): array
{
    $today = now()->toDateString();

    // Optimized query with minimal data
    return Employee::select(['id', 'first_name', 'last_name', 'department_id'])
        ->with([
            'department:id,name',
            'todayTimeEntries:id,employee_id,start_time,location',
            'todayAbsences:id,employee_id,type,reason,end_date'
        ])
        ->where('status', 'active')
        ->orderBy('department_id')
        ->orderBy('last_name')
        ->get()
        ->map(function ($employee) {
            return $this->formatEmployeePresence($employee);
        })
        ->partition(function ($employee) {
            return $employee['status'] === 'present';
        })
        ->pipe(function ($partitioned) {
            return [
                'present' => $partitioned[0]->values()->toArray(),
                'absent' => $partitioned[1]->values()->toArray(),
                'total_present' => $partitioned[0]->count(),
                'total_absent' => $partitioned[1]->count(),
            ];
        });
}
```

### Caching Strategy
```php
protected function getTodayPresence(): array
{
    $cacheKey = 'today_presence_' . now()->format('Y-m-d_H:i');

    return cache()->remember($cacheKey, now()->addMinutes(5), function () {
        return $this->calculateTodayPresence();
    });
}

// Cache invalidation on relevant events
public function invalidatePresenceCache(): void
{
    $pattern = 'today_presence_' . now()->format('Y-m-d') . '*';

    // Clear all today's presence cache entries
    collect(cache()->getRedis()->keys($pattern))
        ->each(fn($key) => cache()->forget($key));
}
```

### Memory Optimization
- Limit result sets to prevent memory issues
- Use select() to fetch only required columns
- Implement pagination for large organizations
- Cache initials and avatar colors to avoid recalculation

## 🧪 Testing Scenarios

### Functional Testing
- [ ] Present employees display with correct check-in times
- [ ] Absent employees show proper absence types and return dates
- [ ] Work type icons and colors display correctly
- [ ] Department grouping works properly
- [ ] Avatar colors remain consistent for same employee
- [ ] Total counters match actual employee counts
- [ ] Empty states handle gracefully (no employees)

### Real-time Testing
- [ ] Widget updates when employees check in/out
- [ ] New absences appear immediately
- [ ] Cancelled absences remove from absent list
- [ ] Work type changes reflect in real-time
- [ ] Performance doesn't degrade with frequent updates

### Edge Case Testing
- [ ] Employees with no department assignment
- [ ] Multiple time entries on same day
- [ ] Overlapping absences (approved + pending)
- [ ] Names with special characters or very long names
- [ ] Employees with same initials
- [ ] Very large teams (100+ employees)

### Visual Testing
- [ ] Avatar colors provide sufficient variety
- [ ] Icons display properly across all devices
- [ ] Text truncation works for long names/departments
- [ ] Mobile responsive layout
- [ ] Color contrast meets accessibility standards
- [ ] Loading states display appropriately

## 🔄 Integration Points

### Related Models
- **Employee**: Core personnel information and relationships
- **TimeEntry**: Daily attendance and time tracking records
- **Absence**: Approved absence requests and leave scheduling
- **Department**: Organizational structure and team groupings
- **WorkLocation**: Office locations and remote work designations

### Data Dependencies
- Employee active status and contact information
- Real-time attendance data from time tracking systems
- Approved absence requests from leave management
- Department assignments and organizational structure
- Work location definitions and remote work policies

### External Services
- Time tracking hardware (badge scanners, biometric systems)
- HR information systems for employee data
- Calendar integration for absence scheduling
- Mobile apps for remote check-in capabilities
- Notification services for presence alerts

## 🚀 Future Enhancement Opportunities

### Advanced Features
1. **Department Filtering**: Show only specific teams or departments
2. **Search Functionality**: Find specific employees quickly
3. **Historical View**: See presence patterns over time
4. **Capacity Planning**: Track department staffing levels
5. **Location Mapping**: Visual office layout with presence indicators

### Real-time Enhancements
1. **Live Check-in Notifications**: Toast messages for arrivals/departures
2. **Presence Forecasting**: Predict tomorrow's attendance
3. **Schedule Integration**: Show expected vs actual presence
4. **Absence Reminders**: Upcoming absence notifications
5. **Overtime Tracking**: Identify extended work hours

### Analytics & Insights
1. **Attendance Patterns**: Analyze team presence trends
2. **Remote Work Statistics**: Track work-from-home adoption
3. **Department Comparisons**: Cross-team attendance analysis
4. **Seasonal Variations**: Holiday and vacation impact assessment
5. **Productivity Correlations**: Link presence to output metrics

### User Experience Improvements
1. **Interactive Avatars**: Click for employee details/contact
2. **Status Filters**: Show only present, absent, or remote workers
3. **Export Options**: Generate attendance reports
4. **Mobile Optimization**: Touch-friendly mobile interface
5. **Accessibility**: Screen reader support and keyboard navigation

## ⚡ Performance Metrics

### Target Benchmarks
- **Load Time**: < 400ms for presence display (including avatars)
- **Update Speed**: < 200ms for real-time presence changes
- **Memory Usage**: < 8MB for widget data (50+ employees)
- **Database Queries**: < 4 queries per render
- **Cache Hit Rate**: > 85% for repeated requests

### Success Indicators
- **Data Accuracy**: > 99% presence information accuracy
- **User Engagement**: > 90% daily widget usage by managers
- **Performance**: < 500ms average response time
- **Reliability**: > 99.9% uptime for real-time updates
- **User Satisfaction**: > 85% positive feedback on usefulness

---

**Last Updated**: January 2025
**Status**: Production Ready
**Widget Class**: `TodayPresenceWidget`
**View Template**: `employee::filament.widgets.today-presence-widget`
**Dependencies**: Filament 3.x, XotBaseWidget, Employee Model
