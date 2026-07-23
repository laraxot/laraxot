# RelationManager Form Schema Pattern

## Overview
In Filament RelationManagers that extend XotBaseRelationManager, we use a consistent pattern for defining form schemas:

1. **getFormSchema()** method:
   - Should be used instead of the form() method
   - Returns an array of form fields
   - Is called by the form() method internally
   - Promotes code reuse and consistency

2. **form()** method:
   - Should not be overridden directly
   - Uses getFormSchema() to build the form
   - Handles form configuration and layout

## Implementation Example

```php
protected static function getFormSchema(): array
{
    return [
        TextInput::make('name')->required(),
        Select::make('type')->options([
            'type1' => 'Type 1',
            'type2' => 'Type 2',
        ]),
    ];
}
```

## Best Practices
- Always use getFormSchema() instead of overriding form()
- Keep schema definitions reusable and modular
- Document schema requirements in the method's PHPDoc
- Use consistent field naming conventions
