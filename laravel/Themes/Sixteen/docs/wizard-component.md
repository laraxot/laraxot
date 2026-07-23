# Wizard Component Template

The `wizard.blade.php` component in the Sixteen theme implements the Design Comuni (Italia.it) visual standards for multi-step forms.

## Structure

The component wraps the Filament Wizard component, providing:

- **Design Comuni Stepper**: A custom CSS/HTML stepper that indicates current progress.
- **Dynamic Content**: Renders the active step's schema using Filament's view component system.
- **Action Buttons**: Custom-styled "Avanti", "Indietro", and "Invia" buttons that trigger the corresponding Livewire actions.

## Visual Parity

To maintain parity with the Design Comuni reference:
- Uses `stepper` and `stepper-item` classes.
- Implements `active` and `completed` states for steps.
- Uses Italia.it icons and colors for navigation.

## Logic Integration

The template relies on methods exposed by the `XotBaseWizardWidget` and the Filament `Wizard` component:
- `getCurrentStepIndex()`: To determine the active step.
- `getSteps()`: To iterate over defined steps.
- `getAction()`, `getSubmitAction()`: To render standard Filament actions.
