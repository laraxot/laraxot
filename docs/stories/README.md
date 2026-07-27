---
title: "BMAD stories — indice gestionale_commesse"
type: index
tags: [bmad, stories, index, gestionale-commesse]
created: 2026-07-24
updated: 2026-07-27
status: active
qmd: "index bmad stories gestionale workorder fila5 sprint agents on-demand"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/16"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/17"
related:
  - "./0.session-handoff-next-steps.story.md"
  - "../sprint-status.yaml"
  - "../gestionale-commesse-comparison/replica-singular-phase.md"
  - "../chat/gestionale-commesse-parity-handoff.md"
---

# Indice BMAD stories — allineamento gestionale_commesse

> ## 🚦 HANDOFF — leggi questo prima se riprendi senza contesto (2026-07-24)
>
> **Brief BMAD dedicato:** [0.session-handoff-next-steps.story.md](./0.session-handoff-next-steps.story.md) · sprint `next_queue` in [sprint-status.yaml](../sprint-status.yaml).
>
> **Prossima azione a più alto impatto:** causa radice Pest **D17** — [debt-phpunit-env-precedence.md](../../laravel/docs/debt-phpunit-env-precedence.md) + changelog [2.5](./2.5.migrate-gestionale-tests.story.md). In breve:
> 1. `laravel/phpunit.xml` forza `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` e scavalca `.env.testing`.
> 2. Fixture `database/fixcity_data.sqlite` (via `prepareSharedFixcitySqliteForTesting()`) **senza** schema `users`/`roles` di *questo* progetto.
> 3. **Non** improvvisare `migrate:fresh` / `RefreshDatabase` / migrate User in blocco (duplicati create_users + file multi-agente). Percorso sicuro = playbook D17 + sezione «Prossimi passi» in story 2.5.
>
> **Poi:** Pest wave 2.5 (Quotation/Signature/Billing) · Timber 1.13 · D1 Document≠media · 2.3 / 1.5.
>
> Inventario debito: [gestionale-technical-debt.md](../../laravel/docs/gestionale-technical-debt.md) (D1–D17).  
> Lock: `bashscripts/lock/check.sh <path>` · non fidarti della chat precedente — verifica filesystem/git.
>
> **in-progress da continuare (non da zero):** `0.session-handoff-next-steps`, `2.3`, `2.5` (+ registro), `1.13.migrate-real-tests-and-timber-gap`.  
> **review** ≈ verificabile → `done`, non rifare.

Sprint: [docs/sprint-status.yaml](../sprint-status.yaml)  
Canon fase: [replica-singular-phase.md](../gestionale-commesse-comparison/replica-singular-phase.md)

## Epic / parity

| Story | Status | Note |
|---|---|---|
| [**0 handoff next steps**](./0.session-handoff-next-steps.story.md) | in-progress | Cold start obbligatorio |
| [gestionale-commesse-module-parity](./gestionale-commesse-module-parity.story.md) | in-progress | Docs mapping SRC→DEST |
| [1.2 Customer](./1.2.customer-module.story.md) | review | Modulo cuore + test Actions green |
| [1.3 WorkOrder](./1.3.workorder-module.story.md) | review | Create smoke green |
| [1.4 Intervention](./1.4.intervention-module.story.md) | review | Create smoke + Platform skip |
| [1.5 Employee/HR gap](./1.5.employee-hr-gap.story.md) | ready-for-dev | Replica HR≠Employee |

## Wave reshape / copy

| Story | Status |
|---|---|
| [1.6 copy-reshape](./1.6.copy-reshape-src-modules.story.md) | done |
| [1.7 composer.json](./1.7.composer-json-new-modules.story.md) | done |
| [1.8 wave1 XotBase](./1.8.wave1-reshape-xotbase.story.md) | review |
| [1.9 wave2](./1.9.wave2-reshape-remaining-modules.story.md) | review |
| [1.11 wave3](./1.11.wave3-reshape-remaining-src-modules.story.md) | done |
| [2.1 copy-then-normalize](./2.1.copy-then-normalize-modules.story.md) | done |

## Guardrail / quality

| Story | Status |
|---|---|
| [1.10 agent-errors guardrails](./1.10.agent-errors-replica-singular-guardrails.story.md) | done |
| [1.10 session lessons](./1.10.session-lessons-learned.story.md) | (lesson log) |
| [2.2 agent-errors replica](./2.2.agent-errors-replica-singular.story.md) | done |
| [1.12 phpstan zero](./1.12.phpstan-zero-per-module.story.md) | review |
| [2.3 deep quality](./2.3.deep-quality-enabled-modules.story.md) | in-progress |
| [2.4 docs hygiene](./2.4.module-theme-docs-hygiene.story.md) | review |
| [2.4 compliance phpstan](./2.4.compliance-phpstan-cleanup.story.md) | superseded → 2.3 |

## Readiness / migrate / test / debt

| Story | Status | Ruolo |
|---|---|---|
| [1.13 functional-readiness](./1.13.functional-readiness-migrate-panels.story.md) | review | path migrate + AdminPanel |
| [1.13 migrate-real-tests + Timber](./1.13.migrate-real-tests-and-timber-gap.story.md) | in-progress | Timber + tallies; **prossimi passi** in story |
| [**2.5 migrate-gestionale-tests SSoT**](./2.5.migrate-gestionale-tests.story.md) | in-progress | Catalog **11** / HR **3+5skip**; **prossimi passi** |
| [2.5 registro dettagliato](./2.5.gestionale-tests-migration.story.md) | in-progress | Alias + coda residuale |
| [**2.6 technical-debt playbooks**](./2.6.technical-debt-playbooks.story.md) | review | HOW `debt-*`; chiusura review #14 |
| [**2.7 agents on-demand compact**](./2.7.agents-skills-rules-on-demand-compact.story.md) | ready-for-dev | stub wave done; #16 / #17 |

## Religion (non negoziabile)

- Replica SRC + PascalCase **singolare** — no delete Auth/HR/Document/AiAssistant
- No `git restore`; no `migrate --force`; no `RefreshDatabase`
- Errori E1–E13: [agent-errors-lessons.md](../gestionale-commesse-comparison/agent-errors-lessons.md)
- Debito HOW: [laravel/docs/gestionale-technical-debt.md](../../laravel/docs/gestionale-technical-debt.md)
- Hub Pest moduli: [gestionale-tests-migration-status.md](../../laravel/Modules/docs/gestionale-tests-migration-status.md)
