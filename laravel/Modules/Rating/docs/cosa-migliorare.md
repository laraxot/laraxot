---
title: "Cosa migliorare: modulo Rating"
type: report
module: Rating
updated: 2026-09-01
qmd: "cosa migliorare rating phpstan phpmd phpinsights coverage debito priorita"
---

# Cosa migliorare — modulo Rating

Ogni affermazione qui sotto viene da un comando eseguito il 1 settembre 2026, dopo il
ripristino di `vendor/` a 330 pacchetti. Le misure precedenti a quella data giravano su
un autoloader dimezzato e non valgono.

## I numeri

| | |
|---|---:|
| Errori PHPStan (modulo isolato) | 0 |
| Rilievi PHPMD su `app/` | 38 (sottostima: parse error) |
| PHPInsights — Code | 97.6 % |
| PHPInsights — Architecture | 78.6 % |
| PHPInsights — Style | 93.8 % |
| File PHP | 132 |
| Casi di test | 125 |
| Casi di test per file | 0.95 |
| Coverage di riga | 80.8 |
| `@phpstan-ignore` | 2 |
| `TODO`/`FIXME`/`HACK` | 0 |
| File `.md` sotto `docs/` | 171 |

## Il quadro

Rating ha **`Code 97.6 %`**, il punteggio più alto del progetto, e una coverage
dell'**80,8 %** — la seconda migliore fra i moduli misurati.

Due dettagli lo tengono sotto la soglia dell'esemplare: **2 parse error PHPMD**, quindi i
38 rilievi sono una sottostima e non un risultato, e **2 `@phpstan-ignore`** che nessuno ha
ancora giustificato per iscritto.

## Cosa fare, in ordine di resa

1. **`Architecture 78.6 %`.** È il segnale che la struttura, non il codice, è il problema: file troppo grandi, troppe dipendenze per classe, o responsabilità mescolate.

## Come rifare ogni numero

```bash
cd laravel
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/Rating
./tools/phpmd.sh Modules/Rating/app     # non la root: aborta sulle classi anonime
./tools/phpinsights.sh Modules/Rating
XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Rating/tests -c Modules/Rating/phpunit.xml --coverage --min=0
```

Prima di fidarsi di qualunque numero: il tree deve essere fermo e `vendor/` completo.

```bash
/usr/bin/find Modules -newermt '-70 seconds' -type f | wc -l   # deve dare 0
php -r 'echo count(require "vendor/composer/autoload_classmap.php");'   # ~25358, non 13041
```

Quadro comparativo di tutte le unità: [`docs/quality-audit.md`](../../../../docs/quality-audit.md).

