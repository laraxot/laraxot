# TimeOffBalanceWidget (LE MIE RIMANENZE DI SETTEMBRE) - Complete Documentation

## 📋 Widget Overview

The **TimeOffBalanceWidget** represents the "LE MIE RIMANENZE DI SETTEMBRE" (My September Balances) section in the dashboard. It displays monthly and annual leave balances including vacation days, ROL, permits, overtime bank, and other time-off categories with visual progress bars and detailed statistics.

### Dashboard Position  
- **Location**: Bottom left widget area
- **Title**: "LE MIE RIMANENZE DI SETTEMBRE" 
- **Column Span**: Full width
- **Sort Priority**: 3 (third in widget order)

## 🎯 Functionality

### Core Purpose
The TimeOffBalanceWidget serves as a comprehensive time-off balance tracker for:
- **Vacation Management**: Annual vacation day allocation and usage
- **ROL Tracking**: Recupero Ore Lavoro (Work Hour Recovery) balance  
- **Permit Monitoring**: Ex-festivities and general permit hours
- **Overtime Banking**: Accumulated overtime hours available
- **Balance Analysis**: Visual progress indicators and remaining allocations

### Key Features
- **Multiple Balance Types**: Support for various Italian labor law categories
- **Visual Progress Bars**: Graphical representation of usage vs. allocation  
- **Negative Balance Handling**: Clear indication of overdrawn accounts
- **Hour/Minute Formatting**: Precise time display (e.g., "8h 53m")
- **Color-Coded Categories**: Different colors for each balance type
- **Real-time Calculations**: Dynamic updates based on usage patterns

## 🏗️ Technical Implementation

### Widget Class Structure

**File**: `/var/www/html/_bases/base_techplanner_fila3_mono/laravel/Modules/Employee/app/Filament/Widgets/TimeOffBalanceWidget.php`

```php
class TimeOffBalanceWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.time-off-balance-widget';
    protected int|string|array $columnSpan = 'full';  
    protected static ?int $sort = 3;
}
```

### Core Methods

#### `getTimeOffBalances(): array`
Returns balance data for all time-off categories:

```php
[
    'id' => 1,
    'type' => 'vacation',
    'label' => 'Ferie',
    'current_balance' => 8.88,    // 8h 53m remaining
    'total_allowance' => 22.0,    // Total annual allocation
    'used' => 13.12,              // Hours already used
    'unit' => 'hours',            // Measurement unit
    'color' => 'blue',            // Visual color theme
    'icon' => 'heroicon-o-sun',   // Display icon
]
```

#### Time Formatting Methods

**`formatHoursMinutes(float $hours): string`**
Converts decimal hours to readable format:
- `8.88` → `"8h 53m"`
- `0.0` → `"0"`  
- `-2.53` → `"-2h 32m"`
- `12.25` → `"12h 15m"`

#### Progress Calculation

**`getProgressPercentage(float $used, ?float $total): float`**
Calculates usage percentage for progress bars:
- Returns 0-100 percentage
- Handles unlimited categories (null total)
- Prevents overflow beyond 100%

#### Color Management  

**`getColorClasses(string $color, float $balance): array`**
Returns comprehensive color scheme for balance display:
- Automatically switches to red theme for negative balances
- Provides background, border, text, and progress bar colors
- Supports 5 color themes: blue, green, red, purple, yellow

## 📊 Data Structure

### Balance Item Schema
```php
[
    'id' => int,                    // Unique balance identifier
    'type' => string,               // vacation|rol|permits_ex_fs|overtime_bank|permits
    'label' => string,              // Localized display name
    'current_balance' => float,     // Remaining hours (can be negative)
    'total_allowance' => ?float,    // Total annual allocation (null for unlimited)
    'used' => float,                // Hours already consumed
    'unit' => string,               // Always 'hours' for time tracking
    'color' => string,              // Theme color: blue|green|red|purple|yellow
    'icon' => string,               // Heroicon identifier
]
```

### Balance Types Supported

1. **Vacation** (`vacation`)
   - Annual vacation day allocation
   - Blue color theme (`bg-blue-50`)
   - Sun icon (`heroicon-o-sun`)
   - Typical allocation: 22 days/year

2. **ROL** (`rol`)  
   - Recupero Ore Lavoro (Work Hour Recovery)
   - Green color theme (`bg-green-50`)
   - Clock icon (`heroicon-o-clock`)
   - Typical allocation: 8 hours/year

3. **Ex-Festivities Permits** (`permits_ex_fs`)
   - Former holiday compensation hours
   - Red color theme (`bg-red-50`) 
   - Document icon (`heroicon-o-document-text`)
   - Typical allocation: 4 hours/year

4. **Overtime Bank** (`overtime_bank`)
   - Accumulated overtime hours
   - Purple color theme (`bg-purple-50`)
   - Banknotes icon (`heroicon-o-banknotes`)
   - No limit (accumulates over time)

5. **General Permits** (`permits`)
   - General purpose permit hours
   - Yellow color theme (`bg-yellow-50`)
   - Hand raised icon (`heroicon-o-hand-raised`)
   - Typical allocation: 8 hours/year

### Mock Data Examples
Current implementation includes realistic Italian workplace scenarios:

1. **Vacation Balance**
   - Remaining: 8h 53m out of 22 days allocated
   - Used: 13h 12m already consumed
   - Status: Positive balance, 40% used

2. **ROL Balance**  
   - Remaining: 0h (fully consumed)
   - Allocation: 8 hours total
   - Status: Fully utilized

3. **Ex-Festivities Permits**
   - Remaining: -2h 32m (overdrawn)
   - Used: 6h 53m against 4h allocation
   - Status: Negative balance warning

4. **Overtime Bank**
   - Accumulated: 12h 15m available
   - No limit on accumulation
   - Status: Healthy balance for future use

5. **General Permits**
   - Remaining: 3h 45m out of 8h allocated
   - Used: 4h 15m already consumed
   - Status: Moderate usage level

## 🎨 Visual Design System

### Color Theme Strategy

| Balance Type | Color | Background | Border | Progress | Use Case |
|-------------|-------|------------|---------|----------|----------|
| **Vacation** | `text-blue-900` | `bg-blue-50` | `border-blue-200` | `bg-blue-500` | Annual leave |
| **ROL** | `text-green-900` | `bg-green-50` | `border-green-200` | `bg-green-500` | Work recovery |
| **Ex-FS Permits** | `text-red-900` | `bg-red-50` | `border-red-200` | `bg-red-500` | Holiday comp |
| **Overtime Bank** | `text-purple-900` | `bg-purple-50` | `border-purple-200` | `bg-purple-500` | OT accumulation |
| **Permits** | `text-yellow-900` | `bg-yellow-50` | `border-yellow-200` | `bg-yellow-500` | General use |

### Negative Balance Handling
When `current_balance < 0`, all balances automatically switch to red theme:
- Background: `bg-red-50`  
- Border: `border-red-200`
- Text: `text-red-900`
- Progress: `bg-red-500`
- Visual warning: Clear negative indication

### Progress Bar Design
- **Background**: Light theme color (`bg-blue-100`)
- **Fill**: Primary theme color (`bg-blue-500`)
- **Width**: Proportional to usage percentage  
- **Height**: Consistent 4px height
- **Border Radius**: Rounded corners for modern appearance

## 📱 User Interface Design

### Balance Card Layout
```
┌─────────────────────────────────────────┐
│      LE MIE RIMANENZE DI SETTEMBRE      │
├─────────────────────────────────────────┤
│ 🌅 Ferie                    8h 53m      │
│ ████████░░░░░░░░ 60%                    │ 
│ Used: 13h 12m • Total: 22h             │
├─────────────────────────────────────────┤ 
│ 🕐 ROL                         0        │
│ ████████████████ 100%                   │
│ Used: 8h • Total: 8h                   │
├─────────────────────────────────────────┤
│ 📄 Perm. ex-fs              -2h 32m     │
│ ████████████████ 163%                   │
│ Used: 6h 53m • Total: 4h               │
├─────────────────────────────────────────┤
│ 💰 Banca ore                12h 15m     │
│ ░░░░░░░░░░░░░░░░ No limit               │
│ Accumulated overtime hours              │
└─────────────────────────────────────────┘
```

### Visual Hierarchy
1. **Category Icon**: Visual identifier for balance type
2. **Category Label**: Localized Italian name  
3. **Current Balance**: Prominent remaining hours display
4. **Progress Bar**: Visual usage indicator
5. **Usage Statistics**: Detailed used/total breakdown
6. **Status Indicators**: Color-coded health assessment

### Responsive Behavior
- **Desktop**: Multi-column card layout
- **Tablet**: Stacked card arrangement
- **Mobile**: Single column with optimized touch targets
- **Print**: Simplified text-only layout

## 🔧 Implementation Guidelines  

### Database Integration

For production use, replace mock data with real balance calculations:

```php
protected function getTimeOffBalances(): array
{
    $employeeId = auth()->id();
    $currentYear = now()->year;
    
    return TimeOffBalance::where('employee_id', $employeeId)
        ->where('year', $currentYear)
        ->with(['timeOffType'])
        ->get()
        ->map(function ($balance) {
            return [
                'id' => $balance->id,
                'type' => $balance->timeOffType->code,
                'label' => $balance->timeOffType->name,
                'current_balance' => $balance->remaining_hours,
                'total_allowance' => $balance->allocated_hours,
                'used' => $balance->used_hours,
                'unit' => 'hours',
                'color' => $this->getBalanceColor($balance->timeOffType->code),
                'icon' => $this->getBalanceIcon($balance->timeOffType->code),
            ];
        })
        ->toArray();
}
```

### Custom Balance Types

Add new time-off categories:

```php
protected function getBalanceIcon(string $type): string
{
    return match ($type) {
        'vacation' => 'heroicon-o-sun',
        'rol' => 'heroicon-o-clock',  
        'permits_ex_fs' => 'heroicon-o-document-text',
        'overtime_bank' => 'heroicon-o-banknotes',
        'permits' => 'heroicon-o-hand-raised',
        'sick_leave' => 'heroicon-o-heart',
        'maternity' => 'heroicon-o-user-group',
        'study_leave' => 'heroicon-o-academic-cap',
        'union_hours' => 'heroicon-o-users',
        default => 'heroicon-o-calendar',
    };
}

protected function getBalanceColor(string $type): string  
{
    return match ($type) {
        'vacation' => 'blue',
        'rol' => 'green',
        'permits_ex_fs' => 'red', 
        'overtime_bank' => 'purple',
        'permits' => 'yellow',
        'sick_leave' => 'red',
        'maternity' => 'pink',
        'study_leave' => 'indigo',
        'union_hours' => 'teal',
        default => 'gray',
    };
}
```

### Advanced Time Formatting

Enhanced time display with localization:

```php
protected function formatHoursMinutes(float $hours, string $locale = 'it'): string
{
    if ($hours == 0) {
        return '0';
    }
    
    $isNegative = $hours < 0;
    $absHours = abs($hours);
    $wholeHours = floor($absHours);
    $minutes = round(($absHours - $wholeHours) * 60);
    
    if ($minutes == 60) {
        $wholeHours++;
        $minutes = 0;
    }
    
    $parts = [];
    if ($wholeHours > 0) {
        $parts[] = $wholeHours . ($locale === 'it' ? 'h' : 'hrs');
    }
    if ($minutes > 0) {
        $parts[] = $minutes . ($locale === 'it' ? 'm' : 'min');
    }
    
    $formatted = implode(' ', $parts);
    return ($isNegative ? '-' : '') . $formatted;
}
```

## 📊 Performance Considerations

### Efficient Balance Calculations
```php
protected function getTimeOffBalances(): array
{
    // Single query with joins for optimal performance
    return DB::table('time_off_balances')
        ->join('time_off_types', 'time_off_balances.type_id', '=', 'time_off_types.id')
        ->select([
            'time_off_balances.id',
            'time_off_types.code as type',
            'time_off_types.name as label',
            'time_off_balances.remaining_hours as current_balance',
            'time_off_balances.allocated_hours as total_allowance',
            'time_off_balances.used_hours as used',
            'time_off_types.color',
            'time_off_types.icon'
        ])
        ->where('time_off_balances.employee_id', auth()->id())
        ->where('time_off_balances.year', now()->year)
        ->orderBy('time_off_types.sort_order')
        ->get()
        ->toArray();
}
```

### Caching Strategy
```php
protected function getTimeOffBalances(): array
{
    $cacheKey = 'time_off_balances_' . auth()->id() . '_' . now()->year;
    
    return cache()->remember($cacheKey, now()->addHours(4), function () {
        return $this->calculateTimeOffBalances();
    });
}

// Invalidate cache when balances change
public function updateBalance(int $balanceId, float $hoursUsed): void
{
    // Update database...
    
    // Clear cache
    $cacheKey = 'time_off_balances_' . auth()->id() . '_' . now()->year;
    cache()->forget($cacheKey);
}
```

### Memory Optimization
- Use select() to fetch only needed columns
- Implement lazy loading for balance history
- Cache formatted strings to avoid re-calculation
- Use database views for complex balance calculations

## 🧪 Testing Scenarios

### Functional Testing
- [ ] Balance calculations are accurate
- [ ] Progress percentages display correctly  
- [ ] Negative balances show red styling
- [ ] Hour formatting handles edge cases
- [ ] Icons display for all balance types
- [ ] Color themes apply consistently
- [ ] Unlimited balances (overtime) handle properly

### Mathematical Testing
- [ ] Decimal hour conversion (8.88 → 8h 53m)
- [ ] Negative hour formatting (-2.53 → -2h 32m)
- [ ] Progress calculation (13.12/22 = 59.6%)
- [ ] Zero balance display (0.0 → "0")
- [ ] Minute rounding (59 minutes stays as minutes)
- [ ] Hour overflow (60 minutes → 1 hour)

### Visual Testing
- [ ] Progress bars fill proportionally
- [ ] Negative balances use red theme
- [ ] Color accessibility (WCAG AA compliance)
- [ ] Mobile responsive layout
- [ ] Icon visibility and alignment
- [ ] Text overflow handling for long labels

### Edge Case Testing
- [ ] Null total allowance (overtime bank)
- [ ] Extremely high usage percentages (>200%)
- [ ] Very small balances (0.01 hours)
- [ ] Very large balances (999+ hours)
- [ ] Missing balance data
- [ ] Invalid color/icon configurations

## 🔄 Integration Points

### Related Models  
- **TimeOffBalance**: Core balance entity with usage tracking
- **TimeOffType**: Balance category definitions and configuration
- **Employee**: Balance ownership and permissions
- **TimeOffRequest**: Balance usage and deductions
- **WorkHour**: Integration with time tracking system

### Data Dependencies
- Employee authentication and authorization
- Annual balance allocation rules
- Time-off request approval system
- Labor law compliance calculations  
- Payroll integration for balance adjustments

### External Services
- Payroll system integration
- HR information system synchronization  
- Government reporting compliance
- Mobile app balance notifications
- Calendar integration for planned time off

## 🚀 Future Enhancement Opportunities

### Advanced Features
1. **Balance Forecasting**: Predict future balance levels
2. **Usage Analytics**: Historical trend analysis  
3. **Smart Recommendations**: Optimal time-off scheduling
4. **Multi-Year View**: Historical balance progression
5. **Team Comparisons**: Department-wide balance insights

### Automation Features
1. **Auto-Allocation**: Annual balance assignment
2. **Rollover Rules**: Year-end balance transfers
3. **Expiration Warnings**: Use-or-lose notifications
4. **Approval Integration**: Automatic balance deductions
5. **Compliance Monitoring**: Labor law adherence checks

### User Experience Improvements
1. **Interactive Charts**: Clickable balance history
2. **Export Options**: PDF/Excel balance reports
3. **Mobile Optimization**: Touch-friendly controls
4. **Accessibility**: Screen reader optimization
5. **Personalization**: Custom balance display preferences

## ⚡ Performance Metrics

### Target Benchmarks  
- **Load Time**: < 250ms for balance display
- **Calculation Speed**: < 50ms for balance updates
- **Memory Usage**: < 5MB for widget data
- **Database Queries**: < 3 queries per render
- **Cache Hit Rate**: > 90% for repeat views

### Success Indicators
- **Balance Accuracy**: 100% calculation precision  
- **User Engagement**: > 85% monthly widget usage
- **Performance**: < 300ms average response time
- **Error Rate**: < 0.1% calculation errors
- **User Satisfaction**: > 90% positive feedback

---

**Last Updated**: January 2025  
**Status**: Production Ready  
**Widget Class**: `TimeOffBalanceWidget`  
**View Template**: `employee::filament.widgets.time-off-balance-widget`  
**Dependencies**: Filament 3.x, XotBaseWidget, TimeOffBalance Model