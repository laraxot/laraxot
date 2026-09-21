# AGENTS.md — On-Demand Stub

Regole, skill e memorie vivono solo in `docs/wiki/`. Caricamento on-demand via QMD.

## Read First

1. [Bootstrap compatto](docs/wiki/concepts/agent-bootstrap-compact.md)
2. [Second brain max](docs/wiki/skills/cursor-second-brain-max-workflow.md) — rule `.cursor/rules/second-brain-max-power.mdc`
3. [Trigger Map](docs/wiki/rules/00-TRIGGER_MAP.md) — una riga per task
4. `bashscripts/docs/second-brain-healthcheck.sh "<topic>"`
5. `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5 --files`

## Critical (always)

- **Second brain prima di codice** — healthcheck + QMD + wiki owner + write-back
- Mai Controllers FO — Folio + Volt + Laraxot
- Mai estendere Filament diretto — XotBase / LangBase
- Mai `RefreshDatabase` — `.env.testing` SQLite
- **Git solo avanti** — studia con `git show`; **mai** restore/checkout/reset/revert/rollback
- **gitmodules.ini = inventario repo figli, NON submodule.** Ogni `path` ha `.git/` proprio. `git status` dalla root NON è lo status del modulo. Obbligatorio: `git -C <path>` e `rev-parse --show-toplevel` uguale al path. Mai affidarsi al `working_directory` dello Shell tool. Itera TUTTI i path del file.
- Commit solo su richiesta esplicita utente
- Stesso task multi-agente → `docs/chat/INDEX.md` + issue GitHub (`git remote -v`)

*Stub ≤50 righe — SSoT: docs/wiki/*
