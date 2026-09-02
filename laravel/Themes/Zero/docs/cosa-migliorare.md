---
title: "Cosa migliorare: tema Zero"
type: report
theme: Zero
updated: 2026-09-01
qmd: "cosa migliorare zero phpstan phpmd phpinsights coverage debito priorita"
---

# Cosa migliorare — tema Zero

Ogni affermazione qui sotto viene da un comando eseguito il 1 settembre 2026, dopo il
ripristino di `vendor/` a 330 pacchetti. Le misure precedenti a quella data giravano su
un autoloader dimezzato e non valgono.

## I numeri

| | |
|---|---:|
| Errori PHPStan (modulo isolato) | 0 |
| Rilievi PHPMD su `app/` | 0 |
| PHPInsights — Code | 100 % |
| PHPInsights — Architecture | 100 % |
| PHPInsights — Style | 100 % |
| File PHP | 28 |
| Casi di test | 0 |
| Casi di test per file | 0.00 |
| Coverage di riga | **mai misurata** |
| `@phpstan-ignore` | 0 |
| `TODO`/`FIXME`/`HACK` | 0 |
| File `.md` sotto `docs/` | 200 |

## Il quadro

Il tema Zero ha **zero test** su 28 file PHP, **due `.code-workspace`** dove ne
serve uno, e nella root convivono `conflict-resolution-summary.md` e
`CONFLICT_RESOLUTION_SUMMARY.md` — due file che su macOS sono lo stesso file.

In root ci sono anche `phpstan_themes_zero_filtered.json`, artefatto di una run vecchia, e
`gitmodules.ini`, che non è un file che git legge.

## Cosa fare, in ordine di resa

1. **Zero test su 28 file PHP.** Qualunque punteggio di qualità qui descrive la forma del codice, non il suo comportamento.

2. **Alzare la densità di test.** 28 file PHP e 0 casi: 0.00 per file. Non serve un piano di copertura totale, serve un test sui percorsi che si rompono.

## Come rifare ogni numero

```bash
cd laravel
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Themes/Zero
./tools/phpmd.sh Themes/Zero/app     # non la root: aborta sulle classi anonime
./tools/phpinsights.sh Themes/Zero
XDEBUG_MODE=coverage ./vendor/bin/pest Themes/Zero/tests -c Themes/Zero/phpunit.xml --coverage --min=0
```

Prima di fidarsi di qualunque numero: il tree deve essere fermo e `vendor/` completo.

```bash
/usr/bin/find Modules -newermt '-70 seconds' -type f | wc -l   # deve dare 0
php -r 'echo count(require "vendor/composer/autoload_classmap.php");'   # ~25358, non 13041
```

Quadro comparativo di tutte le unità: [`docs/quality-audit.md`](../../../../docs/quality-audit.md).

