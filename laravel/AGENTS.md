# AGENTS.md — Laravel On-Demand Stub

SSoT: [../docs/wiki/](../docs/wiki/) · bootstrap: [agent-bootstrap-compact.md](../docs/wiki/concepts/agent-bootstrap-compact.md)

## Read First

1. [Trigger Map](../docs/wiki/rules/00-TRIGGER_MAP.md)
2. `bashscripts/docs/llm-wiki-qmd.sh search "<module> <topic>" -n 5`
3. Modulo owner: `Modules/<Name>/docs/wiki/`

## Commands (from `laravel/`)

```bash
composer dev | composer test | composer lint
./vendor/bin/phpstan analyse Modules --memory-limit=-1
./vendor/bin/pint
php artisan test --filter="Name"
```

## Critical

- XotBase / LangBase — mai Filament diretto
- `declare(strict_types=1);` · Pest `it()` — no PHPUnit classi
- No Controllers FO · No `RefreshDatabase`
- Dipendenze modulo → `Modules/*/composer.json`

*Stub — dettagli in docs/wiki e Modules/*/docs/wiki*