# Filament 5 Widget Schema Submit Bridge Rule

## Regola Tema

Il tema Sixteen puo' rendere bottoni e layout Design Comuni per un wizard, ma non deve cambiare il contratto dati del widget Filament.

Quando un bottone Blade usa `wire:click="submit"` deve chiamare un metodo del widget che legge lo stato da:

```php
$this->form->getState()
```

Non da:

```php
$this->getForm('form')
```

## Perche

`XotBaseWidget` e `XotBaseWizardWidget` sono schema-based (`InteractsWithSchemas`). La proprieta dinamica usata dalla Blade e' `$this->form`, coerente con:

```blade
{{ $this->form }}
```

La view del tema non deve assumere `InteractsWithForms` o multiple forms legacy. Se lo fa, il primo POST Livewire sul submit puo' fallire con `BadMethodCallException`.

## Applicazione su segnalazione-crea

Nel riepilogo del wizard:

- il DOM puo' mostrare una CTA Design Comuni (`Conferma e invia`);
- la CTA e' solo un bridge visuale;
- l'owner dello stato resta il widget schema-based;
- la view non deve duplicare validation/state management.

## Relazione con la parity Design Comuni

La visual parity va ottenuta con:

- markup semantico riusabile;
- classi Bootstrap Italia / Design Comuni;
- CSS component/site-wide del tema.

Non va ottenuta creando un comportamento speciale per `ticket-wizard-root` o per una singola pagina.

