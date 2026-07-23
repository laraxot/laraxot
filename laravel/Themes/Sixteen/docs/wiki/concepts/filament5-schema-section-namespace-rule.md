---
title: Filament 5 Schema Section Namespace Rule For Sixteen Views
type: concept
tags: [sixteen, filament, schemas, blade, wizard]
created: 2026-04-22
updated: 2026-04-22
sources:
  - ../../../../../docs/wiki/concepts/filament5-schema-section-namespace-rule.md
  - https://filamentphp.com/docs/5.x/components/form#using-multiple-forms
---

# Filament 5 Schema Section Namespace Rule For Sixteen Views

## Regola

Le Blade del tema Sixteen non devono compensare errori di namespace Filament nel widget. Se una view renderizza un form Filament con:

```blade
{{ $this->form }}
```

il componente Livewire deve esporre uno schema chiamato `form`, con stato inizializzato e namespace corretti nel PHP owner.

## Applicazione Segnalazione Crea

`resources/views/filament/widgets/create-ticket-wizard.blade.php` e' solo wrapper Design Comuni:

- titolo e descrizione pagina;
- `<form wire:submit="submit">`;
- `{{ $this->form }}`;
- modals actions.

Le sezioni del wizard appartengono allo schema PHP del modulo Fixcity e devono usare `Filament\Schemas\Components\Section`.
