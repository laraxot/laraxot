# Form Tag Fix for Create Ticket Wizard

**Issue**: Missing `<form wire:submit="submit">` wrapper in `create-ticket-wizard.blade.php`

**Problem**: The wizard form was not working because the `{{ $this->form }}` was not wrapped in the required form tag with Livewire's `wire:submit` directive.

**Files Affected**:
- `laravel/Themes/Sixteen/resources/views/filament/widgets/create-ticket-wizard.blade.php`

**Fix Applied**:
Added the required form wrapper around `{{ $this->form }}`:

```blade
<form wire:submit="submit">
    {{ $this->form }}
</form>
```

**Correct Pattern for Wizard Views**:
According to Filament best practices and vendor documentation (`laravel/vendor/filament/forms/stubs/LivewireFormView.stub`), wizard views must:

1. Use `<form wire:submit="submit">` wrapper around `{{ $this->form }}`
2. Maintain the widget structure: `<x-filament-widgets::widget>`
3. Include Design Comuni styling wrappers
4. Keep modal actions at the bottom: `<x-filament-actions::modals />`

**Reference Documentation**:
- [ticket-wizard-filament-refactor.md](../docs/ticket-wizard-filament-refactor.md) - Form wrapping requirements
- [wizard.txt](../docs/prompts/wizard.txt) - "La view widget del modulo deve essere wrapper/layout: titolo, container, `<form wire:submit="submit">`, `{{ $this->form }}`, modals."
- [LivewireFormView.stub](../../../vendor/filament/forms/stubs/LivewireFormView.stub) - Vendor template

**Verification**:
After the fix, the wizard form should:
- Submit properly via Livewire
- Maintain Design Comuni styling
- Work with Filament's wizard step navigation
- Pass PHPStan validation (0 errors)

**Date**: 2026-05-14  
**Status**: Fixed