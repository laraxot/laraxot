---
title: "Chat Agenti — Indice handoff"
type: index
tags: [chat, handoff, multi-agent, second-brain, coordination]
created: 2026-06-06
updated: 2026-06-06
qmd: "chat index handoff agent session techplanner multi agent coordination github issue same task migrate artisan"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/18"
  - "https://github.com/laraxot/base_techplanner_fila5/issues/21"
  - "https://github.com/laraxot/base_techplanner_fila5/issues/23"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
related:
  - ../wiki/how-to/multi-agent-coordination-discipline.md
  - ../wiki/how-to/github-issue-agent-discipline.md
  - ../wiki/memories/multi-agent-coordination-standing.md
  - ../wiki/concepts/agent-bootstrap-compact.md
---

# Chat agenti — TechPlanner

**Bus locale** tra agenti AI che ricevono lo **stesso task**. Non sostituisce `docs/wiki/` (SSoT versionata) né le **GitHub issues** (audit persistente).

## Regola standing (utente)

Prima di ogni task condiviso:

1. Leggi questo indice + handoff del topic
2. `git remote -v` → commenta issue/discussion sulla repo owner
3. Aggiorna handoff a fine sessione se altri agenti seguiranno

Canon: [multi-agent-coordination-discipline.md](../wiki/how-to/multi-agent-coordination-discipline.md) · Issue [#18](https://github.com/laraxot/base_techplanner_fila5/issues/18) · Discussion [#19](https://github.com/laraxot/base_techplanner_fila5/discussions/19)

## Bootstrap sessione

1. `docs/wiki/concepts/agent-bootstrap-compact.md`
2. **`docs/chat/INDEX.md`** (questo file) + handoff topic
3. `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5 --files`
4. `docs/wiki/rules/00-TRIGGER_MAP.md` — una riga dominio

## Handoff attivi

| Data | File | Argomento | Issue |
|------|------|-----------|-------|
| 2026-06-06 | [handoff-bmad-one-migration-per-model.md](./handoff-bmad-one-migration-per-model.md) | /bmad/architecture N modelli = N migrazioni | [#23](https://github.com/laraxot/base_techplanner_fila5/issues/23) |
| 2026-06-06 | [handoff-phpstan-migrate-2026-06-06.md](./handoff-phpstan-migrate-2026-06-06.md) | migrate SQLite + PHPStan 0 | [#22](https://github.com/laraxot/base_techplanner_fila5/issues/22) |
| 2026-06-06 | [handoff-composer-module-dependency-bmad.md](./handoff-composer-module-dependency-bmad.md) | folio→Cms, activitylog→Activity, pdf→Xot | [#16](https://github.com/laraxot/base_techplanner_fila5/issues/16) |
| 2026-06-06 | [handoff-multi-agent-coordination.md](./handoff-multi-agent-coordination.md) | Regola docs/chat + GitHub multi-agente | [#18](https://github.com/laraxot/base_techplanner_fila5/issues/18) |
| 2026-06-06 | [handoff-artisan-migrate-2026-06-06.md](./handoff-artisan-migrate-2026-06-06.md) | migrate sqlite + user DB, fix Activity index + Media duplicato | [#21](https://github.com/laraxot/base_techplanner_fila5/issues/21) |
| 2026-06-06 | [handoff-bmad-architecture-one-migration-per-model-2026-06-06.md](./handoff-bmad-architecture-one-migration-per-model-2026-06-06.md) | N modelli = N migrazioni create_* per modulo | [#23](https://github.com/laraxot/base_techplanner_fila5/issues/23) |
| 2026-06-06 | [handoff-composer-go-2026-06-06.md](./handoff-composer-go-2026-06-06.md) | composer go + fix blocks.governance.cards | [#21](https://github.com/laraxot/base_techplanner_fila5/issues/21) |

| 2026-07-06 | [phpstan-user-migration-syntax-lock-2026-07-06.md](./phpstan-user-migration-syntax-lock-2026-07-06.md) | Blocco PHPStan residuo su UserMigrationSyntaxTest.php: file lockato, fix return.type documentato | [#34](https://github.com/laraxot/base_techplanner_fila5/issues/34) |
| 2026-07-06 | [phpstan-pest-this-binding-fix-2026-07-06.md](./phpstan-pest-this-binding-fix-2026-07-06.md) | Fix sistemico Pest `$this` binding (AST-based) + collisione lock Geo revertita | [#34](https://github.com/laraxot/base_techplanner_fila5/issues/34) |
| 2026-07-06 | [phpstan-modules-progress-2026-07-06-pm.md](./phpstan-modules-progress-2026-07-06-pm.md) | Modules/ portato a 0 errori PHPStan (lavoro convergente multi-agente); docs di modulo aggiornati con root cause reali | [#34](https://github.com/laraxot/base_techplanner_fila5/issues/34) |
| 2026-07-06 | [second-brain-qmd-cache-bug-2026-07-06.md](./second-brain-qmd-cache-bug-2026-07-06.md) | Bug wrapper qmd **risolto** (cache vuota → indice reale), embedding vettoriali in corso | — |
| 2026-07-06 | [phpstan-collision-bugs-round-2026-07-06.md](./phpstan-collision-bugs-round-2026-07-06.md) | Round bug reali da collisione multi-agente (namespace corrotto, funzione globale duplicata, merge marker, @var fittizio) → 0 errori riconfermato | [#34](https://github.com/laraxot/base_techplanner_fila5/issues/34) |
| 2026-07-06 | [phpstan-modules-progress-2026-07-06-pm.md](./phpstan-modules-progress-2026-07-06-pm.md) (append serale) | Pulizia root moduli/temi: cartelle maiuscole, `.txt`, `.md` extra rimossi (grande convergenza multi-agente); `Themes/Two/Resources/` flaggato, non toccato (referenziato da Vite) | — |
| 2026-07-06 | [phpstan-modules-zero-confirmed-and-phpmd-fix-2026-07-06.md](./phpstan-modules-zero-confirmed-and-phpmd-fix-2026-07-06.md) | Riconferma indipendente 0 errori (cache pulita, 2x); fix `tools/phpmd.sh` (phpmd globale via phive, sbloccava Codex) | [#34](https://github.com/laraxot/base_techplanner_fila5/issues/34) |
| 2026-07-16 | [tenantservice-missing-blocks-phpstan.md](./tenantservice-missing-blocks-phpstan.md) | `Modules\Tenant\Services\TenantService` mancante → fatal bootstrap PHPStan; 3 conflitti merge irrisolti risolti (sync-ide-junctions.sh, Category.php) + 5 widget Geo senza `getFormSchema()` corretti | — |
| 2026-07-16 | [handoff-models-parity-audit-2026-07-16.md](./handoff-models-parity-audit-2026-07-16.md) | Audit (sola lettura) parità Models/Migrations/Seeders/Factories sui 22 moduli: gap grossi in TechPlanner (13 seeder mancanti), Tenant (migrations assenti), duplicati sospetti in Cms/Gdpr/Rating/User; docs/ moduli non toccati | — |

## Sessioni archivio

| Data | Slug | Argomento |
|------|------|-----------|
| 2026-06-06 | llm-wiki-bootstrap | Harness LLM Wiki + gap QMD techplanner |
| 2026-06-06 | [phpstan-zero-swarm-handoff](./phpstan-zero-swarm-handoff.md) | PHPStan 0 errori full project + second brain + BMAD v6 |

## Convenzioni

| Cosa | Dove |
|------|------|
| Decisione duratura | `docs/wiki/` + `log.md` + frontmatter GitHub |
| Stato turno / lock | Issue comment + handoff qui |
| Firma | `— <Agente> (<modello>)` |
| Duplicati | Vietato — cerca handoff + `gh issue list` prima |

## Cursor rule

`.cursor/rules/multi-agent-coordination.mdc` (alwaysApply)
# Active coordination

- [Gitmodules multi-repo sync](gitmodules-multi-repo-sync.md) — issue #42, discussion #43
