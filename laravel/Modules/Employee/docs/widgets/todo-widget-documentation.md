# TodoWidget (COSE DA FARE) - Complete Documentation

## 📋 Widget Overview

The **TodoWidget** represents the "COSE DA FARE" (Things To Do) section in the dashboard. It displays HR-related tasks that need to be completed by the employee, featuring priority-based styling and direct action links.

### Dashboard Position
- **Location**: Left sidebar widget
- **Title**: "COSE DA FARE"
- **Column Span**: Full width
- **Sort Priority**: 1 (appears first)

## 🎯 Functionality

### Core Purpose
The TodoWidget serves as a centralized task management system for HR-related activities including:
- **Payroll Tasks**: Review payslips and salary documents
- **Profile Management**: Update employee information
- **Leave Management**: Confirm vacation dates and requests
- **Training**: Complete mandatory safety courses
- **Compliance**: Handle various HR compliance tasks

### Key Features
- **Priority Classification**: High/Medium/Low priority levels with color coding
- **Due Date Tracking**: Visual indication of task urgency
- **Direct Actions**: Links to complete tasks immediately
- **Icon Integration**: Visual icons for different task types
- **Responsive Design**: Mobile-friendly interface

## 🏗️ Technical Implementation

### Widget Class Structure

**File**: `/var/www/html/_bases/base_workorder_fila3_mono/laravel/Modules/Employee/app/Filament/Widgets/TodoWidget.php`

```php
class TodoWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.todo-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;
}
```

### Core Methods

#### `getTodoItems(): array`
Returns an array of todo items with complete task information:

```php
[
    'id' => 1,
    'title' => 'Una busta paga da leggere',
    'description' => 'Controlla la busta paga di dicembre 2024',
    'priority' => 'high',
    'icon' => 'heroicon-o-document-text',
    'action_url' => '#',
    'due_date' => Carbon instance,
]
```

#### Priority System Methods

**`getPriorityColor(string $priority): string`**
- Returns CSS classes for priority-based background, text, and border colors
- High: Red styling (`text-red-600 bg-red-50 border-red-200`)
- Medium: Yellow styling (`text-yellow-600 bg-yellow-50 border-yellow-200`)
- Low: Green styling (`text-green-600 bg-green-50 border-green-200`)

**`getPriorityBadgeColor(string $priority): string`**
- Returns badge-specific color classes
- High: `bg-red-100 text-red-800`
- Medium: `bg-yellow-100 text-yellow-800`
- Low: `bg-green-100 text-green-800`

## 📊 Data Structure

### Task Item Schema
```php
[
    'id' => int,                    // Unique identifier
    'title' => string,              // Task title (shown prominently)
    'description' => string,        // Detailed description
    'priority' => 'high|medium|low', // Priority level
    'icon' => string,              // Heroicon identifier
    'action_url' => string,        // Direct link to complete task
    'due_date' => Carbon,          // Task deadline
]
```

### Mock Data Examples
The widget currently uses mock data representing typical HR tasks:

1. **Payroll Review** (High Priority)
   - Title: "Una busta paga da leggere"
   - Icon: `heroicon-o-document-text`
   - Due: 2 days

2. **Profile Update** (Medium Priority)
   - Title: "Aggiorna profilo dipendente"
   - Icon: `heroicon-o-user`
   - Due: 1 week

3. **Vacation Confirmation** (Low Priority)
   - Title: "Conferma ferie estive"
   - Icon: `heroicon-o-calendar-days`
   - Due: 2 weeks

4. **Safety Training** (High Priority)
   - Title: "Formazione obbligatoria sicurezza"
   - Icon: `heroicon-o-shield-check`
   - Due: 5 days

## 🎨 Visual Design System

### Priority Color Coding

| Priority | Background | Text Color | Border | Badge |
|----------|------------|------------|---------|-------|
| **High** | `bg-red-50` | `text-red-600` | `border-red-200` | `bg-red-100 text-red-800` |
| **Medium** | `bg-yellow-50` | `text-yellow-600` | `border-yellow-200` | `bg-yellow-100 text-yellow-800` |
| **Low** | `bg-green-50` | `text-green-600` | `border-green-200` | `bg-green-100 text-green-800` |

### Icon Categories
- **Documents**: `heroicon-o-document-text` (Payroll, contracts)
- **Profile**: `heroicon-o-user` (Personal information)
- **Calendar**: `heroicon-o-calendar-days` (Time off, scheduling)
- **Security**: `heroicon-o-shield-check` (Training, compliance)

## 📱 User Interface Design

### Layout Structure
```
┌─────────────────────────────────┐
│         COSE DA FARE            │
├─────────────────────────────────┤
│ [→] Una busta paga da leggere   │ <- High priority item
│     Description text here       │
│     Due: 2 days                │
├─────────────────────────────────┤
│ [→] Task 2 title               │ <- Medium priority item
│     Description text here       │
│     Due: 1 week                │
└─────────────────────────────────┘
```

### Visual Hierarchy
1. **Task Title**: Bold, prominent text with arrow indicator
2. **Description**: Smaller gray text providing context
3. **Due Date**: Color-coded urgency indicator
4. **Priority Badge**: Color-coded priority level
5. **Action Arrow**: Visual cue for clickability

## 🔧 Implementation Guidelines

### Adding New Task Types

To add new task categories, extend the icon mapping:

```php
protected function getTaskIcon(string $type): string
{
    return match ($type) {
        'payroll' => 'heroicon-o-banknotes',
        'training' => 'heroicon-o-academic-cap',
        'benefits' => 'heroicon-o-heart',
        'compliance' => 'heroicon-o-shield-check',
        default => 'heroicon-o-document',
    };
}
```

### Database Integration

For production use, replace mock data with database queries:

```php
protected function getTodoItems(): array
{
    return TodoItem::where('employee_id', auth()->id())
        ->where('status', 'pending')
        ->orderBy('priority', 'desc')
        ->orderBy('due_date', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'priority' => $item->priority,
                'icon' => $this->getTaskIcon($item->type),
                'action_url' => $item->action_url,
                'due_date' => $item->due_date,
            ];
        })
        ->toArray();
}
```

### Custom Styling

Override priority colors by extending the methods:

```php
protected function getPriorityColor(string $priority): string
{
    return match ($priority) {
        'urgent' => 'text-red-800 bg-red-100 border-red-300',
        'high' => 'text-red-600 bg-red-50 border-red-200',
        'medium' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
        'low' => 'text-green-600 bg-green-50 border-green-200',
        default => 'text-gray-600 bg-gray-50 border-gray-200',
    };
}
```

## 📊 Performance Considerations

### Optimization Strategies
1. **Lazy Loading**: Load task details only when needed
2. **Caching**: Cache task lists for inactive users
3. **Pagination**: Limit visible tasks to prevent UI overflow
4. **Real-time Updates**: Use polling for urgent task notifications

### Query Optimization
```php
// Efficient database queries
protected function getTodoItems(): array
{
    return TodoItem::select(['id', 'title', 'description', 'priority', 'due_date', 'type'])
        ->where('employee_id', auth()->id())
        ->where('completed_at', null)
        ->limit(10) // Prevent overwhelming UI
        ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
        ->orderBy('due_date', 'asc')
        ->get()
        ->toArray();
}
```

## 🧪 Testing Scenarios

### Functional Testing
- [ ] Tasks display in priority order
- [ ] Due dates calculate correctly
- [ ] Action links navigate properly
- [ ] Priority colors apply correctly
- [ ] Empty states handle gracefully

### Visual Testing
- [ ] Mobile responsiveness
- [ ] Color contrast accessibility
- [ ] Icon visibility
- [ ] Text overflow handling
- [ ] Loading states

### User Experience Testing
- [ ] Task completion flows
- [ ] Priority understanding
- [ ] Navigation efficiency
- [ ] Action affordance clarity

## 🔄 Integration Points

### Related Components
- **Employee Model**: User identification and permissions
- **Task System**: Backend task management
- **Notification System**: Task reminders and alerts
- **Dashboard Layout**: Widget positioning and sizing

### Data Dependencies
- Employee authentication state
- Task database records
- Permission and role system
- Due date calculations

## 🚀 Future Enhancement Opportunities

### Advanced Features
1. **Drag & Drop Priority**: Reorder tasks manually
2. **Task Categories**: Group by department or type
3. **Progress Tracking**: Show completion percentage
4. **Collaborative Tasks**: Team-based todo items
5. **Smart Scheduling**: AI-suggested task ordering

### Integration Possibilities
1. **Calendar Integration**: Sync with calendar apps
2. **Email Notifications**: Automated reminders
3. **Mobile App**: Push notifications
4. **Reporting**: Task completion analytics
5. **Workflow Engine**: Automated task creation

## ⚡ Performance Metrics

### Target Benchmarks
- **Load Time**: < 200ms for task list
- **Interaction Response**: < 100ms for clicks
- **Memory Usage**: < 5MB for widget data
- **Database Queries**: < 3 queries per render

### Success Indicators
- **Task Completion Rate**: > 85%
- **User Engagement**: > 90% daily active usage
- **Priority Accuracy**: < 5% priority changes
- **Navigation Efficiency**: < 2 clicks to complete task

---

**Last Updated**: January 2025
**Status**: Production Ready
**Widget Class**: `TodoWidget`
**View Template**: `employee::filament.widgets.todo-widget`
**Dependencies**: Filament 3.x, XotBaseWidget
