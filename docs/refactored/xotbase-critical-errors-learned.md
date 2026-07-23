# XotBase Critical Errors - Lessons Learned and Prevention

## Error Analysis: ViewLocation Missing getInfolistSchema()

### Error Details
```
Class Modules\Geo\Filament\Resources\LocationResource\Pages\ViewLocation contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord::getInfolistSchema)
```

### Root Cause
When extending XotBase classes, all abstract methods MUST be implemented. XotBase classes enforce consistent patterns through abstract methods.

### Resolution Applied
```php
class ViewLocation extends XotBaseViewRecord
{
    protected static string $resource = LocationResource::class;

    /**
     * REQUIRED: Abstract method from XotBaseViewRecord
     */
    public static function getInfolistSchema(): array
    {
        return [
            // Infolist schema definition
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
```

## XotBase Abstract Method Requirements

### XotBaseResource
- **Required**: `getFormSchema(): array`
- **Purpose**: Define form fields for create/edit operations
- **Example**:
```php
public static function getFormSchema(): array
{
    return [
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\Textarea::make('description'),
    ];
}
```

### XotBaseListRecords
- **Required**: `getTableColumns(): array`
- **Purpose**: Define table columns for list view
- **Example**:
```php
public static function getTableColumns(): array
{
    return [
        'name' => Tables\Columns\TextColumn::make('name')->sortable(),
        'created_at' => Tables\Columns\TextColumn::make('created_at')->dateTime(),
    ];
}
```

### XotBaseViewRecord
- **Required**: `getInfolistSchema(): array`
- **Purpose**: Define infolist fields for view operation
- **Example**:
```php
public static function getInfolistSchema(): array
{
    return [
        Infolists\Components\TextEntry::make('name'),
        Infolists\Components\TextEntry::make('description'),
    ];
}
```

### XotBaseCreateRecord
- **Required**: None (no abstract methods)
- **Optional**: Override `getRedirectUrl()`, `mutateFormDataBeforeCreate()`, etc.

### XotBaseEditRecord
- **Required**: None (no abstract methods)
- **Optional**: Override `getRedirectUrl()`, `mutateFormDataBeforeSave()`, etc.

### XotBasePage
- **Required**: None (no abstract methods)
- **Optional**: Override `getViewData()`, `getHeaderActions()`, etc.

## Prevention Strategy

### 1. Pre-Development Checklist
- [ ] Identify which XotBase class to extend
- [ ] Check abstract method requirements for that class
- [ ] Plan implementation of all required methods
- [ ] Use proper return type annotations

### 2. Template Approach
Create templates for each XotBase extension pattern to ensure consistency.

### 3. Documentation Updates
- Update module docs when adding new Filament components
- Document any custom abstract method implementations
- Maintain bidirectional links between related documentation

### 4. Error Detection
- Run PHPStan regularly to catch abstract method violations
- Test all Filament pages before deployment
- Use IDE hints to identify missing implementations

## Common Patterns and Solutions

### Pattern: Empty Abstract Method Implementation
When you need to implement an abstract method but don't have specific requirements:
```php
public static function getInfolistSchema(): array
{
    return [
        // Auto-generated from model fields or empty for now
    ];
}
```

### Pattern: Delegating to Resource
Some abstract methods can delegate to the resource class:
```php
public static function getTableColumns(): array
{
    return static::$resource::getTableColumns();
}
```

### Pattern: Using Model Introspection
Generate schema based on model structure:
```php
public static function getFormSchema(): array
{
    $model = static::$resource::getModel();
    // Generate form fields based on model fillable attributes
    return collect($model::make()->getFillable())
        ->map(fn($field) => Forms\Components\TextInput::make($field))
        ->toArray();
}
```

## Critical Rules Reinforced

1. **NEVER extend Filament classes directly**
2. **ALWAYS implement required abstract methods**
3. **ALWAYS use proper type annotations**
4. **ALWAYS test after implementation**
5. **ALWAYS update documentation**

## Memory Integration

This error and its resolution have been documented in:
- Memory system for permanent retention
- XotBase extension patterns documentation
- Employee module documentation updates
- Critical rules and guidelines

## Future Prevention

- All new Filament components must follow XotBase patterns
- Regular codebase audits for direct Filament extensions
- Template-based component generation
- Automated testing for abstract method compliance

---

**Last Updated**: August 27, 2025
**Status**: Critical error resolved, prevention measures implemented
**Next Review**: Before any new Filament component development
