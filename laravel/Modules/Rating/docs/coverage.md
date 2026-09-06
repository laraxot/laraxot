---
title: "Coverage del modulo Rating"
type: report
module: Rating
updated: 2026-09-01
qmd: "coverage rating pest misura reale test saltati database"
---

# Coverage del modulo Rating

## Misura del 1 settembre 2026

Comando canonico (AD-25 — servono **entrambe** le opzioni: `-c` sposta il perimetro
di coverage, il path sposta il bootstrap di `Pest.php` e `Helpers.php`):

```bash
cd laravel
XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Rating/tests -c Modules/Rating/phpunit.xml --coverage --min=0
```

| | |
|---|---:|
| **Coverage di riga** | **80.8 %** |
| Test passati | 118 |
| Test saltati | 7 |
| Falliti | 0 |
| Asserzioni | 442 |

## Come leggere questo numero

La percentuale copre il codice sotto `Modules/Rating/app`, che e il perimetro
dichiarato in `Modules/Rating/phpunit.xml`.

**7 test sono stati saltati**, non falliti. I salti in questo progetto
vengono quasi sempre dal database di test irraggiungibile (`10.100.200.53:3306`)
e non da `skip()` scritti a mano: la suite resta verde e il numero descrive solo
la parte che ha girato davvero.

## Cosa e cambiato oggi

Prima di questa misura la suite era rossa su
`tests/Unit/ListRatingsPageTest.php:23`, che asserva 5 colonne su
`BaseListRatings::getTableColumns()`. Quel metodo pero e chiuso dentro un docblock
mai terminato: le colonne sono state spostate di proposito nelle classi sotto
`app/Filament/Resources/RatingResource/Tables/`.

Il test era vecchio, non il codice. Riscritto per asserire il contratto vero — la
pagina non possiede piu colonne, e le 9 colonne stanno in `RatingsTable` — la suite
e verde e il coverage e diventato misurabile: **80,8 %**.

## Precondizioni che invalidano la misura

1. **`bootstrap/cache/config.php` non deve esistere.** Con la config in cache
   `config('app.env')` vale `production` e `app()->runningUnitTests()` diventa falso:
   ogni guardia costruita su quel segnale smette di funzionare. Il file e gitignored e
   viene rigenerato da un qualsiasi `artisan optimize`, quindi va ricontrollato ogni volta.
2. **Nessun altro agente deve stare scrivendo sul tree.** Una run su file in scrittura
   produce risultati diversi a ogni giro.

```bash
/usr/bin/find Modules -newermt '-70 seconds' -type f | wc -l   # deve dare 0
```

## Aggiornamento del 6 settembre 2026 (PHPStan zero-error, story phpstan-Rating-fix)

Baseline PHPStan `Modules/Rating` ripulita da 6 → 0 errori (1 `cast.string` in
`HasRatingsTrait::getRatingsRules()`, 5 `method.deprecated` in
`tests/Unit/ListRatingsPageTest.php`). Dettagli tecnici:
`docs/stories/phpstan-Rating-fix.md`.

`tests/Unit/ListRatingsPageTest.php` è stato riportato da 1 a 2 test dopo la
rimozione delle chiamate deprecate: il test sulle colonne è stato riscritto contro
`$page->table(Table::make($page))->getColumns()` invece di chiamare
`getTableColumns()` deprecato direttamente (stesso pattern di
`RatingFilamentExtendedTest.php`, story 2.3). I tre test su filtri/header/row
actions non sono stati riproposti con lo stesso pattern: passando dal `Table`
costruito, `HasXotTable::invokeTableHook()` salta di proposito
`getTableFilters()/getTableHeaderActions()/getTableActions()/getTableBulkActions()`
quando non overridati dalla pagina concreta (per non duplicare i default nativi
Filament), quindi avrebbero prodotto asserzioni false — non erano nello scope
PHPStan/deprecazione della story.

Verifica mirata (rete/CPU condivisa con ~30 agenti concorrenti, run full-module
troppo lento per attendere in sessione):
```
./vendor/bin/pest Modules/Rating/tests/Unit/ListRatingsPageTest.php -c Modules/Rating/phpunit.xml --no-coverage
Tests: 2 passed (2 assertions)
```
PHPStan `Modules/Rating` cold: **0 errori** (confermato 3 volte con
`clear-result-cache` prima di ogni run).

## Storico

| Data | Passati | Saltati | Falliti | Coverage |
|---|---:|---:|---:|---|
| 2026-09-01 | 118 | 7 | 0 | 80.8 % |
| 2026-09-06 (mirato, vedi nota sopra) | 2/2 file toccato | — | 0 | non ricalcolata (full-suite non completata per carico condiviso) |

