# Filament Wizard: Philosophy, Religion, Zen

## Date
2026-05-05

## The Filament Way (Philosophy)

### Server-Driven UI (SDUI)
Filament is a **Server-Driven UI framework**. The PHP code defines the UI structure, and Alpine.js + Blade render it.

### Wizard Component Architecture
```
Filament\Schemas\Components\Wizard (component)
    ↓ uses
Filament\Actions\Concerns\HasWizard (trait for actions/pages)
    ↓ renders
filament-schemas::components.wizard (Blade view)
```

### Why `pub_theme::components.wizard`?
**Separation of Concerns (Zen):**
- **Module** = Business logic, schema definition, state management
- **Theme** = Visual presentation, CSS classes, HTML structure variations

When we customize the wizard "dress" (vestito), we copy the **Blade view logic** to the theme, NOT reinvent PHP logic.

### The Flow
1. PHP: `Wizard::make($steps)->startOnStep(...)`
2. View: `wizard.blade.php` reads `$getStartStep()`, `$getAction('next')`, etc.
3. Alpine: `wizardSchemaComponent` handles step navigation via Livewire events

## Current Issue in `pub_theme::components.wizard`

### What Was Wrong
The previous implementation tried to reimplement wizard logic in Blade instead of copying the vendor view logic.

### Correct Approach
1. Copy **logic** from `vendor/filament/schemas/resources/views/components/wizard.blade.php`
2. Adapt **styling** to theme needs (CSS classes, wrapper divs)
3. Keep **navigation logic** intact (Alpine component, Livewire events)

## Why Same Wizard, Different Dresses?

### URL 1: `/it/tests/segnalazione-crea`
- **Context**: Frontoffice (public theme)
- **Widget**: `CreateTicketWizardWidget`
- **View**: `pub_theme::components.wizard` (Sixteen theme)
- **Purpose**: Citizen-facing ticket creation

### URL 2: `/fixcity/admin/tickets/create`
- **Context**: Admin panel
- **Page**: `TicketResource::create` (Filament admin)
- **View**: Default Filament admin wizard
- **Purpose**: Admin ticket creation

Both use the same `Wizard` component, but different Blade views = different visual presentation.

## Issues to Fix on `/it/tests/segnalazione-crea`

1. **Missing "Next" button**: The `nextAction` is not rendering properly
   - Check: Is `XotBaseWizardWidget::getWizardSubmitAction()` returning correct Action?
   - Check: Is Alpine `requestNextStep()` being called?

2. **"Vai al contenuto principale" link**: This is an accessibility skip link
   - Should be hidden visually or positioned off-screen
   - Check: `laravel/Themes/Sixteen/resources/views/components/skip-link.blade.php`

## Safe Functions (Security Religion)

### Why `use function Safe\...` is Mandatory
The `thecodingmachine/safe` library wraps PHP functions to throw exceptions instead of returning `false`.

**Example:**
```php
// Dangerous (old way)
$content = file_get_contents($path); // returns false on error, no exception
if ($content === false) { /* handle */ }

// Safe way (required in this project)
use function Safe\file_get_contents;
$content = file_get_contents($path); // throws exception on error
```

### Never Remove Safe Imports
If you see `use function Safe\file_put_contents` removed, it was a mistake. Restore it.

## References
- https://github.com/filamentphp/filament/blob/5.x/packages/schemas/resources/views/components/wizard.blade.php
- https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Wizard.php
- https://github.com/filamentphp/filament/blob/5.x/packages/actions/src/Concerns/HasWizard.php
- `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php`
