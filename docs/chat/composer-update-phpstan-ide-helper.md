---
title: Composer update -W + PHPStan + ide-helper
type: chat-handoff
tags: [composer, phpstan, ide-helper, bmad, multi-agent]
created: 2026-08-31
updated: 2026-08-31
agent: cursor-auto
story: docs/stories/1.14.composer-update-phpstan-ide-helper.story.md
status: done
---

# Composer update -W — esito

## Gate

| Check | Esito |
|-------|--------|
| `composer update -W` | OK (Filament 5.7.6 → 5.7.7) |
| `phpstan analyse Modules` | **[OK] No errors** |
| `ide-helper:generate` | OK, no skip ResponseCache |
| `ide-helper:meta` | OK |
| `ide-helper:models --nowrite` | OK, **0** Error/Exception |

## Fix causa radice

1. `config/responsecache.php`: `DefaultSerializer` → `JsonSerializer` (Spatie v8)
2. DB MySQL create `workorder_data` / `workorder_user` (mancanti in locale)
3. `config/database.php`: alias `blog` + `comment` (moduli orphan fuori `modules_statuses`)
4. `config/comments.php` pubblicato + `commentator` → `User::class`
5. 9 factory senza `@extends Factory<Model>` tipizzate

## Evidence

- `docs/chat/composer-update-W.log`
- `docs/chat/phpstan-modules-final.txt`
- `docs/chat/ide-helper-{generate,meta,models}.log`

## Vincoli

- `phpstan.neon` non toccato
- `HasXotFactory` resta `@template TFactory of Factory`
- Models **non** riscritti (`--nowrite` only)
