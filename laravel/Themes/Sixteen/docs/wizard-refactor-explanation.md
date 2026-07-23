# Wizard Refactor Explanation

## Issue Summary
The `CreateTicketWizardWidget` had two main issues:
1. Syntax error due to duplicate `getSteps()` method
2. Missing "next" button in frontoffice wizard interface

## Changes Made

### 1. Fixed Syntax Error
Removed duplicate `getSteps()` method that was causing a fatal PHP error:
```php
// BEFORE (broken)
public function getSteps(): array
{
    return [
        Step::make('privacy')->label(__('fixcity::segnalazione.privacy.label')),
        Step::make('data')->label(__('fixcity::segnalazione.data.label')),
        Step::make('summary')->label(__('fixcity::segnalazione.summary.label')),
    ];
}
{
    return TicketForm::getSteps();
}

// AFTER (fixed)
public function getSteps(): array
{
    return TicketForm::getSteps();
}
```

### 2. Fixed Missing "Next" Button
Enhanced the `makeWizard()` method to properly configure navigation actions:
```php
protected function makeWizard(array $steps): Wizard
{
    $wizard = Wizard::make($steps)
        ->startOnStep(fn (): int => $this->wizardStartStep)
        ->columnSpanFull()
        ->skippable($this->hasSkippableWizardSteps())
        ->nextAction($this->configureWizardNextAction())  // Added
        ->previousAction($this->configureWizardPreviousAction())  // Added

    if ($this->queryStepOverrideAllowed()) {
        $wizard->persistStepInQueryString('step');
    }

    return $wizard;
}
```

## Technical Details

### Why the "Next" Button Was Missing
The original `XotBaseWizardWidget.makeWizard()` method was not explicitly setting the next and previous actions on the Wizard component. While Filament's Wizard component has default navigation behavior, the custom widget implementation needed to explicitly wire up these actions to ensure proper rendering in both admin and frontoffice contexts.

### Admin vs Frontoffice Handling
The widget now properly handles both contexts:
- **Admin**: Uses standard `Wizard::make()` with full configuration
- **Frontoffice**: Would use `PubThemeWizard::make()` when implemented (currently falls back to admin path)

## Files Modified
- `laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php`

## Verification
1. Syntax error resolved - widget loads without PHP fatal errors
2. Wizard steps load correctly from TicketForm
3. Navigation actions are properly configured
4. Widget extends XotBaseWizardWidget as before (maintains compatibility)
5. **View Resolution**: Redundant `$view` property removed; view path resolved automatically by `XotBaseWidget`.

## View Calculation Rule

As part of the Laraxot standardization:
- Classes extending `XotBaseWizardWidget` **must not** define `protected string $view`.
- The view is automatically calculated (e.g., `CreateTicketWizardWidget` -> `create-ticket-wizard.blade.php`).
- The resolved view must be documented in the class docblock using `@view`.

## Related Documentation
- See `wizard-governance-philosophy.md` for broader wizard implementation guidelines
- Check `filament-wizard-pattern.md` for Filament-specific wizard patterns