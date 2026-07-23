# AGENTS.md — On-Demand Stub

Regole, skill e memorie vivono solo in `docs/wiki/`. Caricamento on-demand via QMD.

## Read First

1. [Bootstrap compatto](docs/wiki/concepts/agent-bootstrap-compact.md)
2. [Trigger Map](docs/wiki/rules/00-TRIGGER_MAP.md) — una riga per task
3. `bashscripts/docs/second-brain-healthcheck.sh "<topic>"`
4. `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -c techplanner-wiki -n 5 --files`
5. Prompt harness: [bashscripts/tools/prompts/llm-wiki.txt](bashscripts/tools/prompts/llm-wiki.txt)

## Laravel app

- [laravel/AGENTS.md](laravel/AGENTS.md) — entrypoint codice PHP/Filament
- PHPStan L10, Pest, XotBase — dettagli in wiki, non qui

## Critical (always)

- Mai Controllers FO — Folio + Volt + Laraxot
- Mai estendere Filament diretto — XotBase / LangBase
- Mai `RefreshDatabase` — `.env.testing` SQLite
- Commit solo su richiesta esplicita utente
- Stesso task multi-agente → `docs/chat/INDEX.md` + issue GitHub (`git remote -v`)

*Stub ≤50 righe — SSoT: docs/wiki/*
