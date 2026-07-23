# WorkHours Page

## Overview
The WorkHours page allows employees to track their working hours, including clock-in/out times, breaks, and work sessions. It provides a visual timeline of the workday and includes features for managing work hours.

## Features

### 1. Time Tracking
- **Clock In/Out**: Employees can record their start and end times
- **Break Management**: Track break times during the workday
- **Real-time Updates**: Current status and elapsed time are displayed in real-time

### 2. Work Sessions
- **Session History**: View past work sessions with details
- **Session Editing**: Modify recorded times if needed (with proper permissions)
- **Status Indicators**: Visual indicators for different session statuses (working, on break, etc.)

### 3. Visual Timeline
- **Day View**: Hourly breakdown of the workday
- **Color-coded Events**: Different colors for work hours, breaks, and other events
- **Interactive Elements**: Hover for details, click to edit

## Data Structure

### WorkHour Model
```php
[
    'id' => 'int',
    'employee_id' => 'int',
    'timestamp' => 'datetime',
    'type' => 'string ["clock_in", "clock_out", "break_start", "break_end"]',
    'status' => 'string ["pending", "approved", "rejected"]',
    'notes' => 'string|null',
    'location' => 'string|null',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
]
```

## Permissions

| Action | Permission | Description |
|--------|------------|-------------|
| View WorkHours | `view_work_hours` | View own work hours |
| Create WorkHours | `create_work_hours` | Record new work hours |
| Update WorkHours | `update_work_hours` | Edit existing work hours |
| Delete WorkHours | `delete_work_hours` | Remove work hours |
| Approve WorkHours | `approve_work_hours` | Approve/reject work hours (managers) |

## Related Components

### Widgets
- `TimeClockWidget`: Main widget for clocking in/out
- `TodayPresenceWidget`: Shows today's work hours summary
- `AttendanceOverviewWidget`: Monthly attendance statistics

### Actions
- `ClockInAction`: Handles clock-in logic
- `ClockOutAction`: Handles clock-out logic
- `StartBreakAction`: Records break start times
- `EndBreakAction`: Records break end times

## API Endpoints

### GET /api/work-hours
List work hours with optional filters

### POST /api/work-hours/clock-in
Record clock-in time

### POST /api/work-hours/clock-out
Record clock-out time

## Localization
All user-facing strings are localized using the `employee::work_hours` translation namespace.

## Error Handling
The page includes comprehensive error handling for:
- Duplicate clock-in/out attempts
- Missing required fields
- Permission issues
- Invalid time entries

## Mobile Support
The page is fully responsive and works on mobile devices with touch-friendly controls.

## Audit Logging
All changes to work hours are logged for compliance and auditing purposes.

## Related Documentation
- [Employee Module Overview](../modules/employee.md)
- [Widgets Documentation](../widgets/README.md)
- [API Documentation](../../api/README.md)
