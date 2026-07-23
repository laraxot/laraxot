# Wizard Step Visibility Mechanism

## Documentazione Tecnica - Perché il 1° Step Rimane Visibile

### Il Problema Osservato

Quando il wizard Filament viene renderizzato, tutti gli step (`<div class="fi-sc-wizard-step">`) sono visibili nel DOM e il primo step non viene mai nascosto correttamente.

### La Causa Root

Il CSS Filament nativo in `vendor/filament/schemas/resources/css/components/wizard.css` (linee 126-132) usa questa regola:

```css
.fi-sc-wizard .fi-sc-wizard-step {
    outline: none;
}

.fi-sc-wizard .fi-sc-wizard-step:not(.fi-active) {
    visibility: hidden;
    position: absolute;
    height: 0;
    overflow: hidden;
    padding: 0;
}
```

Questa regola funziona **solo se** lo step ha la classe `.fi-active`. Ma nel nostro Sixteen theme, lo step **NON** ottiene questa classe perché:

1. **Filament Wizard component** renderizza gli step in un loop `{{ $step }}` nel blade
2. **Alpine.js** gestisce la visibilità via `x-show` ma solo sullo step attivo
3. **Il CSS parity** del Sixteen theme **NASconde** il CSS Filament nativo (linee 11-16 di `filament-wizard-parity.css`):

```css
.fi-sc-wizard .fi-wiz-steps,
.fi-sc-wizard .fi-fo-wizard-header,
.fi-sc-wizard .fi-fo-wizard-step-header {
    display: none !important;
}
```

Questo nasconde `.fi-wiz-steps` (contenitore nativo Filament) e mostra solo lo stepper custom e i contenuti.

### L'Architettura Corretta

#### 1. Wizard Blade Template (`components/wizard.blade.php`)

```php
// Lo stepper custom (Design Comuni)
<x-pub_theme::wizard.stepper :steps="$steps" :total-steps="count($steps)" />

// Il contenuto di OGNI step viene renderizzato
@foreach ($steps as $step)
    {{ $step }}  // Renderizza lo schema dello step
@endforeach
```

#### 2. Alpine.js Wizard Component

L'Alpine component `wizardSchemaComponent` gestisce lo stato:

```javascript
wizardSchemaComponent({
    isSkippable: @js($isSkippable()),
    isStepPersistedInQueryString: @js($isStepPersistedInQueryString()),
    key: @js($key),
    startStep: @js($getStartStep()),
    stepQueryStringKey: @js($getStepQueryStringKey()),
})
```

- `step` = stato interno che tiene traccia dello step corrente
- `getStepIndex(step)` = funzione che restituisce l'indice numerico
- Lo step viene aggiornato da `goToStep()`, `goToNextStep()`, ecc.

#### 3. Lo Stepper Custom (`components/wizard/stepper.blade.php`)

```php
<li
    class="step-item"
    x-bind:class="{
        'active': getStepIndex(step) === {{ $index }},
        'confirmed': getStepIndex(step) > {{ $index }},
    }"
>
    <button x-on:click="step = @js($stepKey)">
        <span class="step-icon">{{ $stepNum }}</span>
        <span class="step-title">{{ $stepLabel }}</span>
    </button>
</li>
```

### La Regola CSS Critica

Per far funzionare il tutto, abbiamo bisogno di questa regola in `filament-wizard-parity.css`:

```css
/* CRITICO: Nascondi TUTTI gli step che non sono attivi */
.fi-sc-wizard .fi-sc-wizard-step {
    display: none !important;
}

/* Solo lo step attivo viene mostrato */
.fi-sc-wizard .fi-sc-wizard-step.fi-active {
    display: block !important;
}
```

**Senza questa regola**: Tutti gli step sono visibili e si accumulano verticalmente.

### Flusso di Render Completo

```
1. Widget::form() → Wizard::make()
                    ↓
2. Wizard::render() → wizard.blade.php
                    ↓
3. blade:$steps = $getChildSchema()->getComponents()
                    ↓
4. @foreach($steps as $step) → {{ $step }}
                    ↓
5. Ogni $step.render() → HTML del contenuto form
                    ↓
6. Alpine:x-data="wizardSchemaComponent(...)"
                    ↓
7. JavaScript gestisce visibilità via step state
                    ↓
8. CSS .fi-sc-wizard-step.fi-active { display: block }
                    ↓
9. Solo lo step corrente è visibile
```

### La Bellezza del Codice

#### 1. **Separazione View/Logic**

Il blade non contiene logica business — solo markup. Sul widget PHP: stato step (`wizardStartStep`, query `step` dove consentito), e contratto **`getSteps()`** che incapsula gli `Step` Filament (delega spesso allo schema tipo `TicketForm::getSteps()`).

#### 2. **Trait Laraxot, non trait pagina Resource**

`XotBaseWizardWidget` **`use`** `Filament\Resources\Pages\Concerns\HasWizard` sulla catena **`XotBaseWidget::form()`**, ove serve trait **`DelegatesFilamentWizardSchemaMethods`** per `wire:click` programmatici sulla Blade tema; il salvataggio nel widget dominio (**es.** `CreateTicketWizardWidget::submit`) usa **`$this->form->getState()`** senza helper di appiattimento sulla base.

Motivazione breve:

- Pagina Filament-panel: lifecycle `parent::form()` + azioni cancellazione pagina — **non disponibili** sul widget tema.
- Frontoffice/widget: tema `pub_theme::filament.wizard.submit-button` + `submit` Livewire pubblico sul widget dominio.

#### 3. **State Management via Alpine**

Lo stato del wizard (`step`) è interamente gestito da Alpine.js:
- Nessuna comunicazione server per cambiare step
- Transizioni fluide
- URL persistence opzionale

#### 4. **CSS Visibility vs Display**

Filament usa `visibility: hidden; position: absolute; height: 0` che:
- Mantiene il DOM per accessibilità
- Nasconde visivamente ma preserva la struttura
- Permette transizioni animate

Noi usiamo `display: none` che:
- Rimuove completamente dal layout
- Più performante per form complessi
- Ma perde accessibilità (screen reader)

### Best Practice per il Future

Se vuoi che lo step sia accessibile da screen reader anche quando non attivo:

```css
/* Alternativa migliore per accessibilità */
.fi-sc-wizard .fi-sc-wizard-step {
    visibility: hidden;
    position: absolute;
    height: 0;
    overflow: hidden;
}

.fi-sc-wizard .fi-sc-wizard-step.fi-active {
    visibility: visible;
    position: static;
    height: auto;
}
```

Ma per ora `display: none` funziona perché i campi form sono comunque in accordion/raggruppati.

### Aggiornamento 2026-05-22 — form annidate sul widget (blocco visibilità / step mancanti)

**Non** avvolgere `{{ $this->form }}` in un `<form wire:submit>` nel Blade del widget se gli step Filament usano `Step` con `hasFormWrapper` (ogni step è un `<form class="fi-sc-wizard-step">`). HTML non consente form annidate: il browser corregge il DOM, spesso **perdendo** step o impedendo ad Alpine di applicare `fi-active` — risultato pannello vuoto e `visibility: hidden` fisso.

Soluzione: contenitore **`div`** (`create-ticket-wizard.blade.php` in **Sixteen** e fallback **Fixcity**). Il submit resta sui pulsanti Filament del footer wizard.

Fallback CSS in `resources/css/components/filament-wizard-parity.css`: se **nessuno** step ha `.fi-active` (race Alpine), mostra il **primo** pannello grazie a `:not(:has(.fi-sc-wizard-step.fi-active))` + `:first-of-type`.

### Riferimenti

- `vendor/filament/schemas/resources/css/components/wizard.css` (wizard step concealment / visibility)
- `resources/views/filament/widgets/create-ticket-wizard.blade.php` (nessun `<form>` esterno intorno allo schema wizard)
- `laravel/Themes/Sixteen/resources/views/components/wizard.blade.php:56-58`
- `laravel/Themes/Sixteen/resources/css/components/filament-wizard-parity.css:113-120`