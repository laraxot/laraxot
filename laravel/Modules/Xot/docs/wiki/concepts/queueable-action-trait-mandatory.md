---
title: "QueueableAction trait mandatory"
type: concept
module: Xot
tags: [xot, queueable-action, spatie, actions, trait]
created: 2026-07-12
updated: 2026-07-12
qmd: "Xot QueueableAction trait mandatory every class app Actions execute"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../../../docs/wiki/rules/queueable-action-trait-mandatory.md
  - ../queueable-actions.md
  - ../../../Activity/docs/wiki/concepts/queueable-action-execute-entrypoint.md
---

# QueueableAction — trait obbligatorio (Xot owner)

Modulo **Xot** owns `spatie/laravel-queueable-action`. Ogni modulo/tema eredita il pattern.

## Checklist nuova Action

1. File in `app/Actions/{Dominio}/FooAction.php`
2. `use Spatie\QueueableAction\QueueableAction;` + `use QueueableAction;`
3. Un solo `execute(...)` pubblico
4. Chiamata: `app(FooAction::class)->execute(...)`
5. Audit: `bash bashscripts/tools/audit-queueable-action-trait.sh`

## Non mettere in `app/Actions/`

| Tipo | Path canonico |
|------|----------------|
| Facade coordinator | `app/Adapters/` |
| Interface adapter | `app/Adapters/` |
| Enum | `app/Enums/` |
| DTO / registry costanti | `app/Datas/` |

**`app/Support/` eliminato monorepo (2026-07-12).** Esempi Activity: `Adapters/ActivityLogger`, `Adapters/ActivityRecorder`.

## Wiki progetto

[queueable-action-trait-mandatory.md](../../../../../../docs/wiki/rules/queueable-action-trait-mandatory.md)
