---
title: "Naming Conventions - Database Folders"
type: concept
tags: [naming, conventions]
created: 2026-07-14
updated: 2026-07-14
qmd: "naming-conventions naming conventions - database folders"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./phpstan-test-mai-escludere.md"
  - "./test-naming-pascalcase.md"
---

# Naming Conventions - Database Folders

## Regola Mandatoria
Tutte le cartelle all'interno di `database/` nei moduli devono essere rigorosamente in **minuscolo**:
- `database/factories`
- `database/migrations`
- `database/seeders`

## Razionale
Garantire la compatibilità cross-platform (Linux/Windows) e l'allineamento con gli standard di caricamento automatico di Laravel e Composer.

## Azioni Correttive
In caso di rilevamento di cartelle con iniziali maiuscole (es. `Factories`), rinominarle immediatamente e aggiornare i namespace nei file PHP contenuti.
