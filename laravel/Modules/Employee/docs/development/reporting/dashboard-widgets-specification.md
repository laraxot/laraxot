# Employee Dashboard Widgets Specification

## Overview

Based on the provided interface analysis, the Employee module requires five main dashboard widgets that replicate the functionality shown in the dipendentincloud.it interface.

## Widget Requirements Analysis

### 1. TimeClock Widget
**Location**: Top-left corner
**Functionality**:
- Display current time (08:51 format)
- Show current date (lunedì 1 settembre 2025)
- Active session indicator with green dot (08:02)
- Clock out button ("Timbra uscita")

### 2. LeaveBalance Widget
**Location**: Bottom-left
**Title**: "LE MIE RIMANENZE DI SETTEMBRE"
**Functionality**:
- Monthly/Annual toggle tabs
- Leave type breakdown:
  - Ferie (Vacation): 8h 53m
  - ROL (Reduction of Working Hours): 0
  - Perm. ex-fs (Former Holidays): -2h 32m
  - Banca ore (Hour Bank): 0
  - Permessi (Permits): 0
- Color-coded bars (blue theme)
- Positive/negative hour tracking

### 3. AttendanceOverview Widget
**Location**: Center column
**Title**: "PROSSIMI 7 GIORNI"
**Functionality**:
- Department selector dropdown ("SVILUPPO")
- Tab navigation: Assenze, Smart Working, Trasferiti
- Daily attendance list showing:
  - Employee names (Filippo Beltrame, Michele Dall'Ara)
  - Absence type and time range (14:00 alle 18:00)
- Link to full presence page
- Employee avatars/icons

### 4. PendingRequests Widget
**Location**: Bottom-center
**Title**: "CHI C'È OGGI"
**Functionality**:
- Department selector dropdown
- Present/Absent counters (13 presenti, 2 assenti)
- Employee status indicators with avatars
- Color-coded presence status (green for present, red for absent)
- "Vedi dettaglio" link

### 5. TaskWidget (Additional)
**Location**: Top-center
**Title**: "COSE DA FARE"
**Functionality**:
- Task list with navigation arrow
- Integration with task management
- Quick action items

## Technical Requirements

### Base Class Compliance
All widgets MUST extend `XotBaseWidget` following Laraxot conventions:

```php
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class TimeClockWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [];
    }
}
```

### Widget Types Mapping
- **TimeClock**: Custom widget with real-time updates
- **LeaveBalance**: Stats widget with chart integration
- **AttendanceOverview**: Table widget with filtering
- **PendingRequests**: Stats widget with user avatars
- **TaskWidget**: List widget with actions

### Data Sources
- **Employee Model**: User information and relationships
- **WorkHour Model**: Time tracking and attendance data
- **Leave Model**: Vacation and leave balance tracking
- **Department Model**: Organizational structure
- **Task Model**: Task management integration

### Styling Requirements
- Consistent with Filament theme
- Responsive design for mobile/desktop
- Color coding for status indicators
- Icon integration (Heroicons)
- Italian language support

### Performance Considerations
- Caching for expensive queries (5-minute TTL)
- Lazy loading for large datasets
- Real-time updates for clock widget
- Optimized database queries with eager loading

## Implementation Priority

1. **TimeClock Widget** - Core functionality for time tracking
2. **AttendanceOverview Widget** - Essential for daily operations
3. **LeaveBalance Widget** - Important for employee self-service
4. **PendingRequests Widget** - Management oversight
5. **TaskWidget** - Additional productivity feature

## Integration Points

- **Authentication**: Current user context
- **Permissions**: Role-based widget visibility
- **Notifications**: Real-time updates
- **Localization**: Italian/English language support
- **Mobile**: Responsive design patterns

---

*Created: September 2025*
*Status: Specification Complete*
*Next: Widget Implementation*
