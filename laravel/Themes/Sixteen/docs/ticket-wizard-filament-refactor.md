# Ticket Wizard Filament Refactor

**Status**: Active  
**Created**: 2026-04-12  
**Last Updated**: 2026-04-12  
**Category**: Filament / Wizard / Frontoffice  
**Related Story**: [7-30](../../../_bmad-output/implementation-artifacts/7-30-refactor-ticket-wizard-to-filament-pure.md)

## Current State

### Widget PHP
`CreateTicketWizardWidget.php` usa gia `Wizard::make([...])` con `Step::make(...)` — corretto per Filament way.

### Blade View (PROBLEMA)
`ticket-create-wizard.blade.php` contiene HTML hardcoded:
- Wrapper Design Comuni (`container`, `cmp-heading`, `ticket-wizard-root`)
- Sezione contatti (`bg-grey-card shadow-contacts`)
- Form wrapping (`<x-filament-widgets::widget>`, `<form wire:submit="submit">`)

## Target State

### Blade minimale
```blade
<div class="ticket-wizard-root">
    <form wire:submit="submit">
        {{ $this->form }}
    </form>
    <x-filament-actions::modals />
</div>
```

### Design Comuni via CSS
- Titolo, sezioni, contatti gestiti via Filament `Section::make()` con custom view
- Styling tramite CSS scoped a `.ticket-wizard-root`
- Mobile-first: 375px → 768px → 1024px

## Files Involved

- `laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php` — wizard PHP
- `laravel/Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php` — blade view (da semplificare)
- `laravel/Themes/Sixteen/resources/css/ticket-wizard-filament.css` — NUOVO CSS scoped

## Related Documentation

- [CreateTicketWizardWidget.md](../../../laravel/Modules/Fixcity/docs/CreateTicketWizardWidget.md) — Widget docs
- [TICKET-CREATION-WIZARD.md](../../../laravel/Themes/Sixteen/docs/design-comuni/TICKET-CREATION-WIZARD.md) — Theme docs
- [Story 7-34](../../../_bmad-output/implementation-artifacts/7-34-create-ticket-wizard-filament-schema-wizard-refactor.md) — Wizard Filament v5 Schema (done)
- [Story 7-35](../../../_bmad-output/implementation-artifacts/7-35-segnalazione-crea-filament-wizard-way-audit-and-refactor.md) — Audit wizard
- [Story 7-30](../../../_bmad-output/implementation-artifacts/7-30-refactor-ticket-wizard-to-filament-pure.md) — Refactor story

## Notes

Il widget PHP e gia corretto — il problema e SOLO la blade view che ha troppo HTML hardcoded invece di affidarsi a Filament per il rendering.
