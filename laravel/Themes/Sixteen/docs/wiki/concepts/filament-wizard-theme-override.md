# Filament Wizard Theme Override — Philosophy & Implementation

## The Zen of Separation (Il Zen della Separazione)

### The Problem
Filament provides excellent wizard components out of the box. However, when we need to customize the "dress" (il vestito) of the wizard — its visual appearance, markup structure, or behavior — we face a choice:

1. **Modify Filament core** — Wrong, breaks updates
2. **Override in module Blade** (e.g., `Fixcity` module) — Wrong, mixes concerns
3. **Override in theme Blade** (e.g., `Sixteen` theme) — **Correct**

### The Philosophy

> "The module owns the logic, the theme owns the dress."

This is not just a technical decision — it's a architectural religion:

- **Modules** (Fixcity, Geo, etc.) own:
  - Business logic
  - Data schemas
  - Widget classes
  - Lang files (labels, tooltips via LangServiceProvider)
  - PHP-based form definitions

- **Themes** (Sixteen) own:
  - Visual presentation
  - Blade templates for components
  - CSS/JS assets
  - Alpine.js behavior customization
  - The "look and feel"

### Why `pub_theme::components.wizard`?

Filament's `Wizard` component resolves its Blade view via:
```php
// In Filament\Schemas\Components\Wizard
public function getView(): string
{
    return 'filament-schemas::components.wizard';
}
```

By creating `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php`, and configuring the theme to override `filament-schemas::components.wizard`, we allow the theme to "dress" the wizard without touching:
- Filament core (updates remain safe)
- Module code (business logic untouched)

### Implementation Details

#### File Location
```
laravel/Themes/Sixteen/resources/views/components/wizard.blade.php
```

This maps to the view name `pub_theme::components.wizard` when the Sixteen theme is active.

#### Source of Truth
The Blade was created by studying:
- **PHP Component**: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Wizard.php
- **Blade Template**: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/resources/views/components/wizard.blade.php

#### Key Alpine.js Structure
The wizard uses `x-data="wizardSchemaComponent({...})"` which provides:
- `step` — current step index (reactive)
- `goToNextStep()` — navigation logic
- `isFirstStep()` / `isLastStep()` — boundary checks
- `isStepAccessible(index)` — respects step order and skippable flag
- Query string persistence via `isStepPersistedInQueryString` and `stepQueryStringKey`

#### Step Rendering
Steps are rendered in a `<ol>` header (unless `$isHeaderHidden`), with:
- Step icons (or numbered circles)
- Completed state icons
- Clickable navigation (respecting accessibility)
- Separator SVG between steps

Each step's content is rendered in a `x-show="step === {{ $loop->index }}"` div.

#### Footer Actions
The footer contains:
- **Previous** button (hidden on first step)
- **Cancel** button (hidden on first step)
- **Next** button (hidden on last step)
- **Submit** button (visible on last step)

### What We Did NOT Do

❌ **Did NOT add `->label()` or `->tooltip()` in Blade**
- Labels belong in Lang files via LangServiceProvider

❌ **Did NOT put `<style>` blocks in Blade**
- CSS belongs in Sixteen theme's CSS files

❌ **Did NOT modify Filament core**
- We override via theme, not core edit

❌ **Did NOT create wizard Blade inside module (Fixcity)**
- Module owns logic, theme owns presentation

### The Dress (Il Vestito)

When Design Comuni requests visual changes to the wizard:
1. Edit `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php` for markup
2. Edit Sixteen theme CSS for styling
3. Run `npm run build && npm run copy` in `laravel/Themes/Sixteen`
4. Clear views: `php artisan view:clear`

The Fixcity module's `CreateTicketWizardWidget` and `TicketForm::getSteps()` remain untouched.

### Quality Checks

After customization:
```bash
# PHP static analysis
./vendor/bin/phpstan analyse --level=max laravel/Themes/Sixteen

# Check Blade syntax
php -l laravel/Themes/Sixteen/resources/views/components/wizard.blade.php

# Verify view resolution
php artisan view:clear && php artisan route:clear
```

### References
- Filament 5.x Wizard Component: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Wizard.php
- Filament 5.x Wizard Blade: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/resources/views/components/wizard.blade.php
- Design Comuni Stepper Patterns: https://designers.italia.it/pattern/stepper/
- BMAD Story 8-106: Header navigation JSON-driven + segnalazione-crea parity
