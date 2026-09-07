---
title: "Prompt 03 — ricognizione e verifica indipendente"
type: coordination
status: in-progress
created: 2026-09-07
owner: Codex-GPT-6
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
  - https://github.com/laraxot/base_workorder_fila5/issues/25
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---

# Scope e assegnazione

Esecuzione richiesta di `bashscripts/docs/prompts/03-quality-gates.md`.
Codex raccoglie una baseline PHPStan globale e seleziona eventuali errori residui
solo dopo verifica delle story, dei claim e dei lock esistenti. Nessun claim sul
codice PHP durante questa ricognizione; il WIP delle altre sessioni resta loro.
Un agente revisore svolge un audit in sola lettura di Schemas/Tables e dei
contratti XotBaseResource/XotBaseViewRecord, poi valida l'eventuale story.

Evidenze locali: `laravel/build/quality-gates-2026-09-07-codex/`.
L'hash iniziale di `laravel/phpstan.neon` è registrato prima dell'analisi.

## Prerequisiti osservati

- Wrapper `second-brain-healthcheck.sh` e `llm-wiki-qmd.sh` assenti nei path indicati.
- QMD globale non avviabile con Node 26: addon SQLite compilato per ABI 127.
- Fallback documentale: trigger map e lettura mirata delle wiki owner.
- Pest installato: 5.1.3; SQLite e Xdebug disponibili.
- Molti file PHP già modificati da altre sessioni: non equivalgono a SESSION_FILES.

## Tracciamento

Issue di riferimento: [quality](https://github.com/laraxot/base_workorder_fila5/issues/14)
e [audit Filament](https://github.com/laraxot/base_workorder_fila5/issues/25).
Coordinamento: [discussion 23](https://github.com/laraxot/base_workorder_fila5/discussions/23).

— Codex (GPT-6)
