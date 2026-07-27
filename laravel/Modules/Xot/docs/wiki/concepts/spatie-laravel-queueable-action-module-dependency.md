---
title: "spatie/laravel-queueable-action — owner e naming Packagist"
type: concept
module: Xot
tags: [xot, spatie, queueable-action, composer, dependency, actions]
created: 2026-07-27
updated: 2026-07-27
qmd: "xot spatie laravel queueable action composer dependency owner Comment Billing Fiscal never queueable-action typo"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - ../../../../../../docs/wiki/rules/composer-module-dependency-go.md
  - ../../../../../../docs/wiki/concepts/composer-go-destructive-workflow.md
  - ../../composer-module-dependency-management.md
  - ../../actions/queueable-actions-overview.md
---

# spatie/laravel-queueable-action

## Perché

Le **Actions** Laraxot estendono `Spatie\QueueableAction\QueueableAction` per esecuzione sync/async via queue. È infrastruttura condivisa → owner primario **Xot**, consumer espliciti nei moduli che ne dipendono.

## Naming (DRY — una sola verità)

| Contesto | Nome corretto |
|----------|---------------|
| Packagist / `composer.json` | **`spatie/laravel-queueable-action`** |
| Namespace PHP | `Spatie\QueueableAction\…` |
| Repo GitHub | [spatie/queueable-action](https://github.com/spatie/queueable-action) |

**Vietato** in `require`: `spatie/queueable-action` — Composer non lo risolve (`could not be found`).

## Dove

| Modulo | `composer.json` |
|--------|-----------------|
| **Xot** | `"spatie/laravel-queueable-action": "*"` |
| Comment | `"^2.16"` |
| Billing, Fiscal | `"*"` |

Merge → `laravel/vendor/spatie/laravel-queueable-action`.

## Workflow dopo bump

```bash
rm -rf laravel/Modules/Xot/vendor laravel/Modules/Comment/vendor
cd laravel && php -d memory_limit=-1 composer.phar update -W
```

Stack completo (solo se serve publish/migrate): `composer go` — vedi [composer-go-destructive-workflow.md](../../../../../../docs/wiki/concepts/composer-go-destructive-workflow.md).

## Collegamenti

- [composer-module-dependency-go.md](../../../../../../docs/wiki/rules/composer-module-dependency-go.md)
- `.cursor/rules/composer-module-dependency-go.mdc`
