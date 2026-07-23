# Employee Dashboard Widgets Documentation

## Overview
Questa directory contiene le specifiche complete per tutti i widget della dashboard del modulo Employee, basati sull'analisi dell'interfaccia utente esistente.

## Widget Components

### 1. TimeClockWidget ✅ (Fully Implemented)
**File:** `TimeClockWidget.php`  
**UI:** Main time clock with clock in/out functionality  
**Position:** Top-left, sort: 0  
**Status:** ✅ Complete implementation

### 2. TodoWidget ✅ (Fully Implemented - English version of TasksWidget)
**File:** `TodoWidget.php`  
**UI:** List of pending tasks for the current user  
**Features:** Payroll tasks, general tasks, notifications  
**Sort:** 1  
**Status:** ✅ Complete implementation (uses mock data)

### 3. UpcomingScheduleWidget ✅ (Fully Implemented)
**File:** `UpcomingScheduleWidget.php`  
**UI:** Events and absences for the next 7 days with team filtering  
**Features:** Calendar events, project filters, scheduled absences  
**Sort:** 2  
**Status:** ✅ Complete implementation

### 4. PendingRequestsWidget ✅ (Fully Implemented)
**File:** `PendingRequestsWidget.php`  
**UI:** Personal request status with empty state handling  
**Features:** Vacation/leave requests, status tracking, quick actions  
**Sort:** 3  
**Status:** ✅ Complete implementation

### 5. TimeOffBalanceWidget ✅ (Fully Implemented - English version of TimeBalanceWidget)
**File:** `TimeOffBalanceWidget.php`  
**UI:** Vacation, ROL, and leave balances with progress bars  
**Features:** Monthly/yearly toggle, balance indicators, exhaustion alerts  
**Sort:** 4  
**Status:** ✅ Complete implementation

### 6. TodayPresenceWidget ✅ (Fully Implemented - English version of WhoIsInTodayWidget)
**File:** `TodayPresenceWidget.php`  
**UI:** Today's presence with counters and employee avatars  
**Features:** Team filters, presence/absence counters, avatar grid  
**Sort:** 5  
**Status:** ✅ Complete implementation

### Additional Widgets ✅
**LeaveBalanceWidget.php** - Leave balance management  
**TeamPresenceWidget.php** - Team presence tracking  
**AttendanceOverviewWidget.php** - Attendance statistics overview  
**WorkHoursBoardWidget.php** - Work hours board display  
**WorkHoursSummaryWidget.php** - Work hours summary statistics

## Implementation Strategy

### Phase 1: Foundation Models
- [ ] Employee task/request models
- [ ] Attendance tracking models  
- [ ] Balance/allowance models
- [ ] Team assignment models

### Phase 2: Core Widgets
- [ ] TasksWidget implementation
- [ ] PendingRequestsWidget implementation
- [ ] TimeBalanceWidget implementation

### Phase 3: Advanced Features
- [ ] UpcomingScheduleWidget with filtering
- [ ] WhoIsInTodayWidget with real-time updates
- [ ] Integration with existing modules

### Phase 4: Polish & Testing
- [ ] UI/UX refinements
- [ ] Performance optimizations
- [ ] Comprehensive testing
- [ ] Documentation updates

## Technical Architecture

### Base Class
All widgets extend `XotBaseWidget` following the established pattern of `TimeClockWidget`.

### Database Design
- **Tasks/Requests**: Unified or separate tables for different request types
- **Attendance**: Daily attendance status tracking
- **Balances**: Time allowance tracking with monthly/yearly breakdown
- **Teams**: Employee team assignments and filtering

### Frontend Components
- **Filament Widgets**: Native Filament widget framework
- **Blade Templates**: Custom views for each widget
- **Livewire**: Real-time updates and interactions
- **Tailwind CSS**: Consistent styling framework

### Integration Points
- **Existing TimeClockWidget**: Maintain consistency
- **User/Employee Models**: Leverage existing authentication
- **HR Module**: Connect with HR workflows
- **Notification System**: Status updates and alerts

## Styling Guidelines

### Color Scheme
- **Success/Present**: Green tones
- **Warning/Pending**: Yellow/Orange tones  
- **Danger/Absent**: Red tones
- **Info/Neutral**: Blue/Gray tones

### Layout Patterns
- **Consistent Headers**: Title + actions/filters
- **Card-based Design**: Individual items as cards
- **Progressive Disclosure**: Summary view with detail links
- **Responsive Grid**: Adaptive layout for mobile

### Accessibility
- **ARIA Labels**: Proper screen reader support
- **Keyboard Navigation**: Full keyboard accessibility
- **Color Contrast**: WCAG compliance
- **Focus Indicators**: Clear focus states

## Development Guidelines

### Code Standards
- **PHPStan Level 10**: Full type safety
- **Laravel Standards**: Follow Laravel conventions
- **Filament Patterns**: Use native Filament components
- **Documentation**: Comprehensive inline documentation

### Testing Strategy
- **Unit Tests**: Business logic and calculations
- **Feature Tests**: Widget interactions and UI
- **Integration Tests**: Database queries and relationships
- **E2E Tests**: Complete user workflows

### Performance Considerations
- **Query Optimization**: Efficient database queries
- **Caching Strategy**: Appropriate caching for each widget
- **Lazy Loading**: Load heavy data on-demand
- **Real-time Updates**: Efficient polling/WebSocket usage

## File Structure
```
Modules/Employee/
├── app/Filament/Widgets/
│   ├── TimeClockWidget.php ✅
│   ├── TasksWidget.php
│   ├── UpcomingScheduleWidget.php
│   ├── PendingRequestsWidget.php
│   ├── TimeBalanceWidget.php
│   └── WhoIsInTodayWidget.php
├── resources/views/filament/widgets/
│   ├── time-clock-widget.blade.php ✅
│   ├── tasks-widget.blade.php
│   ├── upcoming-schedule-widget.blade.php
│   ├── pending-requests-widget.blade.php
│   ├── time-balance-widget.blade.php
│   └── who-is-in-today-widget.blade.php
└── docs/widgets/
    ├── README.md (this file)
    ├── TasksWidget.md
    ├── UpcomingScheduleWidget.md
    ├── PendingRequestsWidget.md
    ├── TimeBalanceWidget.md
    └── WhoIsInTodayWidget.md
```

## Next Steps

1. **Review Documentation**: Validate each widget specification
2. **Database Design**: Create/update required tables
3. **Model Implementation**: Build supporting models
4. **Widget Development**: Implement widgets incrementally
5. **Testing & Refinement**: Comprehensive testing and UI polish

Ogni widget è documentato con:
- Overview e UI components
- Data requirements e database design
- Implementation details
- Frontend templates e styling
- Business logic e integration points
- Testing strategy e performance considerations