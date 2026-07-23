# Filament Multiple Forms

## Overview
Filament 5 permette di gestire **form multiple indipendenti** all'interno dello stesso componente Livewire. Ogni form ha il proprio stato, regole di validazione e gestori di submit, identificato da un nome univoco.

## Concetti chiave
- **Form ID** – Specificare un nome con `Form::make('myForm')` (o `->name('myForm')`) per separare i payload.
- **Isolamento dello stato** – Livewire tiene separati i dati di ciascun form, evitando collisioni di campo.
- **Validazione** – `$this->getForm('myForm')->validate()` valida solo quel form.
- **Submit** – `$this->getForm('myForm')->submit()` o un'azione personalizzata che opera sullo stato di quel form.
- **Rendering** – Nel Blade, utilizzare `{{ $this->form('myForm') }}` oppure `$this->form('myForm')` all'interno di un widget Filament.

## Pattern tipico
```php
use Filament\Forms\Components\Form;
use Filament\Forms\Components\TextInput;

public function getForms(): array
{
    return [
        Form::make('contact')
            ->schema([
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('message')
                    ->required(),
            ]),
        Form::make('subscribe')
            ->schema([
                TextInput::make('newsletter_email')
                    ->email()
                    ->required(),
            ]),
    ];
}

public function submitContact(): void
{
    $this->getForm('contact')->validate();
    // gestisci dati contatto
}

public function submitSubscribe(): void
{
    $this->getForm('subscribe')->validate();
    // gestisci iscrizione
}
```

## Integrazione con i wizard
Se un wizard richiede form isolati per step (es. step 1: dati, step 2: riepilogo), dichiarare ogni step come `Form::make('stepX')`. La navigazione può chiamare `$this->getForm('stepX')->validate()` prima di avanzare.

## Riferimenti
- Documentazione ufficiale: https://filamentphp.com/docs/5.x/components/form#using-multiple-forms
- Implementazione di esempio in **Fixcity**: vedi `CreateTicketWizardWidget` dove gli step del wizard sono gestiti separatamente; è possibile trasformarli in form indipendenti per una logica più chiara.
