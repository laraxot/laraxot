# CLAUDE.md — On-Demand Stub

Guida operativa completa: [docs/wiki/](docs/wiki/) · bootstrap: [agent-bootstrap-compact.md](docs/wiki/concepts/agent-bootstrap-compact.md)

## Session start

1. `docs/wiki/concepts/agent-bootstrap-compact.md`
2. `docs/wiki/rules/00-TRIGGER_MAP.md` → riga task
3. `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5`

## Stack

Laravel modulare · Filament v5 · Folio/Volt · Pest · PHPStan max · `laravel/` codebase

## Commands

```bash
cd laravel && composer dev    # dev stack
cd laravel && composer test   # Pest
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

Dettaglio architettura, XotBase, migrazioni, test: **wiki on-demand**, non preloadare questo file oltre lo stub.

*Stub ≤50 righe — aggiornato 2026-06-06*
