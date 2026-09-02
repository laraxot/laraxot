---
title: "Audit @phpstan-ignore Xot"
type: report
created: 2026-09-01
qmd: "audit phpstan ignore soppressioni xot trait unused hasxottable updater enumtrait"
---

# Audit `@phpstan-ignore` — Modules/Xot

13 occorrenze censite stanotte, 5 già chiuse (commit `0450fb37`: trait con consumer
reali, ignore stantio rimosso). Le 8 restanti, verificate una per una:

## Legittime, lasciate — hanno una ragione tecnica reale

- **`HasXotTable.php` ×5** (`function.alreadyNarrowedType,method.notFound` su
  `method_exists($resource, 'canView'|'canEdit'|'canDelete'|'getRelationship')`):
  `$resource`/`$this` sono tipati `object`/generici perché il metodo serve su risorse
  Filament eterogenee che possono o non possono implementare quei metodi opzionali —
  è duck-typing runtime necessario, non esprimibile staticamente senza un'interfaccia
  condivisa su tutte le risorse (fuori perimetro). Verificato che il pattern è usato
  correttamente (guardia `method_exists` prima della chiamata).
- **`Updater.php` ×2** (`return.type` su `creator()`/`updater()`): `belongsTo($profileClass, ...)`
  con `$profileClass` da `class-string<ProfileContract&Model>` dinamico — limite noto
  di Larastan sull'inferenza generica attraverso variabili di tipo class-string. 42
  consumer reali nel repo, comportamento a runtime corretto.
- **`EnumTrait.php`** (`callable.nonCallable` su `$definition($table)`): `$definition`
  viene da `static::getColumnDefinitions()`, contratto astratto implementato dalle
  sottoclassi con closure — PHPStan non può verificare staticamente che il valore
  restituito sia sempre callable senza il body concreto.
- **`EnumIntegerTrait.php`** (`trait.unused`): già documentato nel codice stesso
  (API di piattaforma pubblicata, nessun consumer in questo repo).

## Zero consumer nel repo — annotati con la stessa motivazione, non cancellati

Verificato con `git log` (storia shallow, un solo commit "first" — non dà segnale) e
grep dei consumer (zero in tutti e tre): `HasUuid.php`, `HasDynamicFillable.php`,
`TypedHasRecursiveRelationships.php`. Aggiunta la nota "Trade-off: API di piattaforma
pubblicata da Xot, nessun consumer in questo repo. Si cancella solo se il pattern viene
ritirato, non per chiudere l'errore." — stesso pattern di `EnumIntegerTrait`, coerente
col fatto che Xot è il modulo base pubblicato per gli altri moduli. Trovato di
passaggio in `HasUuid.php`: un docblock duplicato (due blocchi `/** @phpstan-ignore
trait.unused */` consecutivi, solo il secondo effettivamente attaccato al trait) — bug
meccanico, non correlato alle soppressioni, corretto in un unico blocco.

## Collisioni di case risolte

- `tests/pest.php` (46 righe, boilerplate nudo) vs `tests/Pest.php` (71 righe, stessa
  chiamata `pest()->extend(TestCase::class)->in('Feature', 'Unit')` più un blocco di
  documentazione storica su `pest()->extend()`/`@internal` — datato, verificato,
  corretto). Storia git identica (entrambi nel commit "first", nessun segnale).
  Contenuto funzionale identico, `pest.php` privo della documentazione più recente:
  **rimosso `pest.php`** (Pest carica per convenzione `Pest.php`, verificato zero
  riferimenti al path minuscolo nel repo).
- `app/Http/Http/Controllers/xotbasecontroller.php` + `XotBaseController.php`:
  entrambi 3 righe (`<?php declare(strict_types=1);`, nessun corpo classe), sotto una
  directory `Http/Http/` doppia — debris puro, zero riferimenti nel repo, il
  controller vero è `app/Http/Controllers/XotBaseController.php` (esistente,
  popolato, funzionante). **Rimossa l'intera sottocartella `Http/Http/`.**

## Trovato di passaggio, non risolto (fuori perimetro di questo audit)

- **1211 rilievi PHPMD reali** su `app/` (confermato, stesso numero misurato
  indipendentemente da `base-ptvx-fila5-80`). Un secondo trigger di crash PDepend
  oltre alle classi anonime: `namespace Modules\Xot\Actions\Array;` (`Array` come
  segmento di namespace, PHP valido — `php -l` pulito — ma PDepend non lo digerisce,
  `Unexpected token: Array`). Workaround verificato:
  `./tools/phpmd.phar Modules/Xot/app text ./phpmd-ruleset.xml --exclude "*Actions/Array/*"`.
  Sistemare 1211 rilievi di stile è task a parte (priorità #6 in `docs/quality-audit.md`
  root, `Architecture 50%` di Xot).
- **Coverage non misurato in questo passaggio**: `nc -z 10.100.200.53 3306` fallisce
  (DB di test irraggiungibile, stesso problema già documentato da
  `base-ptvx-fila5-80`), `pest Modules/Xot` non completa entro un timeout ragionevole
  (i test che scrivono restano appesi in attesa di connessione). Non è una regressione
  di questo lavoro: nessuna modifica qui tocca test o codice applicativo oltre alle 3
  rinomine/pulizie di cui sopra.

## Verifica

`phpstan analyse Modules/Xot` → `[OK] No errors` dopo tutte le modifiche.
`./tools/phpinsights.sh Modules/Xot` → Code 77.6, Complexity 100, Architecture 50.0,
Style 90.1 (invariati rispetto alla misura di `base-ptvx-fila5-80` di poche ore fa —
nessuna regressione dalle rinomine/pulizie).
