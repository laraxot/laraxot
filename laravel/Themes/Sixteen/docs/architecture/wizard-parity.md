# Architettura Tema Sixteen - Wizard Parity

## Obiettivo

Garantire che tutti i componenti Wizard di Filament seguano fedelmente il design system "Design Comuni" (Bootstrap Italia) quando visualizzati nel frontoffice.

## Strategia di Implementazione

La parità visiva è ottenuta attraverso una combinazione di view Blade personalizzate e CSS scoped:

### 1. View Blade (`resources/views/components/wizard.blade.php`)

Questa view sostituisce il template di default di Filament per il componente Wizard. Le sue responsabilità sono:
*   Includere lo stepper personalizzato di Bootstrap Italia (`x-pub_theme::wizard.stepper`).
*   Configurare il componente Alpine.js `wizardSchemaComponent` di Filament.
*   Rendere le azioni (bottoni) secondo lo stile del tema.

**Requisiti per il Widget**:
La view richiede che il widget Livewire implementi il metodo `getWizardDisplayStep()` per ottenere l'indice dello step corrente.

### 2. CSS Parity (`resources/css/components/filament-wizard-parity.css`)

Questo file contiene i selettori CSS necessari per mappare il markup generato da Filament sulle classi di Bootstrap Italia.

**Regole di Scoping**:
*   Utilizzare il selettore `.fi-sc-wizard` per applicare gli stili a TUTTI i wizard del tema.
*   Evitare selettori specifici per singole pagine o feature (es. `.ticket-wizard-root`).
*   Utilizzare variabili CSS (es. `--dc-green`) per garantire coerenza con il resto del tema.

### 3. Steppers e Navigazione

Lo stepper segue la struttura di Design Comuni:
*   `.steppers`: Contenitore principale.
*   `.step-list`: Lista degli step con icone (numeri o checkmark per completati).
*   `.step-title`: Label dello step.
*   `.step-divider`: Linea di collegamento tra gli step.

## Meccanismo Visibility Step

Vedi [[wizard-step-visibility]] per la documentazione completa su come gli step vengono mostrati/nascosti.

**Regola Critica**:
```css
.fi-sc-wizard .fi-sc-wizard-step {
    display: none !important;
}
.fi-sc-wizard .fi-sc-wizard-step.fi-active {
    display: block !important;
}
```

Senza questa regola, tutti gli step sono visibili nel DOM.

## Layout Design Comuni (2026-05-14)

Il form del wizard DEVE essere narrow, non full-width, per matchare il reference Design Comuni:
https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html

**Struttura corretta**:
```blade
<div class="container wizard-dc-form-shell">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <form wire:submit="submit">
                {{ $this->form }}
            </form>
        </div>
    </div>
</div>
```

**CSS corrispondente**:
```css
.wizard-dc-form-shell {
    max-width: 100%;
    padding-left: 1rem;
    padding-right: 1rem;
}

.wizard-dc-form-shell .col-lg-8,
.wizard-dc-form-shell .col-xl-7 {
    max-width: 720px;
    margin: 0 auto;
}

.fi-sc-wizard form,
.wizard-dc-form-shell form {
    max-width: 720px;
    margin: 0 auto;
}
```

## Regola CSS Globale (2026-05-14)

**Mai usare classi page-specifiche** come `.fixcity-wizard-form` - usare sempre `.fi-sc-wizard` (selettore globale Filament).

**Regola**:
- `.fi-sc-wizard` = TUTTI i wizard Filament (global scope)
- `.wizard-dc-form-shell` = container generico Design Comuni

**NO**:
- `.fixcity-wizard-form`
- `.ticket-wizard-root`
- Qualsiasi classe page-specifica

## Stepper Design Comuni (2026-05-14)

Il stepper deve matchare la struttura Design Comuni reference:

```blade
<div class="steppers">
    <div class="steppers-header">
        <ul class="steppers-list">
            <li class="stepper-step">Autorizzazioni e condizioni</li>
            <li class="stepper-step">Dati di segnalazione</li>
            <li class="stepper-step">Riepilogo</li>
        </ul>
        <span class="steppers-index">1/3</span>
    </div>
</div>
```

**File**:
- `resources/views/components/wizard/stepper.blade.php`

## GDPR Notice (2026-05-14)

Lo step privacy DEVE mostrare il testo GDPR prima del checkbox:

```
Il Comune di [Nome] gestisce i dati personali forniti e liberamente comunicati sulla base dell'articolo 13 del Regolamento (UE) 2016/679 General Data Protection Regulation (GDPR)...
```

**Traduzioni**: `Modules/Fixcity/lang/it/segnalazione.php` → `gdpr_notice.*`

**File**:
- `Modules/Fixcity/app/Filament/Resources/TicketResource/Schemas/TicketForm.php`

## Manutenzione

Tutte le modifiche globali allo stile dei wizard devono essere effettuate in `filament-wizard-parity.css`. Non aggiungere stili specifici per i wizard in `app.css` o in file CSS dedicati a singole pagine.

## Layout Design Comuni (2026-05-14)

Il form del wizard DEVE essere narrow, non full-width, per matchare il reference Design Comuni:
https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html

**Struttura corretta**:
```blade
<div class="container wizard-dc-form-shell">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <form class="fixcity-wizard-form">
                {{ $this->form }}
            </form>
        </div>
    </div>
</div>
```

**CSS corrispondente**:
```css
.wizard-dc-form-shell {
    max-width: 100%;
    padding-left: 1rem;
    padding-right: 1rem;
}

.wizard-dc-form-shell .col-lg-8,
.wizard-dc-form-shell .col-xl-7 {
    max-width: 720px;
    margin: 0 auto;
}

.fixcity-wizard-form {
    max-width: 720px;
    margin: 0 auto;
}
```

**File modificati**:
| File | Modifica |
|------|----------|
| `resources/views/filament/widgets/create-ticket-wizard.blade.php` | Narrow form layout |
| `resources/css/components/filament-wizard-parity.css` | .wizard-dc-form-shell |

## Aggiornamenti (2026-05-14)

### CSS Miglioramenti per Form Fields

**Problema**: I campi input/select/textarea apparivano trasparenti, rendendo impossibile distinguere il tipo di campo.

**Soluzione** (in `filament-wizard-parity.css`):

```css
/* Forza sfondo opaco su tutti i campi */
.fi-sc-wizard select,
.fi-sc-wizard input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]):not([type="reset"]),
.fi-sc-wizard textarea {
    background-color: #ffffff !important;
    border: 1px solid #5c6f82 !important;
    color: #17324d !important;
    border-radius: 4px !important;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05) !important;
}

/* Select con freccia dropdown visibile */
.fi-sc-wizard select {
    appearance: auto !important;
    -webkit-appearance: auto !important;
    background-image: url("data:image/svg+xml,...") !important;
    background-position: right 0.5rem center !important;
}
```

### Wizard Stepper Miglioramenti

```css
/* Active step icon */
.fi-sc-wizard .step-item.active .step-icon {
    background-color: #007a52 !important;
    color: #ffffff !important;
    border: 2px solid #007a52 !important;
    box-shadow: 0 2px 4px rgba(0, 122, 82, 0.3) !important;
}

/* Step divider */
.fi-sc-wizard .step-divider {
    background-color: #d9e1e8 !important;
    height: 2px !important;
}
```

### Map Full-Width in Wizard

```css
.fixcity-wizard-form .map-container-wrapper,
.fixcity-wizard-form .coordinate-picker-field-wrapper {
    width: 100% !important;
    max-width: 100vw !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}
```

### File Modificati

| File | Modifica |
|------|----------|
| `resources/css/components/filament-wizard-parity.css` | Form fields opacity, stepper visual, map full-width |
| `resources/views/filament/widgets/create-ticket-wizard.blade.php` | Wizard shell layout |
