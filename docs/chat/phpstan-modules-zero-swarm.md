---
title: PHPStan Modules zero — swarm coordinamento
type: chat-handoff
tags: [phpstan, swarm, modules, multi-agent, bmad]
created: 2026-07-27
updated: 2026-08-31
agent: cursor-auto
story: docs/stories/1.12.phpstan-zero-per-module.story.md
status: done-zero-reverified
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/7
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/8
---

# PHPStan Modules — swarm zero errori

## Esito 2026-08-31 sera (ri-verificato su richiesta)

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1
# [OK] No errors
# totals: errors=0, file_errors=0  (10497 file)
```

Nessuna segnalazione da correggere. Invarianti OK: `PestFunctionBridge` assente, `pest-plugin-phpstan` presente, `HasXotFactory` con `@template TFactory of Factory`.

Evidence: `docs/chat/phpstan-modules-final.txt` · `docs/chat/phpstan-modules-live.json` · `docs/chat/phpstan-modules-raw.json`

Baseline storica sessione: **1581** → **0**. Story: `1.12` → **done**.

## Cause radice chiuse

| Causa | Fix |
|-------|-----|
| `PestFunctionBridge.php` (stub void) | eliminato; generatore = guard exit 1 |
| `pest-plugin-phpstan` assente | installato + extension-installer |
| `User::factory()` → mixed | `BaseUser` `@use HasXotFactory<\Modules\User\Database\Factories\UserFactory>` |
| `HasXotFactory` senza/`Factory<Model>` invariante | SSoT: `@template TFactory of Factory` (unbound) — **non** `Factory<Model>` |
| `@var` corti nei test Pest | FQCN dentro le closure |
| `actingAs($u)->get()` | split: `actingAs($u); get(...)->assert…` |
| `ArticleQueryScopes` mancante / collision Parental | trait ripristinato **senza** `scopeChildrenWith*` (li ha Parental) |
| `LARAVEL_VERSION` race Larastan | `laravel/phpstan_constants.php` in composer files autoload |
| Pest file con `namespace` + `uses()` | rimuovere namespace (Pest globale) |

## Contratto multi-agente (critico)

**NON strippiare i generics da `HasXotFactory`.** Più agenti l’hanno riscritto senza `@template` / con `getFactory()` errato durante lo swarm → regressione centinaia di errori.

- File: `laravel/Modules/Xot/app/Models/Traits/HasXotFactory.php`
- Canon: `@template TFactory of Factory` + `@return TFactory` + niente `getFactory()`
- Lock: `bashscripts/lock/lock.sh` su quel path prima di toccarlo
- Modelli base: `@use HasXotFactory<\Illuminate\Database\Eloquent\Factories\Factory<static>>`
- Modelli con factory concreta: `@use HasXotFactory<\Modules\…\XFactory>`

## Neon extra (solo utente)

Ancora presenti in `laravel/` (policy: agente non tocca):

- `phpstan-patched.neon`, `phpstan-simple.neon`, `phpstan-strict.neon`, `phpstan-temp.neon`

## BMAD

- Story: `docs/stories/1.12.phpstan-zero-per-module.story.md`
- Issue #7 / Discussion #8 — `gh` non autenticato in sessione
- Evidence: `docs/chat/phpstan-modules-final.txt`
