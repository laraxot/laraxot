# XotBase Extension Rules - Employee Module

## ABSOLUTE RULE: NEVER EXTEND FILAMENT CLASSES DIRECTLY

### CORRECT CLASS HIERARCHY

#### 1. RESOURCES
```php
// ❌ WRONG - Direct Filament extension
use Filament\Resources\Resource;
class MyResource extends Resource

// ✅ CORRECT - Use XotBase equivalent
use Modules\Xot\Filament\Resources\XotBaseResource;
class MyResource extends XotBaseResource
```

#### 2. PAGES
```php
// ❌ WRONG - Direct Filament extension
use Filament\Resources\Pages\CreateRecord;
class CreateMyResource extends CreateRecord

// ✅ CORRECT - Use XotBase equivalent
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
class CreateMyResource extends XotBaseCreateRecord
```

#### 3. WIDGETS
```php
// ❌ WRONG - Direct Filament extension
use Filament\Widgets\Widget;
class MyWidget extends Widget

// ✅ CORRECT - Use XotBase equivalent
use Modules\Xot\Filament\Widgets\XotBaseWidget;
class MyWidget extends XotBaseWidget
```

### FORBIDDEN IN XotBasePage CHILDREN
Never include these in classes extending XotBasePage:
```php
protected static ?string $navigationIcon;  // ❌
protected static ?string $title;           // ❌
protected static ?string $navigationLabel; // ❌
protected static ?string $navigationGroup; // ❌
protected static int $navigationSort;      // ❌
```

### METHOD IMPLEMENTATION RULES

#### 1. XotBaseResource
```php
// REQUIRED
public function getFormSchema(): array
{
    return [];
}

// NEVER IMPLEMENT - Handled by XotBase
public function getTableColumns(): array  // ❌
```

#### 2. XotBaseListRecords
```php
// REQUIRED - Define table columns
public function getTableColumns(): array
{
    return [];
}
```

#### 3. XotBaseViewRecord
```php
// REQUIRED - Define info list schema
public function getInfolistSchema(): array
{
    return [];
}
```

### VERIFICATION CHECKLIST
- [ ] Class extends XotBase* equivalent
- [ ] No direct Filament class extensions
- [ ] No navigation properties in XotBasePage children
- [ ] No redundant method overrides
- [ ] Follows module's established patterns
- [ ] All abstract methods are implemented
- [ ] Method visibility matches parent class

### COMMON MISTAKES TO AVOID
1. ❌ Extending Filament classes directly
2. ❌ Implementing methods already handled by XotBase
3. ❌ Using protected when parent method is public
4. ❌ Adding navigation properties to XotBasePage children
5. ❌ Creating duplicate functionality already in XotBase

### EXAMPLES FROM EMPLOYEE MODULE

#### Correct Implementation - WorkHoursPage
```php
use Modules\Xot\Filament\Pages\XotBasePage;

class WorkHoursPage extends XotBasePage
{
    protected static string $view = 'employee::filament.pages.work-hours';

    protected function getHeaderWidgets(): array
    {
        return [
            TimeClockWidget::class,
        ];
    }
}
```

#### Correct Implementation - TimeClockWidget
```php
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class TimeClockWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.time-clock';
    
    // Widget implementation...
}
```

Remember: When in doubt, check existing implementations in the module and follow established patterns.
