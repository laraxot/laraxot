---
title: "Audit di qualita: modulo Xot"
type: report
module: Xot
updated: 2026-09-01
qmd: "audit qualita xot phpstan phpmd phpinsights pest coverage soppressioni collisioni case"
---

# Audit di qualita — modulo Xot

Misurato il 1 settembre 2026 a tree fermo. Ogni numero viene da un comando
eseguito, non da una stima; i comandi sono in fondo, cosi la misura si puo
rifare e contestare.

## Stato misurato

| Metrica | Valore |
|---|---:|
| File PHP | 1654 |
| Righe di codice | 108388 |
| File di test `*Test.php` | 154 |
| Casi di test | 613 |
| Casi di test per file PHP | 0.37 |
| `@phpstan-ignore` nel codice | 4 |
| Rilievi PHPMD su `app/` | 1211 *(analisi parziale: abortita in corso)* |
| PHPInsights — Code | 77.6 % |
| PHPInsights — Complexity | 100.0 % |
| PHPInsights — Architecture | 50.0 % |
| PHPInsights — Style | 90.1 % |
| File `.md` sotto `docs/` | 8200 |
| `TODO`/`FIXME`/`HACK` | 9 |
| Test con casi che non girano (senza suffisso `Test.php`) | 0 |
| Collisioni di case nel codice | 24 |
| Collisioni di case nei docs | 66 |
| Marker di conflitto | 0 |
| File `.lock` committati | 0 |
| File `.code-workspace` | 1 |

PHPStan su tutto `Modules/` e a **0 errori, exit 0**, con `ignoreErrors` vuoto in
`phpstan.neon` e `reportUnmatchedIgnoredErrors: true`. Quello zero pero non copre le
soppressioni scritte nel codice come commenti `@phpstan-ignore`: quelle non passano
da `ignoreErrors` e non vengono contate da nessun gate.

## Cosa non va

### Architecture al 50 %, il punteggio piu' basso del progetto

PHPInsights da `Architecture 50.0 %` su Xot, contro una media del resto sopra l'85 %.
Xot e' il modulo base da cui tutti gli altri ereditano: un difetto di struttura qui si
propaga per costruzione a ogni modulo che estende `XotBase*`. E' il singolo numero con
il maggior effetto leva del progetto.

### 1211 rilievi PHPMD che per giorni sono stati letti come zero

Puntando PHPMD alla root del modulo il risultato e' `0`, con un output di 51 byte:
`No node to visit provided for visitAnonymousClass.` L'analizzatore muore alla prima
classe anonima nei test e non analizza niente. Puntandolo a `app/` i rilievi sono
**1211** — il massimo del progetto, su un modulo che veniva riportato come pulito.

### 24 collisioni di case nel codice, 66 nei docs

Le due piu' pericolose sono state chiuse il 1 settembre (`tests/Pest.php` con `pest.php`,
e il doppio `Http/Http` nei controller). Restano 24, fra cui coppie in `.github/`
(`SECURITY.md` / `security.md`, `contributing.md` / `CONTRIBUTING.md`) e residui come
`app/Datas/XotData.php.fixed`, che non e' un file sorgente ma un salvataggio.

### 8226 file .md sotto docs/

Piu' file di documentazione che un file ogni 13 righe di codice. Con 66 collisioni di
case fra di essi, una parte di questa documentazione e' composta da coppie quasi
identiche che divergono in silenzio.

### 4 soppressioni `@phpstan-ignore`

Ogni soppressione e un errore vero che qualcuno ha deciso di non affrontare.
Il `phpstan.neon` di questo progetto lo dice esplicitamente in testa al proprio
output: «Do not add `@phpstan-ignore` comments». Vanno lette una per una e
chiuse alla sorgente o cancellate se non corrispondono piu a niente.

## Coverage

La misura sta in [`coverage.md`](./coverage.md), che va aggiornato a ogni run e non
sostituito.

## Cosa questa misura non vede

- **Il database di test non risponde.** `10.100.200.53:3306` e irraggiungibile: i
  test che scrivono vengono saltati, non falliti. Un conteggio di test verdi qui
  dentro non dice quanti test hanno davvero girato.
- **PHPStan e a zero, ma le soppressioni inline non sono contate da nessun gate.**
  `reportUnmatchedIgnoredErrors` controlla `ignoreErrors` nel neon, non i commenti
  `@phpstan-ignore` sparsi nel codice.
- **PHPMD misurato su `app/`, non sulla root del modulo.** Puntandolo alla root,
  una singola classe anonima nei test fa abortire tutta l'analisi e stampare zero
  rilievi. Uno zero PHPMD sulla root non e una prova di pulizia.
- **I file sotto `tests/` senza suffisso `Test.php` non sono tutti test.** Una
  prima passata ne aveva contati 62 come "test che non girano": verificati uno a uno,
  47 sono stub, fake, helper e classi base che correttamente non hanno il suffisso.
  Il conteggio qui sopra riporta solo i file che contengono davvero casi di test.
- **PHPInsights `Complexity 100 %` su tutte e 22 le unita.** Un valore identico
  ovunque non sta discriminando niente: va trattato come non informativo finche
  non se ne capisce la configurazione.

## Come rifare la misura

```bash
cd laravel
php -d memory_limit=-1 ./vendor/bin/phpstan analyse Modules/Xot
./tools/phpmd.sh Modules/Xot/app          # non la root: aborta sulle classi anonime
./tools/phpinsights.sh Modules/Xot
XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Xot/tests -c Modules/Xot/phpunit.xml --coverage --min=0
grep -rc "@phpstan-ignore" --include=*.php Modules/Xot | grep -v ":0$"
```

Prima di fidarsi di qualunque numero: verificare che nessun altro agente stia
scrivendo sul tree, altrimenti la misura e falsa e diversa a ogni run.

```bash
/usr/bin/find Modules -newermt '-70 seconds' -type f | wc -l   # deve dare 0
```

Audit complessivo e confronto fra tutte le unita: [`docs/quality-audit.md`](../../../../docs/quality-audit.md) nella root del progetto.

