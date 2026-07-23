---
title: "Design Comuni Font Tokens for Sixteen Theme"
type: concept
sources: ["https://github.com/italia/design-comuni-pagine-statiche", "laravel/Themes/Sixteen/resources/css/app.css"]
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [design-comuni, fonts, tokens, sixteeen-theme, tailwind]
related:
  - ../concepts/header-dynamic-navigation.md
  - ../../../docs/wiki/concepts/segnalazione-privacy-design-comuni-parity.md
  - ../../../docs/wiki/concepts/design-comuni-header-auth-state.md
---

# Design Comuni Font Tokens for Sixteen Theme

## Typography Tokens (Design Comuni → Tailwind)

Design Comuni uses Bootstrap Italia which defines these font tokens. We convert to Tailwind CSS for Sixteen theme.

### Primary Font: Titillium Web

| Use Case | Design Comuni (Bootstrap) | Sixteen (Tailwind) |
|----------|---------------------------|----------------------|
| Body text | `font-family: 'Titillium Web', Arial, sans-serif` | `font-family: 'Titillium Web', Arial, sans-serif` |
| Base size | `font-size: 16px` | `text-base` (16px) |
| Small text | `font-size: 14px` | `text-sm` (14px) |
| Large text | `font-size: 18px` | `text-lg` (18px) |
| X-Large | `font-size: 20px` | `text-xl` (20px) |

### Secondary Font: Roboto Mono

| Use Case | Design Comuni (Bootstrap) | Sixteen (Tailwind) |
|----------|---------------------------|----------------------|
| Headings | `font-family: 'Roboto Mono', monospace` | `font-family: 'Roboto Mono', monospace` |
| Code blocks | `font-family: 'Roboto Mono', monospace` | `font-mono` + custom font |

## Implementation in Sixteen Theme

File: `laravel/Themes/Sixteen/resources/css/app.css`

```css
/* Import Design Comuni fonts */
@import url('https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600;700&family=Roboto+Mono:wght@400;500&display=swap');

/* Body text - Titillium Web */
body {
    font-family: 'Titillium Web', Arial, sans-serif;
    font-size: 16px;
    line-height: 1.5;
}

/* Headings - Roboto Mono */
h1, h2, h3, h4, h5, h6 {
    font-family: 'Roboto Mono', monospace;
    font-weight: 600;
}

/* Stepper text - match Design Comuni */
.filament-wizard-step-label {
    font-family: 'Titillium Web', Arial, sans-serif;
    font-size: 14px;
}

/* Checkbox text */
.filament-checkbox-label {
    font-family: 'Titillium Web', Arial, sans-serif;
    font-size: 16px;
}
```

## Design Comuni Reference Pages

- **Stepper Example**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
- **Font Source**: https://github.com/italia/design-comuni-pagine-statiche/blob/main/src/stylesheets/
- **Bootstrap Italia**: https://github.com/italia/bootstrap-italia

## Wizard Stepper Parity

### Design Comuni Stepper Structure (Bootstrap)

```html
<div class="stepper">
  <div class="step active">
    <span class="step-number">1</span>
    <span class="step-label">Autorizzazioni e condizioni</span>
  </div>
  <div class="step">
    <span class="step-number">2</span>
    <span class="step-label">Dati di segnalazione</span>
  </div>
  <div class="step">
    <span class="step-number">3</span>
    <span class="step-label">Riepilogo</span>
  </div>
</div>
```

### Sixteen + Filament Wizard (Tailwind)

```php
Wizard::make([
    Step::make('privacy')
        ->label('Autorizzazioni e condizioni')
        ->icon('heroicon-o-shield-check'),
    Step::make('data')
        ->label('Dati di segnalazione')
        ->icon('heroicon-o-pencil'),
    Step::make('summary')
        ->label('Riepilogo')
        ->icon('heroicon-o-document-text'),
])
->skippable()
->persistStepInQueryString()
```

## Checkbox Text Parity (CRITICAL)

Exact text from Design Comuni:
```
"Dichiaro di aver letto l'informativa sulla privacy e acconsento al trattamento dei dati personali"
```

Translation file: `laravel/Modules/Fixcity/lang/it/create_ticket_wizard.php`

```php
'privacyAccepted' => [
    'label' => 'Dichiaro di aver letto l\'informativa sulla privacy e acconsento al trattamento dei dati personali',
    // ...
],
```

## Mobile-First Responsive

Design Comuni uses responsive breakpoints:
- **Mobile**: `< 768px` → Stepper collapses to dropdown or horizontal scroll
- **Tablet**: `≥ 768px` → Stepper shows all steps
- **Desktop**: `≥ 1024px` → Full stepper with descriptions

Tailwind implementation:
```css
/* Mobile: stepper horizontal scroll */
@media (max-width: 768px) {
    .filament-wizard-steps {
        @apply flex overflow-x-auto;
    }
}

/* Desktop: full stepper */
@media (min-width: 1024px) {
    .filament-wizard-steps {
        @apply flex justify-between;
    }
}
```

## Quality Gates

- [ ] Fonts load from Google Fonts CDN (no 404)
- [ ] Stepper visible on all breakpoints
- [ ] Checkbox text matches Design Comuni exactly
- [ ] No Bootstrap classes in Blade (Tailwind only)
- [ ] Mobile responsive: stepper usable on 375px width

## Related Files

- Theme CSS: `laravel/Themes/Sixteen/resources/css/app.css`
- Wizard Schema: `laravel/Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php`
- Translation: `laravel/Modules/Fixcity/lang/it/create_ticket_wizard.php`
- Widget: `laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php`
- View: `laravel/Modules/Fixcity/resources/views/pages/tickets/create.blade.php`
