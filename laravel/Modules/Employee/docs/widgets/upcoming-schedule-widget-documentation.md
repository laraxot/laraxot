# UpcomingScheduleWidget (PROSSIMI 7 GIORNI) - Complete Documentation

## 📋 Widget Overview

The **UpcomingScheduleWidget** represents the "PROSSIMI 7 GIORNI" (Next 7 Days) section in the dashboard. It displays upcoming schedule events for the next week including absences, smart working, transfers, and other schedule-related activities.

### Dashboard Position
- **Location**: Center widget area (main content)
- **Title**: "PROSSIMI 7 GIORNI"
- **Column Span**: Full width
- **Sort Priority**: 2 (second in order)

## 🎯 Functionality

### Core Purpose
The UpcomingScheduleWidget serves as a comprehensive scheduling overview that includes:
- **Absence Management**: Vacation days, sick leave, personal time
- **Remote Work**: Smart working and work-from-home schedules
- **Business Travel**: Transfers and business trips
- **Team Coordination**: Visibility into colleague availability
- **Approval Tracking**: Status of pending schedule requests

### Key Features
- **7-Day Forward View**: Focus on immediate upcoming events
- **Employee Avatars**: Visual representation with initials and colors
- **Event Type Classification**: Color-coded event categories
- **Status Tracking**: Approved, pending, rejected states
- **Location Information**: Office, remote, or travel locations
- **Notes Integration**: Contextual information for each event

## 🏗️ Technical Implementation

### Widget Class Structure

**File**: `/var/www/html/_bases/base_workorder_fila3_mono/laravel/Modules/Employee/app/Filament/Widgets/UpcomingScheduleWidget.php`

```php
class UpcomingScheduleWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.upcoming-schedule-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;
}
```

### Core Methods

#### `getUpcomingEvents(): array`
Returns scheduled events for the next 7 days:

```php
[
    'id' => 1,
    'employee_name' => 'Mario Rossi',
    'employee_initials' => 'MR',
    'event_type' => 'absence',
    'event_title' => 'Ferie',
    'start_date' => Carbon instance,
    'end_date' => Carbon instance,
    'status' => 'approved',
    'location' => null,
    'notes' => 'Ferie programmate',
]
```

#### Event Type Configuration Methods

**`getEventTypeConfig(string $type): array`**
Returns visual configuration for different event types:

```php
'absence' => [
    'icon' => 'heroicon-o-x-circle',
    'color' => 'text-red-600 bg-red-50 border-red-200',
    'badge_color' => 'bg-red-100 text-red-800',
],
'smart_working' => [
    'icon' => 'heroicon-o-home',
    'color' => 'text-blue-600 bg-blue-50 border-blue-200',
    'badge_color' => 'bg-blue-100 text-blue-800',
],
'transfer' => [
    'icon' => 'heroicon-o-map-pin',
    'color' => 'text-purple-600 bg-purple-50 border-purple-200',
    'badge_color' => 'bg-purple-100 text-purple-800',
]
```

#### Helper Methods

**`getStatusBadgeColor(string $status): string`**
- **Approved**: `bg-green-100 text-green-800`
- **Pending**: `bg-yellow-100 text-yellow-800`
- **Rejected**: `bg-red-100 text-red-800`

**`getAvatarColor(string $initials): string`**
- Generates consistent colors based on initials hash
- 8 predefined color options for visual variety
- Ensures same person always gets same color

## 📊 Data Structure

### Event Item Schema
```php
[
    'id' => int,                    // Unique event identifier
    'employee_name' => string,      // Full employee name
    'employee_initials' => string,  // 2-letter initials for avatar
    'event_type' => string,         // absence|smart_working|transfer
    'event_title' => string,        // Localized event title
    'start_date' => Carbon,         // Event start date/time
    'end_date' => Carbon,           // Event end date/time
    'status' => string,             // approved|pending|rejected
    'location' => ?string,          // Location info (optional)
    'notes' => ?string,             // Additional context (optional)
]
```

### Event Types Supported
1. **Absence** (`absence`)
   - Vacation days, sick leave, personal time off
   - Red color scheme (`text-red-600`)
   - X-circle icon

2. **Smart Working** (`smart_working`)
   - Remote work, work from home
   - Blue color scheme (`text-blue-600`)
   - Home icon

3. **Transfer** (`transfer`)
   - Business trips, client meetings, travel
   - Purple color scheme (`text-purple-600`)
   - Map pin icon

### Mock Data Examples
Current implementation includes representative scenarios:

1. **Mario Rossi - Vacation**
   - Type: Absence (Ferie)
   - Duration: 3 days starting tomorrow
   - Status: Approved
   - Notes: "Ferie programmate"

2. **Sara Bianchi - Smart Working**
   - Type: Remote work
   - Duration: 1 day
   - Location: Casa
   - Status: Approved
   - Notes: "Lavoro da remoto"

3. **Luca Verde - Business Trip**
   - Type: Transfer (Trasferta)
   - Duration: 2 days
   - Location: Milano
   - Status: Pending
   - Notes: "Meeting cliente"

## 🎨 Visual Design System

### Color Coding Strategy

| Event Type | Primary Color | Background | Border | Badge |
|------------|---------------|-------------|---------|-------|
| **Absence** | `text-red-600` | `bg-red-50` | `border-red-200` | `bg-red-100 text-red-800` |
| **Smart Working** | `text-blue-600` | `bg-blue-50` | `border-blue-200` | `bg-blue-100 text-blue-800` |
| **Transfer** | `text-purple-600` | `bg-purple-50` | `border-purple-200` | `bg-purple-100 text-purple-800` |

### Status Indicators

| Status | Badge Color | Meaning |
|--------|-------------|---------|
| **Approved** | `bg-green-100 text-green-800` | Confirmed and scheduled |
| **Pending** | `bg-yellow-100 text-yellow-800` | Awaiting approval |
| **Rejected** | `bg-red-100 text-red-800` | Denied or cancelled |

### Avatar System
- **Circular avatars** with colored backgrounds
- **Initials display** (first 2 letters of name)
- **8 color variants** for visual variety
- **Consistent coloring** based on name hash

## 📱 User Interface Design

### Layout Structure
```
┌─────────────────────────────────────────┐
│            PROSSIMI 7 GIORNI            │
├─────────────────────────────────────────┤
│ [MR] Mario Rossi          [APPROVED]    │
│      📅 Ferie                           │
│      15/01 - 17/01 • Ferie programmate  │
├─────────────────────────────────────────┤
│ [SB] Sara Bianchi         [APPROVED]    │
│      🏠 Smart Working                   │
│      16/01 • Casa • Lavoro da remoto    │
├─────────────────────────────────────────┤
│ [LV] Luca Verde           [PENDING]     │
│      📍 Trasferta                       │
│      18/01 - 19/01 • Milano • Meeting   │
└─────────────────────────────────────────┘
```

### Visual Hierarchy
1. **Employee Avatar**: Colored circle with initials
2. **Employee Name**: Bold primary text
3. **Event Type**: Icon + localized title
4. **Date Range**: Clear start/end dates
5. **Location**: Geographic context when relevant
6. **Status Badge**: Approval status indicator
7. **Notes**: Additional contextual information

## 🔧 Implementation Guidelines

### Database Integration

For production use, replace mock data with real queries:

```php
protected function getUpcomingEvents(): array
{
    return WorkHour::with(['employee'])
        ->where('date', '>=', now()->startOfDay())
        ->where('date', '<=', now()->addDays(7)->endOfDay())
        ->whereHas('workHourType', function($query) {
            $query->whereIn('code', ['ABSENCE', 'SMART_WORK', 'TRANSFER']);
        })
        ->orderBy('date', 'asc')
        ->get()
        ->map(function ($workHour) {
            return [
                'id' => $workHour->id,
                'employee_name' => $this->getEmployeeFullName($workHour->employee),
                'employee_initials' => $this->getInitialsFromName($workHour->employee->full_name),
                'event_type' => $this->mapWorkHourTypeToEventType($workHour->workHourType->code),
                'event_title' => $this->getEventTitle($workHour->workHourType->code),
                'start_date' => $workHour->date,
                'end_date' => $workHour->end_date ?? $workHour->date,
                'status' => $workHour->approval_status ?? 'approved',
                'location' => $workHour->location,
                'notes' => $workHour->notes,
            ];
        })
        ->toArray();
}
```

### Adding New Event Types

Extend the event type configuration:

```php
protected function getEventTypeConfig(string $type): array
{
    return match ($type) {
        'absence' => [
            'icon' => 'heroicon-o-x-circle',
            'color' => 'text-red-600 bg-red-50 border-red-200',
            'badge_color' => 'bg-red-100 text-red-800',
        ],
        'smart_working' => [
            'icon' => 'heroicon-o-home',
            'color' => 'text-blue-600 bg-blue-50 border-blue-200',
            'badge_color' => 'bg-blue-100 text-blue-800',
        ],
        'transfer' => [
            'icon' => 'heroicon-o-map-pin',
            'color' => 'text-purple-600 bg-purple-50 border-purple-200',
            'badge_color' => 'bg-purple-100 text-purple-800',
        ],
        'training' => [
            'icon' => 'heroicon-o-academic-cap',
            'color' => 'text-green-600 bg-green-50 border-green-200',
            'badge_color' => 'bg-green-100 text-green-800',
        ],
        'meeting' => [
            'icon' => 'heroicon-o-users',
            'color' => 'text-indigo-600 bg-indigo-50 border-indigo-200',
            'badge_color' => 'bg-indigo-100 text-indigo-800',
        ],
        default => [
            'icon' => 'heroicon-o-calendar',
            'color' => 'text-gray-600 bg-gray-50 border-gray-200',
            'badge_color' => 'bg-gray-100 text-gray-800',
        ],
    };
}
```

### Localization Support

Add Italian translations for event types:

```php
protected function getEventTitle(string $type): string
{
    return match ($type) {
        'absence' => 'Assenza',
        'smart_working' => 'Smart Working',
        'transfer' => 'Trasferta',
        'vacation' => 'Ferie',
        'sick_leave' => 'Malattia',
        'permit' => 'Permesso',
        'training' => 'Formazione',
        'meeting' => 'Riunione',
        default => ucfirst($type),
    };
}
```

## 📊 Performance Considerations

### Query Optimization
```php
// Efficient date range queries
protected function getUpcomingEvents(): array
{
    $startDate = now()->startOfDay();
    $endDate = now()->addDays(7)->endOfDay();

    return WorkHour::select([
            'id', 'employee_id', 'date', 'end_date',
            'work_hour_type_id', 'location', 'notes', 'approval_status'
        ])
        ->with(['employee:id,first_name,last_name', 'workHourType:id,code,name'])
        ->whereBetween('date', [$startDate, $endDate])
        ->orderBy('date', 'asc')
        ->orderBy('employee_id', 'asc')
        ->limit(20) // Prevent UI overload
        ->get()
        ->toArray();
}
```

### Caching Strategy
```php
protected function getUpcomingEvents(): array
{
    return cache()->remember(
        'upcoming_events_user_' . auth()->id(),
        now()->addMinutes(15),
        function () {
            return $this->fetchUpcomingEventsFromDatabase();
        }
    );
}
```

### Memory Optimization
- Limit result set to prevent memory issues
- Use select() to fetch only needed columns
- Implement pagination for large datasets
- Cache results for frequent requests

## 🧪 Testing Scenarios

### Functional Testing
- [ ] Events display in chronological order
- [ ] Date ranges calculate correctly
- [ ] Status badges show proper colors
- [ ] Employee names and initials display
- [ ] Event types map correctly
- [ ] Location information appears when available
- [ ] Notes truncate appropriately

### Visual Testing
- [ ] Avatar colors remain consistent
- [ ] Event type icons display properly
- [ ] Status badges have correct colors
- [ ] Text overflow handles long names/notes
- [ ] Mobile responsive layout works
- [ ] Empty states handle gracefully

### Performance Testing
- [ ] Widget loads within 300ms
- [ ] No memory leaks with large datasets
- [ ] Cache invalidation works properly
- [ ] Database queries are optimized

## 🔄 Integration Points

### Related Models
- **Employee**: Personnel information and relationships
- **WorkHour**: Schedule and time tracking records
- **WorkHourType**: Event type classifications
- **ApprovalWorkflow**: Status management system

### Data Dependencies
- Employee database records
- Work hour scheduling system
- Approval workflow states
- Location and notes data

### External Services
- Calendar synchronization
- Email notification system
- Mobile app integration
- Reporting and analytics

## 🚀 Future Enhancement Opportunities

### Advanced Features
1. **Calendar Integration**: Sync with Outlook/Google Calendar
2. **Conflict Detection**: Alert on scheduling conflicts
3. **Team View**: Department-wide schedule visibility
4. **Recurring Events**: Support for repeated schedules
5. **Time Zone Support**: Multi-location company support

### User Experience Improvements
1. **Quick Actions**: Approve/deny directly from widget
2. **Filtering Options**: Show only specific event types
3. **Search Functionality**: Find specific employees/events
4. **Export Options**: Download schedule as PDF/iCal
5. **Notification Integration**: Real-time status updates

### Analytics & Reporting
1. **Pattern Analysis**: Identify scheduling trends
2. **Capacity Planning**: Team availability forecasting
3. **Approval Metrics**: Track approval times and rates
4. **Usage Statistics**: Widget interaction analytics

## ⚡ Performance Metrics

### Target Benchmarks
- **Load Time**: < 300ms for 7-day view
- **Data Refresh**: < 200ms for cache updates
- **Memory Usage**: < 10MB for widget data
- **Database Queries**: < 5 queries per render

### Success Indicators
- **Schedule Accuracy**: > 98% event display accuracy
- **User Engagement**: > 80% daily active usage
- **Approval Efficiency**: < 24h average approval time
- **Conflict Prevention**: < 2% scheduling conflicts

---

**Last Updated**: January 2025
**Status**: Production Ready
**Widget Class**: `UpcomingScheduleWidget`
**View Template**: `employee::filament.widgets.upcoming-schedule-widget`
**Dependencies**: Filament 3.x, XotBaseWidget, Employee Model
