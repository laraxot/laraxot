# Ticket Creation Wizard - Implementation Report

**Date**: 2026-04-09  
**Status**: ✅ **COMPLETE**

---

## Architecture

### Single Page with Filament Widget

Instead of 4 separate pages (01-privacy, 02-dati, 03-riepilogo, 04-conferma), the ticket creation flow is now unified:

| Before | After |
|--------|-------|
| 4 separate pages | 1 page: `segnalazione-crea` |
| Manual Blade step navigation | **Filament v5 Wizard Schema** |
| Hundreds of Blade lines | **67-line thin wrapper view** |
| JSON blocks per page | Single JSON with widget block |
| 04-conferma separate | Still separate (redirect after submit) |

**Implementation**: Uses `Filament\Schemas\Components\Wizard` + `Wizard\Step` (Filament v5).
State managed in `$this->data` via `XotBaseWizardWidget::form()` (wrapper policy on top of `XotBaseWidget`, statePath: `data`).
Blade view is a **wrapper** (header + `{{ $this->form }}` + contacts footer).

Reference: [Filament v5 Wizards](https://filamentphp.com/docs/5.x/schemas/wizards)

### Component Structure

```
segnalazione-crea (page)
├── JSON: tests.segnalazione-crea.json
├── Blade: components/blocks/tests/segnalazione-crea.blade.php
└── Widget: Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php
    ├── View: Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php (impostata esplicitamente su `CreateTicketWizardWidget::$view`; il file tema `filament/widgets/createticketwizard.blade.php` non è usato per questo widget perché `GetViewByClassAction` lo selezionerebbe altrimenti al posto del layout con sidebar)
    ├── Schema SSoT: Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php
    └── 3 Steps:
        ├── Step 1: Privacy (checkbox)
        ├── Step 2: Data (address, type, title, details, images, author)
        └── Step 3: Summary + Submit → redirect to 04-conferma
```

### Old Pages Status

| Page | Status | Notes |
|------|--------|-------|
| segnalazione-01-privacy | ✅ Kept | Reference for visual parity |
| segnalazione-02-dati | ✅ Kept | Reference for visual parity |
| segnalazione-03-riepilogo | ✅ Kept | Reference for visual parity |
| segnalazione-04-conferma | ✅ Kept | Post-submit confirmation page |
| segnalazione-crea | ✅ New | Unified wizard page |

---

## Widget Details

### Class: `CreateTicketWizardWidget`
**Namespace**: `Modules\Fixcity\Filament\Widgets`
**Extends**: `XotBaseWizardWidget`
**NOT**: ~~`CreateSegnalazioneWizardWidget`~~ (incorrect naming)

### Boundary With `TicketForm`

Il tema Sixteen governa layout, CSS Design Comuni e parity visuale. Non e' owner dei campi del wizard.

Il contratto corretto e':
- `TicketForm` e' la sorgente unica dello schema ticket: privacy, dati, summary.
- `CreateTicketWizardWidget` delega a `TicketForm` per gli schemi e mantiene solo runtime frontoffice: `blockData`, submit/draft, payload, redirect e view wrapper.
- Sixteen non deve introdurre un nuovo file schema o duplicare campi in Blade per correggere il wizard.

### Key Properties
| Property | Type | Purpose |
|----------|------|---------|
| `$blockData` | array | CMS JSON configuration |
| `$wizardStartStep` | int | Initial step (from `?step=` query) |

**Form state**: managed via `$this->data` (`XotBaseWizardWidget` / `XotBaseWidget`) — NOT individual public properties.

### Wizard Schema (Filament v5)
Composed via `XotBaseWizardWidget::getFormSchema()` and widget-provided steps:
```php
Wizard::make([
    makeStepPrivacy(),   // Step 1: privacy notice statico + checkbox
    makeStepData(),      // Step 2: address, type, title, details, images, author
    makeStepSummary(),   // Step 3: summary read-only con Infolist entries
])
```

### Key Methods
| Method | Purpose |
|--------|---------|
| `mount()` | Init blockData, resolve step, fill defaults |
| `makeStepPrivacy()` | Build step 1 |
| `makeStepData()` | Build step 2 (all inputs) |
| `makeStepSummary()` | Build step 3 (read-only) via Infolist |
| `submit()` | Validate, create Ticket, redirect |
| `getSteps()` | Ordered wizard step list |

**REMOVED (old Blade-managed approach)**:
- ~~`nextStep()`~~ — Filament wizard handles navigation
- ~~`prevStep()`~~ — Filament wizard handles navigation
- ~~`removeImage()`~~ — Filament FileUpload handles this

### Governance updates

- No explicit `->label()` / `->tooltip()` customization on wizard actions in this widget: localization is managed by the project translation/auto-label stack.
- No local `Log::error()` in widget submit flow: user feedback is handled through field error + Filament notification.
- No locale literals in PHP runtime widget code: summary section labels/descriptions come from translation keys.
- Payload mapping for ticket creation follows explicit normalization contract (`trim`, `blank -> null`, enum fallback, type-safe owner resolution) and is not kept as inline cast soup.
- Geolocation action "use my location" must expose runtime feedback (spinner + busy state) to make async GPS/reverse-geocode progress visible.
- Data step parity must keep the same semantic grouping as Design Comuni reference: `luogo`, `disservizio`, `autore della segnalazione`; author block rendered as read-only infolist-style summary plus contact input.
- Step 2 implementation parity: use explicit `Section` grouping (`Luogo`, `Disservizio`, `Autore della segnalazione`) and keep author data visually read-only before contact edit fields.
- Data step visual parity must use `Section` as the primary grouping primitive and reserve `Infolist` for genuinely read-only structured data, so the local step keeps the same cognitive rhythm as `segnalazione-02-dati`.
- Visual cleanup rule: avoid nested collapsible cards inside `Autore della segnalazione` when they reduce readability; keep contact edit as direct field and style through Filament `fi-*` selectors (`fi-section`, `fi-section-content-ctn`, `fi-sc-text`).
- Final parity consolidation tracked in [7-50 segnalazione-crea step2 high html visual parity](../../../../_bmad-output/implementation-artifacts/7-50-segnalazione-crea-step2-high-html-visual-parity.md) for residual gaps and anti-regression guardrails.
- Ultra-parity follow-up tracked in [7-51 segnalazione-crea step2 columns header ultra parity](../../../../_bmad-output/implementation-artifacts/7-51-segnalazione-crea-step2-columns-header-ultra-parity.md), focused on left/center column width, vertical rhythm and header readability on the real step-2 URL.
- Scope 7-50 is page-level, not widget-only: left helper column width, center content width, vertical spacing density, and header readability are part of acceptance, because the Design Comuni reference is perceived as one page, not as an isolated card.
- HTML parity guardrail: avoid theme-level wrapper divs around the wizard mount point when they do not exist in the reference DOM (example removed: `segnalazione-crea-wrapper`).
- Summary step parity uses native Filament Infolists (`TextEntry`, `ImageEntry`) for read-only structured data; avoid fallback to generic placeholders for primary summary entries.
- Privacy/instructional copy is not a summary entry: for static notice content prefer Filament `Schemas` prime components (`Text`, `Image`, ...) instead of deprecated `Placeholder`.
- Stability gate for `segnalazione-crea`: run `composer run-script guard:fixcity-wizard` (from `laravel/`) plus HTTP smoke `curl -I --max-time 15 http://127.0.0.1:8000/it/tests/segnalazione-crea` after each wizard refactor.
- Privacy step parity: before `privacyAccepted` checkbox, show GDPR notice text block ("Il Comune di Firenze gestisce i dati personali...") with explicit privacy link, aligned with Design Comuni step 1 reference.
- Step 2 parity rule: `Section` governs visual hierarchy, `Infolist` governs read-only author data. Do not flatten the page into a single undifferentiated form card.

---

## Translation Pattern

All translations use: `fixcity::segnalazione.steps.<item>.<tipo>`

| Key | Value (IT) |
|-----|-----------|
| `fixcity::segnalazione.steps.privacy.label` | Privacy |
| `fixcity::segnalazione.steps.data.label` | Dati |
| `fixcity::segnalazione.steps.summary.label` | Riepilogo |
| `fixcity::segnalazione.steps.active.label` | Passo attivo |
| `fixcity::segnalazione.steps.confirmed.label` | Passo completato |

---

## Visual Parity

### Reference vs Local Comparison

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| h1 font-size | 48px | 48px | ✅ |
| h1 font-weight | 700 | 700 | ✅ |
| h1 color | rgb(25,25,25) | rgb(25,25,25) | ✅ |
| stepper color | rgb(0,122,82) | rgb(0,122,82) | ✅ |
| active stepper | rgb(0,122,82) | rgb(0,122,82) | ✅ |
| form-check margin-top | 40px | 40px | ✅ |
| form-check margin-bottom | 40px | 40px | ✅ |
| privacy label font-size | 18px | 18px | ✅ |
| privacy label color | rgb(26,26,26) | rgb(26,26,26) | ✅ |

### CSS Fixes Applied

1. **Parity scoping**: use stable wizard/theme hooks such as `.ticket-wizard-root` and `.tests-route`, not slug-coupled selectors
2. **Stepper colors**: Fixed to green `#007A52` (not blue `#17334f`)
3. **Form check margins**: Fixed to `40px` (was `24px/16px`)
4. **Privacy label**: Fixed to `18px` (was `14px`)
5. **H1 font-size**: Fixed to `48px` (was `40px`)

### Files Modified

| File | Changes |
|------|---------|
| `segnalazione-parity.css` | Added `.page-tests-segnalazione-crea` styles |
| `main.blade.php` | Added dynamic body class for test pages |

---

## Data Flow

```
User fills Step 1 (Privacy)
  → nextStep() validates privacyAccepted
    → User fills Step 2 (Data)
      → nextStep() validates address, issueType, title, details
        → User reviews Step 3 (Summary)
          → submit() validates all + creates Ticket
            → redirect to /it/tests/segnalazione-04-conferma
```

### Data Persistence
- Filament/Livewire handles state between steps automatically
- No manual session/storage needed
- Widget properties persist across Livewire requests

---

## Screenshots

| File | Description |
|------|-------------|
| `reference-screenshot.png` | Reference page (segnalazione-01-privacy) |
| `local-screenshot.png` | Pre-fix segnalazione-crea |
| `local-screenshot-after.png` | Post-fix segnalazione-crea |

---

## Related Documentation

- [Widget Source](../../../laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php)
- [Widget View](../../../laravel/Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php)
- [Page JSON](../../../laravel/config/local/fixcity/database/content/pages/tests.segnalazione-crea.json)
- [Page Blade](../../../laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-crea.blade.php)
- [Wizard governance story](../../../../Modules/Fixcity/docs/stories/wizard-governance-langserviceprovider-and-xotbase-refactor.md)

---

*Report created: 2026-04-09*  
*Maintained by: AI Agents + Development Team*
