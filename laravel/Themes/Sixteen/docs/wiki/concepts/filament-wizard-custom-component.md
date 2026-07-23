---
title: "Filament Wizard Custom Component - Theme Sixteen"
type: concept
sources: []
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [filament, wizard, component, blade, architecture, theme, design-comuni]
related:
  - ../../../../Modules/Fixcity/docs/wiki/concepts/wizard-theme-integration.md
  - ../../../../../docs/wiki/concepts/laraxot-theme-module-separation.md
---

# Filament Wizard Custom Component - Theme Sixteen

> **Philosophy**: Tema = Vestito (presentazione), Modulo = Logica (business)
>
> **Pattern**: Wrapper Blade component che riceve il wizard Filament via props e applica stili Design Comuni

## The Problem: Violation of Theme/Module Separation

### ❌ WRONG: Logic in Theme
```blade
{{-- Themes/Sixteen/views/filament/widgets/create-ticket-wizard.blade.php --}}
@php
    // ❌ LOGICA BUSINESS NEL TEMA - VIETATO!
    $steps = [
        ['label' => __('fixcity::create_ticket_wizard.steps.1.label'), ...],
        ['label' => __('fixcity::create_ticket_wizard.steps.2.label'), ...],
        ['label' => __('fixcity::create_ticket_wizard.steps.3.label'), ...],
    ];
@endphp

<div class="steppers">
    @foreach($steps as $step) {{-- ❌ Usa dati hardcoded, non dal Wizard --}}
        ...
    @endforeach
</div>
```

**Why this is wrong:**
1. **Duplication**: Steps defined both in `TicketForm::getSteps()` AND in theme
2. **Single Source of Truth violation**: Two sources for same data
3. **Business logic in theme**: Theme should only present, not decide
4. **No sync**: If wizard adds a step, theme doesn't know

### ✅ CORRECT: Props Down from Module

```
┌─────────────────────────────────────────────────────────────┐
│  FIXCITY MODULE (PHP/Logic)                                  │
│  ├── CreateTicketWizardWidget.php                            │
│  │   └── getFormSchema() → Wizard::make(getSteps())    │
│  └── TicketForm.php                                          │
│      └── getSteps() → [Step::make('privacy'), ...]     │
└────────────────────────┬────────────────────────────────────┘
                         │ $this->form (Wizard component)
                         ↓
┌─────────────────────────────────────────────────────────────┐
│  SIXTEEN THEME (Blade/Presentation)                         │
│  create-ticket-wizard.blade.php                              │
│  └── <x-pub_theme::wizard :form="$this->form" />           │
│                                                               │
│  Components/wizard.blade.php                                  │
│  └── Receives Wizard via $wizard prop                        │
│  └── $wizard->getSteps() → dynamic from module               │
└─────────────────────────────────────────────────────────────┘
```

## The Solution: pub_theme::wizard Component

### File Structure
```
Themes/Sixteen/resources/views/components/
├── wizard.blade.php              # Main wrapper component
├── wizard/
│   ├── stepper.blade.php         # Design Comuni stepper
│   └── step.blade.php            # Individual step wrapper (optional)
```

### Component API

#### pub_theme::wizard
```php
@props([
    'wizard' => null,           // Filament\Schemas\Components\Wizard instance
    'form' => null,             // Alternative: pass entire form
    'showStepper' => true,      // Show Design Comuni stepper
    'stepperPosition' => 'top', // top, bottom, both
])

{{-- Extract wizard from form if needed --}}
@php
$wizardComponent = $wizard ?? $form?->getComponent('wizard');
$steps = $wizardComponent?->getSteps() ?? [];
$currentStepIndex = $wizardComponent?->getCurrentStepIndex() ?? 0;
@endphp
```

#### pub_theme::wizard.stepper
```php
@props([
    'steps' => [],           // Array of Filament\Schemas\Components\Wizard\Step
    'currentStep' => 0,      // 0-based index
])

@foreach($steps as $index => $step)
    {{ $step->getLabel() }}     {{-- Dynamic from Wizard --}}
    {{ $step->getDescription() }}
@endforeach
```

## Implementation Guide

### Step 1: Create Component
```bash
touch Themes/Sixteen/resources/views/components/wizard.blade.php
touch Themes/Sixteen/resources/views/components/wizard/stepper.blade.php
```

### Step 2: Component Blade Code
```blade
{{-- wizard.blade.php --}}
@props(['wizard' => null, 'form' => null, 'showStepper' => true])

@php
    $wizardComponent = $wizard ?? $form?->getComponent('wizard');
    $steps = $wizardComponent?->getSteps() ?? [];
    $statePath = $wizardComponent?->getStatePath();
    
    // Get current step from Livewire state
    $currentStep = data_get($this, $statePath ?? 'wizardStartStep', 1);
@endphp

<div class="ticket-wizard-root">
    {{-- Design Comuni Stepper --}}
    @if($showStepper)
        <x-pub_theme::wizard.stepper 
            :steps="$steps" 
            :current-step="$currentStep"
            :state-path="$statePath"
        />
    @endif
    
    {{-- Native Filament Wizard --}}
    @if($wizardComponent)
        {{ $wizardComponent }}
    @endif
</div>
```

### Step 3: Refactor Theme View
```blade
{{-- Themes/Sixteen/views/filament/widgets/create-ticket-wizard.blade.php --}}
<x-filament-widgets::widget>
    <div class="container wizard-dc-heading-shell">
        {{-- Title section --}}
    </div>
    
    {{-- Use custom wizard component --}}
    <div class="container">
        <x-pub_theme::wizard :form="$this->form" show-stepper="true" />
    </div>
    
    <x-filament-actions::modals />
</x-filament-widgets::widget>
```

## Filament v5 Wizard Study Notes

### Key Classes
```php
// Filament\Schemas\Components\Wizard
public function getSteps(): array;           // Returns Step[]
public function getStartStep(): int;
public function isSkippable(): bool;
public function getStatePath(): string;      // Livewire property path

// Filament\Schemas\Components\Wizard\Step
public function getLabel(): string;
public function getDescription(): ?string;
public function getIcon(): ?string;
```

### Accessing from Blade
```blade
{{-- If you have $wizard component --}}
@foreach($wizard->getSteps() as $stepIndex => $step)
    {{ $step->getLabel() }}
@endforeach

{{-- From Livewire widget context --}}
@php
$wizardComponent = $this->getForm()?->getComponent('wizard');
$steps = $wizardComponent?->getSteps() ?? [];
@endphp
```

## CSS Architecture

### Files
```
Themes/Sixteen/resources/css/components/
├── filament-wizard-parity.css    # Main styles
└── wizard-stepper.css            # Stepper specific
```

### Key Classes
```css
.ticket-wizard-root { }
.wizard-stepper { }
.wizard-step { }
.wizard-step.active { }
.wizard-step.confirmed { }
.wizard-step-divider { }
```

## Testing Checklist

- [ ] Step labels come from `TicketForm::getSteps()`, not hardcoded
- [ ] Adding a step in `TicketForm` automatically shows in UI
- [ ] Stepper highlights current step correctly
- [ ] Stepper shows completed steps with checkmark
- [ ] Mobile: stepper responsive
- [ ] No PHP logic in theme blade (only presentation)

## References

- **Story 8-111**: `_bmad-output/implementation-artifacts/8-111-fixcity-wizard-theme-component-architecture.md`
- **Filament Wizard Source**: https://github.com/filamentphp/filament/blob/5.x/packages/schemas/src/Components/Wizard.php
- **Laraxot Architecture**: `docs/wiki/concepts/laraxot-theme-module-separation.md`

---

**Rule**: Theme Never Defines Business Logic. Theme Only Presents What Module Provides.
