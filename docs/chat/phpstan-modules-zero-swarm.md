---
title: PHPStan Modules zero — swarm coordinamento
type: chat-handoff
tags: [phpstan, swarm, modules, multi-agent, bmad]
created: 2026-07-27
updated: 2026-08-31
agent: cursor-auto
story: docs/stories/1.12.phpstan-zero-per-module.story.md
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/7
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/8
---

# PHPStan Modules — swarm zero errori

## Obiettivo

`cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1` → **0 errori di codice**
(`phpstan.neon` immutabile; solo utente può toccarlo).

## Baseline 2026-08-31 (pre-fix questa sessione)

| Area | Errori |
|------|--------|
| `tests/` | **1567** (quasi tutti fantasma da `PestFunctionBridge.php`) |
| `app/` | **14** |
| **Totale** | **1581** |

Cause radice:

1. **`PestFunctionBridge.php` ri-generato oggi** (`generate-pest-phpstan-bridge.php`) —
   anti-pattern già vietato in `docs/wiki/rules/pest-phpstan-bridge.md`. Stub `: void`
   → `function.void` + `method.nonObject` (`->group()` / `->with()`).
2. **`pestphp/pest-plugin-phpstan` assente da vendor** (era in lock ma non installato).
3. App: `TechPlanner\Models\Profile` inesistente in Media; generics `HasXotFactory`;
   `LocaleSwitcherRefresh`; trait `ArticleQueryScopes` non collegato.

## Fix applicati (agente cursor-auto, 2026-08-31)

| Azione | Perché |
|--------|--------|
| `composer require --dev pestphp/pest-plugin-phpstan:^5.2` | plugin ufficiale per Pest+PHPStan |
| **Eliminato** `Modules/Xot/tests/Support/PestFunctionBridge.php` | rimuove ~1500 errori fantasma |
| Generatore → guard `exit(1)` | impedisce di riprodurre il bridge |
| Media models → `ProfileContract` + `@use HasXotFactory<…Factory>` | class.notFound / missingType.generics |
| `LocaleSwitcherRefresh` → `redirect()->to()` + `array<array-key, mixed>` | argument/return.type |
| `Article` usa `ArticleQueryScopes` (scopes duplicati rimossi) | trait.unused |

## Neon extra (solo utente)

In `laravel/` esistono ancora file vietati dalla policy:

- `phpstan-patched.neon`, `phpstan-simple.neon`, `phpstan-strict.neon`, `phpstan-temp.neon`

L’agente **non** li cancella/ricrea. Chiedere all’utente di rimuoverli.

## Protocollo agenti

1. Lock: `bashscripts/lock/{check,lock,unlock}.sh`
2. **Mai** rigenerare `PestFunctionBridge`
3. Fix causa radice — no `@phpstan-ignore` / baseline
4. Gate: `phpstan analyse Modules` + phpmd + phpinsights sullo scope toccato
5. Append stato qui

## Comandi

```bash
cd laravel
./vendor/bin/phpstan analyse Modules --memory-limit=-1 --no-progress
test ! -f Modules/Xot/tests/Support/PestFunctionBridge.php
composer show pestphp/pest-plugin-phpstan
```

## BMAD

- Story: `docs/stories/1.12.phpstan-zero-per-module.story.md` (lock possibile da altro agente)
- Sprint: `1.12.phpstan-zero-per-module: review` in `docs/sprint-status.yaml`
- `gh` non autenticato in questa sessione → issue/discussion non aggiornate via API
