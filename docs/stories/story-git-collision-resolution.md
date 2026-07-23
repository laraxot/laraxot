---
story_id: STORY-GIT-001
title: Risoluzione collisioni Git merge dev ↔ origin/dev
epic: infrastructure
phase: 4-implementation
status: done
points: 8
agent: scrum-master
created: 2026-06-06
---

# STORY-GIT-001 — Risoluzione collisioni Git

## User story

Come **maintainer del monorepo TechPlanner**,
voglio **eliminare tutti i marker `<<<<<<<` / `=======` / `>>>>>>>`** introdotti dal merge `dev` ↔ `origin/dev`,
così che **PHPStan, Pest e gli agenti AI possano analizzare il codice senza parse error**.

## Contesto

- Branch: `dev` in merge con `origin/dev` (3712 file unmerged)
- HEAD locale: codice produzione (168 commit avanti)
- origin/dev: second brain on-demand, stub AGENTS/CLAUDE, BMAD v6, `docs/wiki/`

## Acceptance criteria

- [x] Nessun marker `^<<<<<<<` in file `.php`, `.blade.php`, `.json`, `.neon`, `.js`, `.css`
- [x] `AGENTS.md` e `CLAUDE.md` root → stub on-demand (≤50 righe)
- [x] `laravel/AGENTS.md` → stub Laravel on-demand
- [x] Codice applicativo → versione HEAD (ours) preservata
- [x] `docs/wiki/` da origin/dev integrato
- [x] Test regressione: `UserMigrationSyntaxTest` verifica assenza marker nelle migrazioni User
- [x] Documentazione second brain: `docs/wiki/how-to/git-merge-marker-sweep.md`
- [x] Corruzione strutturale senza marker: restore da `origin/master` + fix import/trait/comandi
- [x] Bootstrap Laravel: `php artisan about` senza fatal error
- [x] Quality gate documentato: `laravel/tools/post-edit-php.sh` (PHPStan, PHPMD, PHPInsights, Pest)

## Strategia di risoluzione

| Categoria | Scelta | Motivo |
|-----------|--------|--------|
| PHP, Blade, config runtime | **ours (HEAD)** | Codice produzione stabile |
| `AGENTS.md`, `CLAUDE.md` | **theirs (origin/dev)** | Stub second brain, context compression |
| `docs/wiki/**` | **theirs** | SSoT wiki on-demand |
| `composer.lock`, `package-lock.json` | **theirs + verify** | Lockfile coerente post-merge |
| Lato vuoto in conflitto | **lato non vuoto** | Evita perdita import/use |
| Docs inventory conflitti | **skip auto** | Contengono marker come esempio testuale |

## File critici toccati manualmente

- `laravel/phpstan_bootstrap.php` — merge import + TechPlanner main_module
- `laravel/Modules/Xot/app/Providers/XotBaseServiceProvider.php` — merge conflitti + SvgNotFound
- `laravel/Modules/Xot/app/Database/Migrations/XotBaseMigration.php` — restore master + migliorie dev
- `laravel/Modules/Xot/app/Datas/XotData.php` — restore master + `findUserByEmail()`
- `laravel/Modules/User/app/Models/Traits/HasTeams.php` — `teams()` de-commentato (restore master)
- `laravel/Modules/User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php`
- `AGENTS.md`, `CLAUDE.md`, `laravel/AGENTS.md`

## Verifica

```bash
# Zero marker in codice
rg '^<<<<<<< ' laravel --glob '*.{php,blade.php,json,neon}'

# Test migrazioni User
cd laravel && php artisan test --filter=UserMigrationSyntax
```

## Collegamenti

- [git-merge-marker-sweep](../wiki/how-to/git-merge-marker-sweep.md)
- [00-TRIGGER_MAP](../wiki/rules/00-TRIGGER_MAP.md) — riga «Residui conflitto Git»
- [bmad-on-demand-routing](../../laravel/Modules/Xot/docs/wiki/skills/bmad-on-demand-routing.md)