---
title: "Cosa migliorare: modulo Xot"
type: report
module: Xot
updated: 2026-09-01
qmd: "cosa migliorare xot phpstan phpmd phpinsights coverage debito priorita"
---

# Cosa migliorare — modulo Xot

Ogni affermazione qui sotto viene da un comando eseguito il 1 settembre 2026, dopo il
ripristino di `vendor/` a 330 pacchetti. Le misure precedenti a quella data giravano su
un autoloader dimezzato e non valgono.

## I numeri

| | |
|---|---:|
| Errori PHPStan (modulo isolato) | 0 |
| Rilievi PHPMD su `app/` | 1211 (sottostima: parse error) |
| PHPInsights — Code | 77.6 % |
| PHPInsights — Architecture | 50.0 % |
| PHPInsights — Style | 81.5 % |
| File PHP | 1665 |
| Casi di test | 636 |
| Casi di test per file | 0.38 |
| Coverage di riga | 100,0 |
| `@phpstan-ignore` | 13 |
| `TODO`/`FIXME`/`HACK` | 9 |
| File `.md` sotto `docs/` | 8215 |

## Il quadro

Xot non è un modulo. È la gravità: non la vedi, ma decide dove cade tutto il resto.

E la gravità qui è storta. **`Architecture 50.0 %`** — il numero peggiore del progetto, su
un modulo da cui ereditano tutti gli altri. Ogni difetto di struttura qui non resta qui:
si moltiplica per il numero di classi che estendono `XotBase*`, in silenzio, per sempre.

**1211 rilievi PHPMD**, il massimo assoluto. E per giorni sono stati letti come zero,
perché puntando PHPMD alla root del modulo l'analisi aborta alla prima classe anonima nei
test e stampa un output di 51 byte. Un modulo che si dichiarava pulito mentre era il più
sporco: non è ironia, è il costo di fidarsi di un conteggio senza guardare la prima riga.

**13 `@phpstan-ignore`**, di cui 9 aggiunti oggi su `HasXotTable.php` per zittire
`method.deprecated` invece di chiudere la migrazione di `XotBaseRelationManager` (27
sottoclassi), `XotBaseManageRelatedRecords` (9) e `XotBaseTableWidget` (1). Il gate legge
0, il difetto è dov'era.

## Cosa fare, in ordine di resa

1. **Rivedere le 13 soppressioni `@phpstan-ignore`.** Ognuna nasconde un errore che esiste davvero: il `phpstan.neon` di questo progetto lo scrive in testa al proprio output.

2. **`Architecture 50.0 %`.** È il segnale che la struttura, non il codice, è il problema: file troppo grandi, troppe dipendenze per classe, o responsabilità mescolate.

3. **1211 rilievi PHPMD.** Non vanno chiusi tutti: vanno raggruppati per regola e va chiusa la regola più frequente, che di solito è una sola abitudine ripetuta.

4. **8215 file `.md` sotto `docs/`.** Oltre una certa soglia la documentazione smette di essere consultabile e diventa un archivio: va sfoltita fondendo, non cancellando, perché de-duplicare rompe i link.

## Come rifare ogni numero

```bash
cd laravel
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/Xot
./tools/phpmd.sh Modules/Xot/app     # non la root: aborta sulle classi anonime
./tools/phpinsights.sh Modules/Xot
XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Xot/tests -c Modules/Xot/phpunit.xml --coverage --min=0
```

Prima di fidarsi di qualunque numero: il tree deve essere fermo e `vendor/` completo.

```bash
/usr/bin/find Modules -newermt '-70 seconds' -type f | wc -l   # deve dare 0
php -r 'echo count(require "vendor/composer/autoload_classmap.php");'   # ~25358, non 13041
```

Quadro comparativo di tutte le unità: [`docs/quality-audit.md`](../../../../docs/quality-audit.md).

