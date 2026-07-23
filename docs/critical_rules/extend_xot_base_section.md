# Laraxot Critical Rule: Always Extend XotBaseSection

In the Laraxot framework, it is a critical rule that all custom Filament Section components **MUST** extend `Modules\Xot\Filament\Schemas\Components\XotBaseSection` instead of directly extending `Filament\Schemas\Components\Section`.

## Rationale

- **Consistency**: This ensures all sections in the application adhere to a common base, promoting a consistent and predictable architecture.
- **Centralized Logic**: `XotBaseSection` provides a centralized place to implement common functionality and default configurations for all sections.
- **Adherence to Laraxot Philosophy**: Following this rule is a fundamental aspect of the Laraxot development philosophy, which prioritizes convention over configuration and a unified coding style.

## Example

**Correct:**
```php
<?php

namespace Modules\MyModule\Filament\Forms\Components;

use Modules\Xot\Filament\Schemas\Components\XotBaseSection;

class MyCustomSection extends XotBaseSection
{
    // ...
}
```

**Incorrect:**
```php
<?php

namespace Modules\MyModule\Filament\Forms\Components;

use Filament\Schemas\Components\Section;

class MyCustomSection extends Section
{
    // ...
}
```
