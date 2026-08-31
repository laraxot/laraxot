---
title: ide-helper refresh — models generate meta eloquent
type: chat-handoff
tags: [ide-helper, phpstan, bmad, multi-agent]
created: 2026-08-31
updated: 2026-08-31
agent: cursor-auto
story: docs/stories/1.14.composer-update-phpstan-ide-helper.story.md
status: done
---

# ide-helper refresh — esito

## Gate

| Comando | Esito |
|---------|--------|
| `ide-helper:models --nowrite` | OK → `_ide_helper_models.php` |
| `ide-helper:generate` | OK → `_ide_helper.php` |
| `ide-helper:meta` | OK → `.phpstorm.meta.php` (no more `Is a directory`) |
| `ide-helper:eloquent` | OK (mixin già presenti su `Model`) |
| `phpstan analyse Modules` (cache clear) | **[OK] No errors** |

## Fix causa radice

1. **`config/media.php` era una DIRECTORY** (`media.php/config.php`) → glob ide-helper `**/*.php` → `file_get_contents` EISDIR su `ide-helper:meta`. Convertita in **file** PHP (stesso contenuto modulo Media).
2. **`force_fqn => true`** in `config/ide-helper.php` (Builder/FQCN corretti se si riscrivono PHPDoc).
3. **`HasXotFactory`**: ripristinato `@template TFactory of Factory` (unbound) — `Factory<Model>` spezza lo swarm.
4. **Vietato `ide-helper:models --write --reset`**: cancella `@property` manuali → migliaia di `property.notFound`. Canon: **solo `--nowrite`**.
5. Cache PHPStan stale dopo rewrite model → `phpstan clear-result-cache` prima del gate.

## Dati sacri (vincolo)

Ide-helper **non** autorizza `migrate:fresh` / `--force` / `RefreshDatabase` / `db:wipe`.
Schema vuoto → stop (owner); solo `migrate` additivo. Canon: `docs/wiki/rules/data-sacred-no-destructive-db.md`.

## Policy operativa

```bash
cd laravel
php artisan ide-helper:generate --no-interaction
php artisan ide-helper:meta --no-interaction
php artisan ide-helper:models --nowrite --no-interaction
# opzionale: ide-helper:eloquent (tocca vendor Model — si perde al composer update)
./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

## Evidence

- `docs/chat/ide-helper-{generate,meta,models,eloquent}.log`
- `docs/chat/phpstan-modules-final.txt`
- Canon: `laravel/Modules/Xot/docs/ide-helper-best-practices.md`
