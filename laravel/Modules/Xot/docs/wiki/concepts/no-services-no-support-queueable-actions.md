---
title: "No Services / No Support — QueueableAction only"
type: concept
module: Xot
tags: [xot, services, support, actions, queueable-action, migration, adapter]
created: 2026-07-13
updated: 2026-07-13
qmd: "Xot module Services and Support banned use app Actions Adapters QueueableAction policy"
related:
  - no-app-support-queueable-actions.md
  - xot-services-support-to-actions.md
  - queueable-action-trait-mandatory.md
---

# Xot — Services/Support vietati: solo Actions e Adapters

## Regola

- **Mai** creare file in `app/Services/` o `app/Support/`
- **Logica singola** → `app/Actions/{Contexto}/FooAction.php`
- **Multi-metodo binding** → `app/Adapters/{Dominio}/FooAdapter.php`
- **Trait**: `use Spatie\QueueableAction\QueueableAction;`
- **Entrypoint**: unico metodo `execute(...)`
- **Chiamata**: `app(FooAction::class)->execute(...)`
- **Gruppi**: sottocartelle per attore/contesto (es. `Actions/Route/`, `Actions/Html/`)

## Conversione

Vedi [xot-services-support-to-actions.md](xot-services-support-to-actions.md) e [no-app-support-queueable-actions.md](no-app-support-queueable-actions.md) per mapping dettagliato.
