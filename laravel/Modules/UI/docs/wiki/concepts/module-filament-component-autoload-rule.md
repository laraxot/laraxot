---
title: "Module Filament Component Autoload Rule"
type: rule
tags: [module, filament, component, autoload]
created: 2026-07-14
updated: 2026-07-14
qmd: "module-filament-component-autoload-rule module filament component autoload rule"
<<<<<<< HEAD
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
=======
<<<<<<< HEAD
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
<<<<<<< HEAD
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
=======
=======
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
related:
  - "./auth-register-focus-loss-overlay.md"
  - "./block-rendering-and-optional-services.md"
  - "./claude-audit-static.md"
  - "./code-redundancy-ui.md"
  - "./context-overflow-prevention.md"
  - "./enum-select-best-practices.md"
  - "./enum-select-component.md"
  - "./enum-select-contract-and-false-friends.md"
---

# Module Filament Component Autoload Rule

## Regola

Nei moduli Laraxot i componenti PHP autoloadabili devono stare sotto `app/`. Per i componenti Filament del modulo UI il path corretto e':

`Modules/UI/app/Filament/Forms/Components/...`

Non usare path paralleli fuori da `app/` per classi namespaced `Modules\UI\...`.

## Perche'

<<<<<<< HEAD
L'errore recente su `EnumSelect` non era un problema del widget Fixcity ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
L'errore recente su `EnumSelect` non era un problema del widget <nome progetto> ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
L'errore recente su `EnumSelect` non era un problema del widget progetto corrente ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
=======
<<<<<<< HEAD
L'errore recente su `EnumSelect` non era un problema del widget Fixcity ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
<<<<<<< HEAD
L'errore recente su `EnumSelect` non era un problema del widget <nome progetto> ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
L'errore recente su `EnumSelect` non era un problema del widget progetto corrente ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
L'errore recente su `EnumSelect` non era un problema del widget <nome progetto> ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
L'errore recente su `EnumSelect` non era un problema del widget progetto corrente ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
=======
L'errore recente su `EnumSelect` non era un problema del widget <nome progetto> ma di autoload: il file era stato creato nel path sbagliato e Laravel non trovava la classe `Modules\UI\Filament\Forms\Components\EnumSelect`.
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Best Practices

- creare classi PHP del modulo solo sotto `app/` salvo convenzioni esplicite diverse
- verificare sempre namespace e PSR-4 insieme al path fisico
- dopo aggiunta/spostamento classe, fare un controllo rapido sulla route che la usa
- tenere i componenti Filament riusabili nel modulo owner, non duplicati in tema o modulo consumer

## Bad Practices

- mettere una classe namespaced `Modules\UI\...` in `Modules/UI/Filament/...`
- avere due copie della stessa classe in path diversi
- correggere il codice consumer quando il problema reale e' l'autoload
- introdurre workaround nel widget per compensare una classe non trovata

## False Friends

- `php -l` sul file non garantisce che Composer lo autoloaddi
- una classe "esiste nel repo" non significa che Laravel la possa risolvere
- il fatal su un widget consumer non implica che il bug sia nel consumer
- `optimize:clear` non basta se il file sta nel namespace/path sbagliato

## Check rapido

1. namespace coerente con il path sotto `app/`
2. file unico, nessun duplicato shadow
3. route reale che istanzia il componente
4. solo dopo, eventuale clear cache
