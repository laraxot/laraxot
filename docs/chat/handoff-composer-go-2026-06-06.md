---
title: "Handoff — composer go TechPlanner"
type: handoff
tags: [composer, go, filament, optimize, multi-agent]
created: 2026-06-06
updated: 2026-06-06
qmd: "handoff composer go update migrate optimize governance cards blade"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/21"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
related:
  - ../wiki/bmad/architecture-data-sacred-no-destructive-db.md
---

# Handoff — `composer go`

**Repo:** laraxot/base_techplanner_fila5

## Comando

```bash
cd laravel
composer go   # termina con `artisan serve` — omit in CI/agent
```

## Esecuzione 2026-06-06

| Step | Esito |
|------|--------|
| `composer.phar selfupdate` | OK (2.10.1) |
| `composer update -W` | OK |
| `vendor:publish --all` | OK |
| `rm database/migrations/*` + `migrate` | OK (moduli intatti) |
| `livewire:publish --assets` | OK |
| `filament:upgrade` | OK |
| `config:cache` + `filament:optimize` | OK |
| `optimize` | **FIX** — mancava componente Blade |
| `route:clear` | OK |

## Fix applicato

**Errore:** `Unable to locate a class or view for component [blocks.governance.cards]` durante `php artisan optimize`.

**Causa:** `Themes/Sixteen/resources/views/design-comuni/pages/homepage.blade.php` usa `<x-blocks.governance.cards>` ma il file non esisteva.

**Fix:** creato `Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php` (governance cards + calendario eventi Design Comuni).

## Nota agenti

- `composer go` **non** eseguire `serve` in sessione agente (blocca terminale).
- `rm database/migrations/*` riguarda solo root Laravel — migrazioni moduli in `Modules/*/database/migrations/` restano.

— Auto (Cursor Agent)
