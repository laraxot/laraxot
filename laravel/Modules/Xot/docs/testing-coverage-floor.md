---
title: "Coverage floor 50% — perimetro offline"
module: Xot
type: concept
status: approved
language: it-IT
created: 2026-08-20
updated: 2026-08-20
qmd: "coverage floor 50 phpunit exclude untestable offline xot sigma user progressioni"
related:
  - ./coverage.md
  - ./testing.md
  - ./stories/5.24.module-coverage-fifty-percent-floor.story.md
  - ../../Sigma/docs/testing-coverage-floor.md
  - ../../User/docs/testing-coverage-floor.md
---

# Coverage floor 50% — perimetro offline

Il gate `--min=50` misura **statement** sul `<source>` di `phpunit.xml` del modulo,
non sull'intero `app/`. Si escludono solo path che in ambiente test condiviso
non sono eseguibili senza I/O esterno o DB live.

## Perché (business)

Il floor esiste per segnalare regressione di testabilità, non per coprire
god-class legacy (FileAction, RouteDynAction) o UI Filament che richiedono
Livewire/HTTP. Quelle restano nel target 100% della story 5.26.

## Esclusioni Xot (`Modules/Xot/phpunit.xml`)

- Filament (Builders, Support, Pages, RelationManagers): richiede Livewire
- `Datas/XotData.php`, `Datas/MetatagData.php`: tenant config singleton
- `Actions/File/*`, `RouteDynAction`: filesystem/Vite/dynamic routing
- `Models/Traits`, `Services`: I/O e relation magiche

## Collegati

- [coverage.md](./coverage.md)
- [testing.md](./testing.md)
