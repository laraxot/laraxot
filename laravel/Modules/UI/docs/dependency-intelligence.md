---
title: "Dependency Intelligence - Module UI"
type: concept
tags: [dependency, intelligence]
created: 2026-07-14
updated: 2026-07-14
qmd: "dependency-intelligence dependency intelligence - module ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Dependency Intelligence - Module UI

Aggiornato da `composer show` il 2026-03-02.

## Runtime dependencies (`require`)

| Package | Constraint | Installed | Area |
|---|---|---|---|
| `owenvoke/blade-fontawesome` | `*` | `v2.9.1` | `application-support` |

## Dev dependencies (`require-dev`)

| Package | Constraint | Installed | Area |
|---|---|---|---|
| _(none)_ | - | - | - |

## Declared but missing from installed set

- Nessun pacchetto mancante nel lock corrente.

## Workspace critical runtime versions

- `laravel/framework`: `v12.52.0`
- `laravel/folio`: `v1.1.12`
- `livewire/livewire`: `v4.1.4`
- `livewire/volt`: `v1.10.2`
- `filament/filament`: `v5.2.1`
- `nwidart/laravel-modules`: `v12.0.4`
- `calebporzio/sushi`: `v2.5.3`
- `mcamara/laravel-localization`: `v2.3.0`
- `spatie/laravel-data`: `4.19.1`
- `spatie/laravel-queueable-action`: `2.16.2`

## Chaos monkey focus points

- Verificare breaking changes su dipendenze `admin-ui` (Filament/Livewire) prima di toccare pagine o widget.
- Verificare coerenza tra package lock e vincoli modulo dopo merge di `Modules/*/composer.json`.
- Se un modulo ha `require` vuoto, i rischi runtime arrivano soprattutto da dipendenze transitivamente fornite da Xot/app root.

## Deep Study References

- [Composer packages study](../../../../docs/architecture/composer-packages-study.md)
- [Composer packages full inventory](../../../../docs/architecture/composer-packages-full-inventory.md)
