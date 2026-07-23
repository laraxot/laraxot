# XotBaseResource Form Schema Audit

## Overview
An audit of XotBaseResource classes revealed that 46 classes are missing the `getFormSchema()` method.

## Affected Modules
- Xot
- Tenant
- Job
- Media
- Notify
- TechPlanner
- Activity
- Gdpr
- User

## Action Items
1. Implement `getFormSchema()` method in each identified Resource class
2. Ensure method follows consistent pattern
3. Add method to base abstract class if applicable
4. Update documentation for each implementation

## Detailed Findings
Total classes checked: 94
Classes missing getFormSchema: 46

### Modules Breakdown
- Xot: 6 classes
- Tenant: 1 class
- Job: 7 classes
- Media: 3 classes
- Notify: 2 classes
- TechPlanner: 7 classes
- Activity: 3 classes
- Gdpr: 6 classes
- User: 7 classes

## Recommended Implementation
```php
public function getFormSchema(): array
{
    return [
        // Define form fields consistently
        // Example:
        Forms\Components\TextInput::make('name')
            ->required(),
        // Add other form fields specific to the resource
    ];
}
```

## Next Steps
- Review each Resource class individually
- Implement getFormSchema method
- Ensure method is consistent across all XotBaseResource subclasses
- Update documentation for each implementation
- Run automated checks to verify implementation
