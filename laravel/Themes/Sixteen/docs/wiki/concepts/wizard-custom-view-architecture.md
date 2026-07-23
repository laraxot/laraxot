---
title: "Wizard Custom View Architecture - Theme Sixteen"
type: concept
sources:
  - laravel/Themes/Sixteen/resources/views/components/wizard.blade.php
  - laravel/Themes/Sixteen/resources/views/filament/widgets/create-ticket-wizard.blade.php
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [filament, wizard, custom-view, theme, architecture, design-comuni]
related:
  - ../../../../Modules/Xot/docs/wiki/concepts/xotbasewizardwidget-architecture.md
  - ../../../../Modules/Fixcity/docs/wiki/concepts/create-ticket-wizard-architecture.md
---

# Wizard Custom View Architecture

> **Philosophy**: The theme owns the "dress" (presentation) of the wizard. The module owns the logic.
>
> **Pattern**: `XotBaseWizardWidget` configures a custom view from the theme. The theme view renders everything: stepper, content, AND actions.

## The Zen

```
┌─────────────────────────────────────────────────────────────┐
│  THEME (pub_theme) = VESTITO (Presentation Layer)            │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  create-ticket-wizard.blade.php                               │
│  └── Container + Title wrapper                               │
│       └── {{ $this->form }}                                  │
│            │                                                  │
│            ▼                                                  │
│  pub_theme::components.wizard (custom view)                  │
│  ├── Stepper: <x-pub_theme::wizard.stepper />               │
│  ├── Content: @foreach($steps) {{ $step }} @endforeach      │
│  └── Actions: Avanti/Indietro/Submit in fi-sc-wizard-footer  │
│                                                               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│  MODULE (Xot) = LOGICA (Business Logic Layer)               │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  XotBaseWizardWidget::makeWizard()                            │
│  ├── Wizard::make($steps) - Schema definition                │
│  ├── ->view('pub_theme::components.wizard') ← TEMA!          │
│  ├── ->nextAction() - Navigation logic                     │
│  ├── ->submitAction() - Form submission                    │
│  └── ->skippable() - Step configuration                    │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## File Structure

### Theme Files
```
Themes/Sixteen/resources/views/
├── components/
│   └── wizard.blade.php              # Custom wizard view
├── components/wizard/
│   └── stepper.blade.php             # Design Comuni stepper
└── filament/widgets/
    └── create-ticket-wizard.blade.php # Wrapper with title
```

### Module Files
```
Modules/Xot/app/Filament/Widgets/
└── XotBaseWizardWidget.php           # Configures custom view

Modules/Fixcity/app/Filament/Widgets/
└── CreateTicketWizardWidget.php      # Extends base
```

## Key Insight: Why Custom View?

### WITHOUT Custom View (Wrong approach)
```php
// Theme tries to wrap wizard after it's rendered
<div class="my-wrapper">
    {{ $this->form }}  // Wizard already rendered with default skin
</div>
// Result: Default Filament wizard, theme just adds container
```

### WITH Custom View (Correct approach)
```php
// XotBaseWizardWidget configures theme view
$wizard->view('pub_theme::components.wizard');

// Theme view controls EVERYTHING inside the wizard
<div class="fi-sc-wizard">
    <x-pub_theme::wizard.stepper />  // Design Comuni stepper
    @foreach($steps) {{ $step }} @endforeach  // Content
    <div class="fi-sc-wizard-footer">
        {{ $previousAction }}  // Indietro
        {{ $nextAction }}      // Avanti
        {{ $getSubmitAction() }} // Submit
    </div>
</div>
// Result: Full Design Comuni skin, including actions
```

## The View Contract

### What Filament Provides to the View
```php
// Available variables in pub_theme::components.wizard
$isContained           // Bool
$key                   // String
$previousAction        // Action (Indietro)
$nextAction            // Action (Avanti)
$steps                 // Step[]
$isHeaderHidden        // Bool
$getLabel()            // ?string
$getId()               // string
$getExtraAttributes()   // array
$getExtraAlpineAttributes() // array
$isSkippable()         // bool
$isStepPersistedInQueryString() // bool
$getStartStep()        // int
$getStepQueryStringKey() // string
$getCancelAction()     // Action
$getSubmitAction()     // Action
```

### What the View MUST Render
1. **Stepper**: Navigation between steps (Design Comuni style)
2. **Steps Content**: `@foreach($steps) {{ $step }} @endforeach`
3. **Actions Footer**: Previous, Next, Cancel, Submit

### What the View MUST Include
1. **Alpine.js setup**: `x-data="wizardSchemaComponent(...)"`
2. **Hidden input**: `x-ref="stepsData"` with step keys
3. **Event listeners**: `x-on:next-wizard-step.window`, `x-on:go-to-wizard-step.window`

## Architecture Comparison

| Aspect | Without Custom View | With Custom View |
|--------|---------------------|------------------|
| Stepper | Filament default | Design Comuni |
| Actions | Filament default | Fully customizable |
| Styling | CSS overrides | Complete control |
| Logic | Module only | Module + Theme presentation |

## Common Mistakes

### ❌ Mistake 1: View Without Actions
```blade
{{-- WRONG: Actions not rendered! --}}
<div class="my-wizard">
    <x-wizard.stepper />
    @foreach($steps) {{ $step }} @endforeach
    {{-- Missing actions! --}}
</div>
```

### ✅ Correct: Full View with Actions
```blade
{{-- CORRECT: Everything rendered --}}
<div x-data="wizardSchemaComponent(...)" ...>
    <input type="hidden" x-ref="stepsData" ... />
    
    {{-- Stepper --}}
    <x-pub_theme::wizard.stepper :steps="$steps" ... />
    
    {{-- Content --}}
    @foreach($steps) {{ $step }} @endforeach
    
    {{-- Actions --}}
    <div class="fi-sc-wizard-footer">
        {{ $previousAction }}
        {{ $nextAction }}
        {{ $getSubmitAction() }}
    </div>
</div>
```

### ❌ Mistake 2: Duplicate Stepper
```blade
{{-- theme wrapper --}}
<x-pub_theme::wizard.stepper />  {{-- ❌ Here --}}
{{ $this->form }}  {{-- And also in custom view! --}}
```

### ✅ Correct: Stepper Only in Custom View
```blade
{{-- theme wrapper --}}
{{ $this->form }}  {{-- Custom view handles stepper --}}
```

## References

- **Story 8-114**: XotBaseWizardWidget vs Filament HasWizard
- **Filament Wizard Source**: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/resources/views/components/wizard.blade.php
- **Theme View**: `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php`
- **Theme Wrapper**: `laravel/Themes/Sixteen/resources/views/filament/widgets/create-ticket-wizard.blade.php`

---

**Remember**: The custom view is the "dress" of the wizard. It must render EVERYTHING: stepper, content, AND actions.
