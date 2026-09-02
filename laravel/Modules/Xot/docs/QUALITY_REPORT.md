---
title: "Quality Report — Xot"
type: report
tags: [quality, phpstan, pest, coverage]
module: Xot
created: 2026-08-24
updated: 2026-08-24
qmd: "Xot quality report phpstan pest coverage test ratio"
---

# Quality Report — Xot

Aggiornato: 2026-08-24. Rigenera con: `bashscripts/tools/quality-report.sh Xot`

| Metrica | Valore |
|---|---|
| File PHP (app/) | 551 |
| LOC app/ | 39734 |
| File test | 212 |
| LOC test | 19340 |
| Test/App LOC ratio | 48.7% |
| PHPStan (level max) |  |

## Come misurare la coverage Pest

```bash
cd laravel
XDEBUG_MODE=coverage php -d memory_limit=2G ./vendor/bin/pest Modules/Xot/tests \
  --coverage-text --colors=never
```

## Note

- PHPStan gira a level max su tutto `Modules/`: il valore sopra è quello del singolo modulo.
- Il coverage completo per tutti i moduli è costoso (~2 min/modulo con Xdebug): da eseguire selettivamente o via CI.
