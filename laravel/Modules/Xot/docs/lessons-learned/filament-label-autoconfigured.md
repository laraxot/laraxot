---
name: filament-label-autoconfigured
description: "Mai ->label() esplicito sui componenti Filament: AutoLabelAction lo sovrascrive comunque via LangServiceProvider"
metadata:
  type: lesson-learned
  created: 2026-09-15
  github_issues: []
---

# Le label Filament sono autoconfigurate, mai `->label()` esplicito

## L'errore che si è ripetuto

```php
// SBAGLIATO
OrderColumn::make('order_column')
    ->label('Order')
```

## Perché è sbagliato

`Modules\Lang\Providers\LangServiceProvider::registerFilamentLabel()` registra
`configureUsing()` su Field/Column/Entry/Section/Action/Step, che invoca
`AutoLabelAction::execute()`. Questa action cerca sempre
`trans("<modulo>::<key>.fields.<campo>.label")`:

- se la traduzione **esiste**, sovrascrive incondizionatamente con
  `$component->label(...)` — la tua chiamata esplicita viene ignorata;
- se **manca**, non tocca il componente, che resta con il default Filament
  (`Str::headline($name)`, es. `order_column` → `"Order column"`).

In entrambi i casi `->label('Order')` è dead code: o viene sovrascritto, o non serviva
perché il default automatico si applica comunque.

## Come si fa correttamente

Non chiamare mai `->label()` sui componenti Filament in questo progetto. Se la label
mostrata non è quella desiderata, il fix è nella traduzione
(`lang/it/<file>.php` → `fields.<campo>.label`), non nel componente.

## Come riconoscerlo in futuro

```bash
rg "->label\(" laravel/Modules/*/app/Filament/ --include="*.php"
```

Ogni risultato è un candidato da rimuovere, salvo verificarne il contesto (raro: alcuni
componenti non passano da `configureUsing()`).

## Riferimenti

- Memory: `label-autoconfigured-lang-service-provider.md`
- Commit `Modules/UI` `e0d770b0`
