---
title: "Quality Report — Rating"
type: report
tags: [quality, phpstan, pest, coverage]
module: Rating
created: 2026-08-24
updated: 2026-08-24
qmd: "Rating quality report phpstan pest coverage test ratio"
---

# Quality Report — Rating

Aggiornato: 2026-08-24. Rigenera con: `bashscripts/tools/quality-report.sh Rating`

| Metrica | Valore |
|---|---|
| File PHP (app/) | 58 |
| LOC app/ | 2826 |
| File test | 25 |
| LOC test | 2349 |
| Test/App LOC ratio | 83.1% |
| PHPStan (level max) |  |

## Come misurare la coverage Pest

```bash
cd laravel
XDEBUG_MODE=coverage php -d memory_limit=2G ./vendor/bin/pest Modules/Rating/tests \
  --coverage-text --colors=never
```

## Note

- PHPStan gira a level max su tutto `Modules/`: il valore sopra è quello del singolo modulo.
- Il coverage completo per tutti i moduli è costoso (~2 min/modulo con Xdebug): da eseguire selettivamente o via CI.
