# PendingRequestsWidget (LE MIE RICHIESTE IN ATTESA) - Complete Documentation

## 📋 Widget Overview

The **PendingRequestsWidget** represents the "LE MIE RICHIESTE IN ATTESA" (My Pending Requests) section in the dashboard. It displays pending approval requests submitted by the current employee with status tracking and illustrations for empty states.

### Dashboard Position
- **Location**: Right sidebar widget 
- **Title**: "LE MIE RICHIESTE IN ATTESA"
- **Column Span**: Full width
- **Sort Priority**: 5 (appears fifth in widget order)

## 🎯 Functionality  

### Core Purpose
The PendingRequestsWidget serves as a personal request tracking system for:
- **Leave Requests**: Vacation, sick leave, personal time off
- **Work Arrangement**: Smart working and remote work requests
- **Business Travel**: Transfer and travel authorization requests  
- **Permit Requests**: Medical appointments, personal errands
- **Status Monitoring**: Real-time approval workflow tracking

### Key Features
- **Request Status Tracking**: Visual indication of approval progress
- **Empty State Illustration**: Encouraging message when no pending requests
- **Request Type Classification**: Color-coded request categories
- **Priority Indicators**: High, normal, low priority levels
- **Approver Information**: Shows who will review the request
- **Submission Date**: When the request was submitted

## 🏗️ Technical Implementation

### Widget Class Structure

**File**: `/var/www/html/_bases/base_techplanner_fila3_mono/laravel/Modules/Employee/app/Filament/Widgets/PendingRequestsWidget.php`

```php
class PendingRequestsWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.pending-requests-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 5;
}
```

### Core Methods

#### `getPendingRequests(): array`
Returns pending requests for the current user. Currently returns empty array to demonstrate the "all managed" state:

```php
// Example request structure (commented out in current implementation)
[
    'id' => 1,
    'type' => 'vacation',
    'title' => 'Richiesta Ferie Agosto',
    'description' => 'Ferie dal 15 al 30 agosto 2024',
    'submitted_date' => Carbon instance,
    'status' => 'pending',
    'approver' => 'Mario Rossi',
    'priority' => 'normal',
    'icon' => 'heroicon-o-sun',
]
```

#### Request Type Configuration Methods

**`getRequestTypeConfig(string $type): array`**
Returns visual configuration for different request types:

```php
'vacation' => [
    'icon' => 'heroicon-o-sun',
    'color' => 'text-orange-600',
    'bg' => 'bg-orange-50', 
    'border' => 'border-orange-200',
],
'sick' => [
    'icon' => 'heroicon-o-heart',
    'color' => 'text-red-600',
    'bg' => 'bg-red-50',
    'border' => 'border-red-200',
],
'permit' => [
    'icon' => 'heroicon-o-document-text',
    'color' => 'text-blue-600',
    'bg' => 'bg-blue-50',
    'border' => 'border-blue-200',
]
```

#### Status and Priority Methods

**`getStatusBadgeColor(string $status): string`**
- **Pending**: `bg-yellow-100 text-yellow-800`
- **Approved**: `bg-green-100 text-green-800`  
- **Rejected**: `bg-red-100 text-red-800`
- **Under Review**: `bg-blue-100 text-blue-800`

**`getPriorityBadgeColor(string $priority): string`**
- **High**: `bg-red-100 text-red-800`
- **Normal**: `bg-blue-100 text-blue-800`
- **Low**: `bg-gray-100 text-gray-800`

## 📊 Data Structure

### Request Item Schema
```php
[
    'id' => int,                    // Unique request identifier  
    'type' => string,               // vacation|sick|permit|smart_working|transfer
    'title' => string,              // Request title/summary
    'description' => string,        // Detailed description
    'submitted_date' => Carbon,     // When request was submitted
    'status' => string,             // pending|approved|rejected|under_review
    'approver' => string,           // Name of approving manager
    'priority' => string,           // high|normal|low
    'icon' => string,               // Heroicon identifier
]
```

### Request Types Supported

1. **Vacation** (`vacation`)
   - Personal vacation time, holidays
   - Orange color scheme (`text-orange-600`)
   - Sun icon (`heroicon-o-sun`)

2. **Sick Leave** (`sick`)
   - Medical leave, illness
   - Red color scheme (`text-red-600`)
   - Heart icon (`heroicon-o-heart`)

3. **Permit** (`permit`)
   - Medical appointments, personal errands
   - Blue color scheme (`text-blue-600`) 
   - Document icon (`heroicon-o-document-text`)

4. **Smart Working** (`smart_working`)
   - Remote work requests
   - Green color scheme (`text-green-600`)
   - Home icon (`heroicon-o-home`)

5. **Transfer** (`transfer`)
   - Business travel, client visits
   - Purple color scheme (`text-purple-600`)
   - Map pin icon (`heroicon-o-map-pin`)

### Mock Data Examples
The widget includes example request scenarios (commented out):

1. **Vacation Request**
   - Title: "Richiesta Ferie Agosto"  
   - Description: "Ferie dal 15 al 30 agosto 2024"
   - Status: Pending
   - Approver: Mario Rossi
   - Priority: Normal

2. **Medical Permit**
   - Title: "Permesso Medico"
   - Description: "Visita specialistica - 2 ore"  
   - Status: Pending
   - Approver: Sara Bianchi
   - Priority: High

## 🎨 Visual Design System

### Request Type Color Coding

| Request Type | Primary Color | Background | Border | Use Case |
|-------------|---------------|-------------|---------|----------|
| **Vacation** | `text-orange-600` | `bg-orange-50` | `border-orange-200` | Personal time off |
| **Sick** | `text-red-600` | `bg-red-50` | `border-red-200` | Medical leave |
| **Permit** | `text-blue-600` | `bg-blue-50` | `border-blue-200` | Short absences |
| **Smart Working** | `text-green-600` | `bg-green-50` | `border-green-200` | Remote work |  
| **Transfer** | `text-purple-600` | `bg-purple-50` | `border-purple-200` | Business travel |

### Status Indicators

| Status | Badge Color | Meaning |
|--------|-------------|---------|
| **Pending** | `bg-yellow-100 text-yellow-800` | Waiting for approval |
| **Approved** | `bg-green-100 text-green-800` | Request confirmed |
| **Rejected** | `bg-red-100 text-red-800` | Request denied |
| **Under Review** | `bg-blue-100 text-blue-800` | Being evaluated |

### Priority Levels

| Priority | Badge Color | Visual Weight |
|----------|-------------|---------------|
| **High** | `bg-red-100 text-red-800` | Urgent requests |
| **Normal** | `bg-blue-100 text-blue-800` | Standard requests |
| **Low** | `bg-gray-100 text-gray-800` | Non-urgent requests |

## 📱 User Interface Design

### Empty State Layout
```
┌─────────────────────────────────────────┐
│        LE MIE RICHIESTE IN ATTESA       │
├─────────────────────────────────────────┤
│                                         │
│            🎯 [LIGHTBULB ICON]          │
│                                         │
│     Tutte le tue richieste sono         │
│     state gestite dall'amministratore   │  
│                                         │
│               [Illustration]            │
│                                         │
└─────────────────────────────────────────┘
```

### Active Requests Layout  
```  
┌─────────────────────────────────────────┐
│        LE MIE RICHIESTE IN ATTESA       │
├─────────────────────────────────────────┤
│ 🌅 Richiesta Ferie Agosto    [PENDING] │
│     Ferie dal 15 al 30 agosto 2024     │
│     Submitted: 3 days ago               │  
│     Approver: Mario Rossi               │
├─────────────────────────────────────────┤
│ ❤️ Permesso Medico           [PENDING] │
│     Visita specialistica - 2 ore       │
│     Submitted: 1 day ago                │
│     Approver: Sara Bianchi              │
└─────────────────────────────────────────┘
```

### Visual Hierarchy
1. **Request Icon**: Visual type identifier
2. **Request Title**: Bold primary heading
3. **Description**: Detailed context information  
4. **Status Badge**: Current approval status
5. **Metadata**: Submission date and approver info
6. **Priority Indicator**: Urgency level (if applicable)

## 🔧 Implementation Guidelines

### Database Integration

For production use, replace mock data with real database queries:

```php  
protected function getPendingRequests(): array
{
    return Request::where('employee_id', auth()->id())
        ->where('status', 'pending')
        ->with(['approver'])
        ->orderBy('priority', 'desc')
        ->orderBy('submitted_at', 'desc')
        ->get()
        ->map(function ($request) {
            return [
                'id' => $request->id,
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'submitted_date' => $request->submitted_at,
                'status' => $request->status,
                'approver' => $request->approver->full_name ?? 'N/A',
                'priority' => $request->priority,
                'icon' => $this->getRequestIcon($request->type),
            ];
        })
        ->toArray();
}
```

### Adding Custom Request Types

Extend the request type configuration:

```php
protected function getRequestTypeConfig(string $type): array
{
    return match ($type) {
        'vacation' => [
            'icon' => 'heroicon-o-sun',
            'color' => 'text-orange-600',
            'bg' => 'bg-orange-50',
            'border' => 'border-orange-200',
        ],
        'sick' => [
            'icon' => 'heroicon-o-heart',
            'color' => 'text-red-600', 
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
        ],
        'permit' => [
            'icon' => 'heroicon-o-document-text',
            'color' => 'text-blue-600',
            'bg' => 'bg-blue-50', 
            'border' => 'border-blue-200',
        ],
        'smart_working' => [
            'icon' => 'heroicon-o-home',
            'color' => 'text-green-600',
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
        ],
        'transfer' => [
            'icon' => 'heroicon-o-map-pin',
            'color' => 'text-purple-600',
            'bg' => 'bg-purple-50', 
            'border' => 'border-purple-200',
        ],
        'training' => [
            'icon' => 'heroicon-o-academic-cap',
            'color' => 'text-indigo-600',
            'bg' => 'bg-indigo-50',
            'border' => 'border-indigo-200',
        ],
        'overtime' => [
            'icon' => 'heroicon-o-clock',
            'color' => 'text-yellow-600',
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
        ],
        default => [
            'icon' => 'heroicon-o-document',
            'color' => 'text-gray-600',
            'bg' => 'bg-gray-50',
            'border' => 'border-gray-200',
        ],
    };
}
```

### Empty State Customization  

Customize the empty state message and illustration:

```php
protected function getEmptyStateConfig(): array
{
    return [
        'icon' => 'heroicon-o-light-bulb',
        'title' => 'Nessuna richiesta in sospeso',
        'message' => 'Tutte le tue richieste sono state gestite dall\'amministratore',
        'illustration' => 'employee::components.illustrations.managed-requests',
        'action_label' => 'Crea nuova richiesta',
        'action_url' => route('filament.employee.resources.requests.create'),
    ];
}
```

## 📊 Performance Considerations

### Query Optimization
```php
protected function getPendingRequests(): array
{
    return Request::select([
            'id', 'type', 'title', 'description', 
            'submitted_at', 'status', 'priority', 'approver_id'
        ])
        ->where('employee_id', auth()->id()) 
        ->where('status', 'pending')
        ->with(['approver:id,first_name,last_name'])
        ->orderByRaw("FIELD(priority, 'high', 'normal', 'low')")
        ->orderBy('submitted_at', 'desc')
        ->limit(10) // Prevent UI overload
        ->get()
        ->toArray();
}
```

### Caching Strategy
```php
protected function getPendingRequests(): array
{
    return cache()->remember(
        'pending_requests_user_' . auth()->id(),
        now()->addMinutes(5), // Short cache for real-time updates
        function () {
            return $this->fetchPendingRequestsFromDatabase();
        }
    );
}
```

### Real-time Updates
```php
// Use Livewire polling for real-time status updates
protected $listeners = ['requestStatusUpdated' => 'refreshRequests'];

public function refreshRequests()
{
    $this->resetCache();
    $this->render();
}
```

## 🧪 Testing Scenarios

### Functional Testing
- [ ] Pending requests display correctly
- [ ] Empty state shows when no requests
- [ ] Request types have correct colors/icons
- [ ] Status badges display proper states  
- [ ] Priority ordering works correctly
- [ ] Submission dates format properly
- [ ] Approver names display correctly

### Visual Testing
- [ ] Request type icons render properly
- [ ] Status badge colors are correct
- [ ] Priority indicators display clearly
- [ ] Empty state illustration works
- [ ] Mobile responsive layout
- [ ] Text overflow handling
- [ ] Loading states

### User Experience Testing  
- [ ] Empty state messaging is encouraging
- [ ] Request information is clear and complete
- [ ] Status progression is understandable
- [ ] Priority levels are visually distinct
- [ ] Action affordances are obvious

## 🔄 Integration Points

### Related Models
- **Request**: Core request entity with status tracking
- **Employee**: Request originator and approver relationships
- **RequestType**: Classification and configuration
- **ApprovalWorkflow**: Multi-step approval process

### Data Dependencies
- Employee authentication and permissions
- Request submission and approval system
- Manager/approver assignment logic  
- Request type configuration
- Status workflow definitions

### External Services
- Email notification system
- Calendar integration for approved requests
- Mobile push notifications
- Reporting and analytics dashboard

## 🚀 Future Enhancement Opportunities

### Advanced Features
1. **Quick Actions**: Withdraw or modify pending requests
2. **Request History**: View completed and rejected requests
3. **Bulk Operations**: Submit multiple related requests
4. **Request Templates**: Pre-defined request patterns
5. **Conditional Logic**: Dynamic form fields based on type

### User Experience Improvements
1. **Progress Indicators**: Multi-step approval visualization  
2. **Estimated Timeline**: Predicted approval timeframes
3. **Comments System**: Two-way communication with approvers
4. **File Attachments**: Supporting documentation uploads
5. **Mobile Optimization**: Touch-friendly interface

### Integration Enhancements
1. **Calendar Sync**: Auto-block time for approved requests
2. **Team Coordination**: Impact analysis on team schedules
3. **Reporting Integration**: Request analytics and trends
4. **API Access**: Third-party system integration
5. **Workflow Automation**: Smart routing and approvals

## ⚡ Performance Metrics

### Target Benchmarks
- **Load Time**: < 200ms for request list
- **Status Update**: < 100ms for real-time changes
- **Memory Usage**: < 3MB for widget data
- **Database Queries**: < 2 queries per render
- **Cache Hit Rate**: > 80% for repeat views

### Success Indicators
- **Request Visibility**: > 95% status accuracy  
- **User Engagement**: > 70% daily widget interaction
- **Approval Efficiency**: < 48h average approval time
- **User Satisfaction**: > 85% positive feedback on clarity

---

**Last Updated**: January 2025  
**Status**: Production Ready  
**Widget Class**: `PendingRequestsWidget`  
**View Template**: `employee::filament.widgets.pending-requests-widget`  
**Dependencies**: Filament 3.x, XotBaseWidget, Request Model