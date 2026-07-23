# XotBase Widget Implementation Checklist

## ✅ Pre-Implementation Checklist

Before creating a new widget, ensure you follow these steps:

### 1. 🏗️ Architecture Requirements
- [ ] **NEVER extend `Filament\Widgets\Widget` directly**
- [ ] **ALWAYS extend `Modules\Xot\Filament\Widgets\XotBaseWidget`**
- [ ] Understand the widget's purpose (form-based, display-only, interactive)

### 2. 📁 File Structure
- [ ] Place widget in correct module path: `Modules/{Module}/app/Filament/Widgets/`
- [ ] Use correct namespace: `Modules\{Module}\Filament\Widgets`
- [ ] Create corresponding Blade view in: `Modules/{Module}/resources/views/filament/widgets/`

### 3. 🔧 Mandatory Implementation
- [ ] **Implement `getFormSchema()` method** (abstract method - REQUIRED)
- [ ] Define `$view` property pointing to your Blade template
- [ ] Set appropriate `$sort` property for widget ordering

## 🚨 Critical Implementation Requirements

### ✅ REQUIRED: getFormSchema() Method
```php
/**
 * MANDATORY: All XotBase widgets must implement this
 * @return array<int|string, Component>
 */
public function getFormSchema(): array
{
    // For form-based widgets
    return [
        Section::make('Widget Title')
            ->schema([
                // Your form components here
            ]),
    ];
    
    // For display-only widgets (empty array is valid)
    return [];
}
```

### ⚠️ Common Fatal Errors to Avoid

#### ❌ Missing getFormSchema() Method
```php
class MyWidget extends XotBaseWidget // ❌ FATAL ERROR
{
    protected static string $view = 'employee::widgets.my-widget';
    
    // Missing getFormSchema() causes:
    // "Class contains 1 abstract method and must therefore be declared abstract"
}
```

#### ❌ Wrong Base Class
```php
class MyWidget extends Widget // ❌ FORBIDDEN - Direct Filament extension
{
    // Violates XotBase extension rules
}
```

## 📋 Implementation Steps Checklist

### Step 1: Create Widget Class
- [ ] Create PHP class extending `XotBaseWidget`
- [ ] Add proper namespace and imports
- [ ] Implement mandatory `getFormSchema()` method

### Step 2: Configure Widget Properties
- [ ] Set `protected static string $view` property
- [ ] Set `protected static ?int $sort` for ordering (optional)
- [ ] Configure `$columnSpan` if needed (optional)

### Step 3: Implement Business Logic
- [ ] Add `getViewData()` method if passing data to view
- [ ] Implement any Livewire methods (public methods for interactions)
- [ ] Add proper PHPDoc annotations for type safety

### Step 4: Create Blade Template
- [ ] Create Blade view file in correct location
- [ ] Use passed data from `getViewData()`
- [ ] Follow Filament/Livewire templating conventions

### Step 5: PHPStan Compliance
- [ ] Run PHPStan level 10 analysis
- [ ] Fix all type safety issues
- [ ] Handle translations properly (avoid direct casting)

## 🔍 PHPStan Level 10 Compliance Checklist

### Translation Methods
- [ ] **Avoid direct casting of translations**
```php
// ❌ Wrong - causes cast errors
return (string) __('translation.key');

// ✅ Correct - type-safe
$label = __('translation.key');
return is_string($label) ? $label : 'Default';
```

### Model Property Access
- [ ] **Use correct model properties**
```php
// WorkHour model - common mistakes:
// ❌ Wrong properties
$workHour->start_time    // Doesn't exist
$workHour->hours_worked  // Doesn't exist
$workHour->user_id       // Wrong foreign key

// ✅ Correct properties  
$workHour->timestamp     // ✅ Correct
WorkHour::calculateWorkedHours($id) // ✅ For calculations
$workHour->employee_id   // ✅ Correct foreign key
```

### Collection Type Safety
- [ ] **Add proper type annotations**
```php
/**
 * @var \Illuminate\Database\Eloquent\Collection<int, WorkHour> $workHours
 */
$workHours = WorkHour::where('employee_id', $id)->get();

$groupedByDate = $workHours->groupBy(function (WorkHour $item): string {
    return $item->timestamp->format('Y-m-d');
});
```

## 📝 Widget Templates by Purpose

### 1. Display-Only Widget Template
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class DisplayOnlyWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.display-only';
    
    protected static ?int $sort = 1;
    
    /**
     * MANDATORY: Even for display-only widgets
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return []; // Empty array is valid
    }
    
    public function getViewData(): array
    {
        return [
            'stats' => $this->calculateStats(),
            'data' => $this->getData(),
        ];
    }
    
    private function calculateStats(): array
    {
        // Your calculation logic
        return [];
    }
}
```

### 2. Form-Based Widget Template
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class FormBasedWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.form-based';
    
    protected static ?int $sort = 2;
    
    /**
     * MANDATORY: Form schema definition
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('Form Title')
                ->schema([
                    TextInput::make('field1')
                        ->required()
                        ->label(__('employee::fields.field1')),
                        
                    Select::make('field2')
                        ->options([
                            'option1' => 'Option 1',
                            'option2' => 'Option 2',
                        ])
                        ->label(__('employee::fields.field2')),
                ]),
        ];
    }
    
    public function submit(): void
    {
        $data = $this->form->getState();
        
        // Process form submission
        $this->notify('Form submitted successfully!');
    }
}
```

### 3. Interactive Widget Template
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Livewire\Attributes\Reactive;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class InteractiveWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.interactive';
    
    protected static ?int $sort = 3;
    
    #[Reactive]
    public array $data = [];
    
    /**
     * MANDATORY: Form schema
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return []; // No form needed for this widget
    }
    
    public function refresh(): void
    {
        $this->data = $this->loadData();
        $this->dispatch('widget-refreshed');
    }
    
    public function getViewData(): array
    {
        return [
            'items' => $this->data,
            'canRefresh' => true,
        ];
    }
    
    private function loadData(): array
    {
        // Load widget data
        return [];
    }
}
```

## ✅ Final Validation Checklist

Before submitting your widget:

### Code Quality
- [ ] PHPStan level 10 passes without errors
- [ ] All abstract methods implemented
- [ ] Proper type annotations added
- [ ] Translations handled safely

### Architecture Compliance
- [ ] Extends `XotBaseWidget` (not direct Filament classes)
- [ ] Follows module file structure
- [ ] Uses correct namespacing

### Functionality
- [ ] Widget renders without errors
- [ ] Form submission works (if applicable)
- [ ] Data loading works correctly
- [ ] View template displays properly

### Documentation
- [ ] Code is properly documented
- [ ] Purpose and usage is clear
- [ ] Any special requirements noted

---

**Remember**: The `getFormSchema()` method is **MANDATORY** for all XotBase widgets. Even display-only widgets must implement it (returning an empty array is valid).

**Always run PHPStan level 10** before submitting to catch type safety issues early.