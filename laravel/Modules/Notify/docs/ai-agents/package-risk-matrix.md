---
title: "Package Risk Matrix (2026-03-02)"
type: concept
tags: [package, risk, matrix]
created: 2026-07-14
updated: 2026-07-14
qmd: "package-risk-matrix package risk matrix (2026-03-02)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Package Risk Matrix (2026-03-02)

## Fonte
Analisi completa di `composer show --format=json` (312 pacchetti).

## Studio completo package-by-package
- `laravel/Modules/Xot/docs/composer-packages-full-catalog-2026-03-02.md`

## Critical path packages
- `laravel/framework`
- `filament/filament`
- `livewire/livewire`
- `livewire/volt`
- `laravel/folio`
- `nwidart/laravel-modules`
- `mcamara/laravel-localization`
- `spatie/laravel-data`
- `spatie/laravel-queueable-action`
- `spatie/laravel-translatable`

## Ownership map
- `theme/cms rendering`: Folio, Volt, Localization
- `admin runtime`: Filament, Xot base contracts
- `module boundaries`: Laravel Modules, Service Providers
- `actions/data contracts`: Spatie Data + QueueableAction
- `auth/security`: Passport, Socialite, OAuth/JWT libs

## Incident use
Durante Chaos Monkey, mappa il fault al package cluster prima di patchare codice applicativo.
