---
title: "Copy-then-normalize — replica singolare"
type: concept
tags: [gestionale-commesse, copy, normalize, replica, singular]
created: 2026-07-23
updated: 2026-07-23
qmd: "copy then normalize replica singular AiAssistant Auth Document WorkOrder"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./module-mapping.md"
  - "../wiki/concepts/module-structure-laraxot-canon.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
---

# Copy-then-normalize (fase replica)

> Canon fase: [replica-singular-phase.md](./replica-singular-phase.md).  
> Si **replicano** i moduli SRC; unica differenza di nome = **singolare**.

## Fase A — Copia → cartella singolare

```bash
SRC=/var/www/_bases/gestionale_commesse/Modules
DST=/var/www/_bases/base_workorder_fila5/laravel/Modules
# Esempio: plurale → singolare
rsync -a --exclude node_modules --exclude vendor "$SRC/WorkOrders/" "$DST/WorkOrder/"
rsync -a --exclude node_modules --exclude vendor "$SRC/AiAssistant/" "$DST/AiAssistant/"
# Auth, HR, Email, … → stesso nome (già singolare)
```

Poi: `modules_statuses.json` (`false` finché normalize+PHPStan), `composer.json` PSR-4, `composer dump-autoload`.

## Fase B — Normalize Laraxot

1. PSR-4 `"Modules\\Name\\": "app/"`
2. Spostare Domain/Application/Infrastructure → `app/`
3. Namespace + XotBase* (Resource/Provider/Model)
4. Root hygiene; `docs/` + `README.md`
5. PHPStan L10; Pest smoke
6. Aggiornare [module-mapping.md](./module-mapping.md)

## Non in questa fase

| Vietato ora | Perché |
|---|---|
| Cancellare `AiAssistant` / `Auth` / `HR` / … | Sono **repliche**, non twin illegali |
| Merge forzato in `AI` / `User` / `Employee` / `Notify` / `Media` | Fase gap **successiva** |
| Lasciare enable `WorkOrders` + `WorkOrder` | Solo il **singolare** |

## Stato

| Step | Stato |
|---|---|
| Canon replica-singular | **done** |
| Copia / reshape in corso | vedi audit |
| Merge piattaforma | **backlog fase dopo** |
