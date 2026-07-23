# XotBase Extension Patterns - FUNDAMENTAL LARAXOT RULE

## FUNDAMENTAL RULE - NEVER VIOLATE
**NEVER extend Filament classes directly. ALWAYS extend XotBase abstract classes.**

## CRITICAL ADDITIONAL RULE
**XotBaseResource classes do NOT need getTableColumns() method - this is handled automatically by the base class.**

## Required Extensions and Abstract Methods

### 1. Never Extend Filament Classes Directly
All Filament components MUST extend XotBase abstract classes:

- **Resources**: `extends XotBaseResource` (not `Resource`)
- **Create Pages**: `extends XotBaseCreateRecord` (not `CreateRecord`)
- **Edit Pages**: `extends XotBaseEditRecord` (not `EditRecord`)
- **List Pages**: `extends XotBaseListRecords` (not `ListRecords`)
- **View Pages**: `extends XotBaseViewRecord` (not `ViewRecord`)
- **Custom Pages**: `extends XotBasePage` (not `Page`)
- **Widgets**: `extends XotBaseWidget` (not `Widget`)

### 1.1. Method Visibility Requirements
**CRITICAL**: Abstract method implementations MUST match parent visibility exactly:

```php
// ❌ FATAL ERROR - Visibility mismatch
abstract class XotBaseWidget {
    public abstract function getFormSchema(): array;
}
class MyWidget extends XotBaseWidget {
    protected function getFormSchema(): array {} // FATAL ERROR
}

// ✅ CORRECT - Matching visibility
class MyWidget extends XotBaseWidget {
    public function getFormSchema(): array {} // CORRECT
}
```

### 2. Module-Specific Base Classes Take Priority
When a module provides specialized base classes, use them instead of generic XotBase classes:

#### Lang Module Base Classes (for Translatable content)
- **Resources with Translatable**: `extends LangBaseResource` (not `XotBaseResource`)
- **Edit Pages with Translatable**: `extends LangBaseEditRecord` (not `XotBaseEditRecord`)
- **Create Pages with Translatable**: `extends LangBaseCreateRecord` (not `XotBaseCreateRecord`)
- **List Pages with Translatable**: `extends LangBaseListRecords` (not `XotBaseListRecords`)
- **View Pages with Translatable**: `extends LangBaseViewRecord` (not `XotBaseViewRecord`)

#### User Module Base Classes (for User-specific functionality)
- **User Edit Pages**: `extends BaseEditUser` (not `XotBaseEditRecord`)

### 3. Translatable Trait Rule
**CRITICAL**: Any resource or page that uses the `Translatable` trait MUST extend LangBase classes, not XotBase classes.

```php
// ❌ WRONG - Translatable with XotBase
class MyResource extends XotBaseResource
{
    use Translatable; // This will cause errors
}

// ✅ CORRECT - Translatable with LangBase
class MyResource extends LangBaseResource
{
    use Translatable; // This works correctly
}
```

### 1. XotBaseResource
```php
// ❌ NEVER: extends Filament\Resources\Resource
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\XotBaseResource

class MyResource extends XotBaseResource
{
    protected static ?string $model = MyModel::class;

    /**
     * REQUIRED: Abstract method from XotBaseResource
     */
    public static function getFormSchema(): array
    {
        return [
            // Form schema definition
        ];
    }
}
```

### 2. XotBaseCreateRecord
```php
// ❌ NEVER: extends Filament\Resources\Pages\CreateRecord
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord

class CreateMyRecord extends XotBaseCreateRecord
{
    protected static string $resource = MyResource::class;

    // Optional: Override methods as needed
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
```

### 3. XotBaseEditRecord
```php
// ❌ NEVER: extends Filament\Resources\Pages\EditRecord
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord

class EditMyRecord extends XotBaseEditRecord
{
    protected static string $resource = MyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
```

### 4. XotBaseListRecords
```php
// ❌ NEVER: extends Filament\Resources\Pages\ListRecords
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\Pages\XotBaseListRecords

class ListMyRecords extends XotBaseListRecords
{
    protected static string $resource = MyResource::class;

    /**
     * REQUIRED: Abstract method from XotBaseListRecords
     */
    public static function getTableColumns(): array
    {
        return [
            'name' => Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
            // Other columns...
        ];
    }
}
```

### 5. XotBaseViewRecord
```php
// ❌ NEVER: extends Filament\Resources\Pages\ViewRecord
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord

class ViewMyRecord extends XotBaseViewRecord
{
    protected static string $resource = MyResource::class;

    /**
     * REQUIRED: Abstract method from XotBaseViewRecord
     */
    public static function getInfolistSchema(): array
    {
        return [
            // Infolist schema definition
        ];
    }
}
```

### 6. XotBasePage
```php
// ❌ NEVER: extends Filament\Resources\Pages\Page
// ✅ ALWAYS: extends Modules\Xot\Filament\Resources\Pages\XotBasePage

class MyCustomPage extends XotBasePage
{
    protected static string $resource = MyResource::class;
    protected static string $view = 'module::filament.pages.my-page';

    public function getViewData(): array
    {
        return [
            'title' => 'My Custom Page',
        ];
    }
}
```

## Error Prevention Checklist

### Before Creating Any Filament Component:
- [ ] Check if extending XotBase class instead of Filament class
- [ ] Identify required abstract methods for the XotBase class
- [ ] Implement all required abstract methods
- [ ] Use proper return type annotations
- [ ] Follow Laraxot naming conventions

### Common Abstract Methods by XotBase Class:
- **XotBaseResource**: `getFormSchema(): array`
- **XotBaseListRecords**: `getTableColumns(): array`
- **XotBaseViewRecord**: `getInfolistSchema(): array`
- **XotBaseCreateRecord**: No required abstract methods
- **XotBaseEditRecord**: No required abstract methods
- **XotBasePage**: No required abstract methods

## Why XotBase Classes Exist

1. **Centralized Management**: Common functionality is handled automatically
2. **Backward Compatibility**: Respect old paths and patterns
3. **Auto-Configuration**: Navigation, properties, and behaviors are auto-managed
4. **Consistency**: All modules follow the same patterns
5. **Maintainability**: Changes to base behavior affect all modules consistently
6. **Enforcement**: Abstract methods ensure consistent implementation

## Common Errors and Solutions

### Error: "Class contains 1 abstract method and must therefore be declared abstract"
**Solution**: Implement the required abstract method for the XotBase class you're extending.

### Error: Method signature not compatible
**Solution**: Check the parent class method signature and match it exactly.

### Error: Navigation not working
**Solution**: Remove hardcoded navigation properties - they're auto-managed by XotBase.

## Documentation Updates Required

When creating new Filament components:
1. Update module documentation in `Modules/{ModuleName}/docs/`
2. Update root documentation if it affects multiple modules
3. Create bidirectional links between related documentation
4. Update this document with new patterns discovered

## This Rule Applies To
- ALL modules
- ALL Filament components
- ALL future development
- NO exceptions

**CRITICAL**: This is a fundamental Laraxot philosophy rule that must NEVER be violated.
