---
title: "no app/Support — business logic in QueueableAction"
type: concept
tags: [ui, actions, queueable-action, support, refactor, adapter]
created: 2026-07-12
updated: 2026-07-22
qmd: "UI module no app Support queueable action no map adapters"
issues:
discussions:
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

# no `app/Support/` — business logic in QueueableAction

## Scopo

Nel modulo UI **non** esiste più `app/Support/`.

## Migrazione (2026-07-12 → 2026-07-22)

`app/Support/` e `app/Services/` eliminati. I null-object Map/Location **non** restano in UI: dominio Geo — vedi [geo-boundary.md](../../geo-boundary.md).

| Legacy | Esito |
|--------|--------|
| `Support/Map/Null*` / `Services/Map/Null*` / `Adapters/Map/*` | **Eliminati** da UI (2026-07-22) |
| `UIService::asset()` | `AssetAction` (Xot) |
| `ThemeService`, `ComponentService` | Eliminati |

## Collegamenti

- [block-rendering-and-optional-services.md](block-rendering-and-optional-services.md)
- [queueable-action-trait-mandatory](../../../../docs/wiki/rules/queueable-action-trait-mandatory.md)
