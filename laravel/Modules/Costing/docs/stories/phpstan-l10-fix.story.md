---
title: "Costing — PHPStan L10 fix + test isolation + coverage"
type: story
module: Costing
epic: 18
story_id: COSTING-18.16
slug: phpstan-l10-fix
status: done
created: 2026-09-06
updated: 2026-09-07
language: it-IT
related:
  - ../../Xot/docs/stories/18.16.costing-phpstan-l10-fix.story.md
  - ../../../../docs/chat/2026-09-06-pest-v5-done-plus-phpstan-parallel-wave.md
  - ../../../../docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md
tags: [phpstan, level-10, pest, database-transactions]
---

# COSTING-18.16 — PHPStan level max a zero + isolamento test

## Update 2026-09-07

Regressione da `phpstan.neon` (owner commit `6eacf7c38`, Larastan/Pest
extension includes disabilitati) ha fatto risalire 22 findings (3 reali,
19 strutturali Pest). Fix + dettaglio completo in `docs/coverage.md`. Corretto
anche un edit concorrente non tracciato che aveva rimosso `use HasXotFactory;`
da `WorkCenter.php` — ripristinato prima del commit.

## Nota su story precedente

Una story gemella esiste in `Modules/Xot/docs/stories/18.16.costing-phpstan-l10-fix.story.md`
(creata per errore lì da uno swarm precedente — la sede corretta e' il modulo owner).
Non cancellata/rinumerata, solo collegata: riportava "7 errori" come baseline, numero
maturato prima che la regressione di `HasXotFactory::factory()` (vedi
`docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md`) venisse
sistemata da un altro agente nel modulo Xot. Con quel fix gia' in `Modules/Xot` al
momento in cui questa story e' iniziata, la baseline reale per Costing era 2 errori,
non 7.

## Problema

`./vendor/bin/phpstan analyse Modules/Costing --memory-limit=-1` (livello max, da
`phpstan.neon` esistente, non modificato) partiva da **2 errori**, entrambi
`parameter.missing` (non-ignorable):

```
Line 57  Method Modules\Costing\Models\WorkCenter::factory() overrides method
         Modules\Xot\Models\XotBaseModel::factory() but misses parameter #1 $count.
Line 57  Method Modules\Costing\Models\WorkCenter::factory() overrides method
         Modules\Xot\Models\XotBaseModel::factory() but misses parameter #2 $state.
```

Causa: `WorkCenter` dichiarava un proprio `public static function factory(): WorkCenterFactory`
senza parametri, aggiunto in una sessione precedente (commit `26dad08`, poi
`f0318f9`) per aggirare un momento in cui `HasXotFactory` (nel modulo Xot) era priva
del metodo `factory()` pubblico. Da quando quella trait e' stata ripristinata con la
firma corretta (`public static function factory($count = null, $state = [])`,
delega a `newFactory()` + risoluzione automatica della factory via
`GetFactoryAction`), l'override in `WorkCenter` e':

1. **Incompatibile per firma** — LSP violation, da qui i due errori PHPStan;
2. **Ridondante** — la trait risolve gia' `WorkCenterFactory` per convenzione di nome,
   e la override perdeva pure il supporto a `->count()`/`->state()`.

Un secondo problema, scoperto durante la verifica Pest (non PHPStan, ma bloccante per
il gate "coverage deve salire"): `Modules\Costing\tests\TestCase` dichiarava
`protected $connectionsToTransact = ['sqlite', 'xot'];` **senza includere `'costing'`**,
la connessione reale usata da `BaseModel`/`WorkCenter`. Risultato: le scritture di test
sulla tabella `costing_work_centers` non venivano racchiuse in una transazione e non
venivano fatte rollback, quindi due run consecutivi di `WorkCenterTest` fallivano con
`UniqueConstraintViolationException` (dati residui del run precedente). Lo stesso
pattern (`connection modulo` incluso in `connectionsToTransact`) e' gia' applicato in
`Job`, `Activity`, `Customer` e altri moduli — qui mancava.

## Soluzione

1. Rimossa l'override `WorkCenter::factory()`: la trait `HasXotFactory` (gia' in uso
   nel model) fornisce l'implementazione corretta e compatibile.
2. Rimosso l'`use` ora non necessario di `WorkCenterFactory` in `WorkCenter.php`
   (import diventato inutilizzato dopo la rimozione dell'override — confermato da
   PHPInsights `Unused uses`).
3. Pulizia cosmetica minima nello stesso file (riga vuota doppia residua) — cambio
   diretto, non un refactor a parte.
4. Aggiunta `'costing'` a `connectionsToTransact` in
   `Modules/Costing/tests/TestCase.php`, allineando il modulo al pattern gia' usato
   altrove (Job/Activity/Customer).
5. Applicata (non distruttiva) la migrazione mancante `costing_work_centers` sul DB di
   test (`workorder_data_test`, connessione `costing`) — era `Pending`, mai `fresh`/
   `refresh`, solo forward: `php artisan migrate --env=testing --database=costing
   --path=Modules/Costing/database/migrations`. Nessun dato esistente toccato.
6. Ripulita la tabella di test dai residui prodotti dai run senza transazione
   (`truncate` su tabella di TEST, connessione `costing_test`, popolata solo da
   `WorkCenterFactory` in questa sessione — non dati applicativi).

## Acceptance criteria

1. [x] `laravel/phpstan.neon` non modificato.
2. [x] `./vendor/bin/phpstan analyse Modules/Costing --memory-limit=-1` → `[OK] No errors`
       (0 errori, da 2).
3. [x] Nessun `@phpstan-ignore` introdotto.
4. [x] Nessun `mixed` introdotto o lasciato dove evitabile (nessuno presente nel diff).
5. [x] `./vendor/bin/pest Modules/Costing/tests` → tutti i test passano, **due run
       consecutivi** senza `UniqueConstraintViolationException` (prova dell'isolamento
       transazionale).
6. [x] Coverage: 10/10 test pre-esistenti passano; nessuna regressione. Vedi
       `docs/coverage.md` per il dettaglio prima/dopo.
7. [x] PHPMD (`./tools/phpmd.sh Modules/Costing/app text ...`) — nessun nuovo finding
       introdotto dal diff (i due `CamelCasePropertyName` residui sono convenzione
       XotBase ereditata da ogni modulo, fuori scope).
8. [x] Git sync sul repo indipendente di Costing (`laraxot/dev`), nessun force-push.

## Dev Agent Record

### Baseline vs finale

| Metrica | Prima | Dopo |
|---|---:|---:|
| PHPStan errori (`Modules/Costing`, level max) | 2 | 0 |
| Pest test passati | 10 (ma falliscono al 2° run per dati residui) | 10, stabili su run ripetuti |
| Test con isolamento transazionale reale | no (`costing` assente da `connectionsToTransact`) | si |

### File toccati

- `app/Models/WorkCenter.php` — rimossa override `factory()` incompatibile + import
  inutilizzato + pulizia riga vuota.
- `tests/TestCase.php` — aggiunta `'costing'` a `connectionsToTransact`.
- `docs/coverage.md` — numeri prima/dopo.
- `docs/stories/phpstan-l10-fix.story.md` — questa story.

### Verifiche eseguite

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Costing --memory-limit=-1   # 0 errori
./vendor/bin/pest Modules/Costing/tests                          # 10 passed
./vendor/bin/pest Modules/Costing/tests                          # 10 passed (ripetuto, niente duplicate-key)
./tools/phpmd.sh Modules/Costing/app text cleancode,codesize,controversial,design,naming,unusedcode
./tools/phpinsights.sh analyse Modules/Costing --no-interaction --disable-security-check --composer=composer.lock
```

Nota su PHPInsights: un hook post-edit di questa sessione ha segnalato "non installato,
rimosso per incompatibilita' con Pest 5" — verificato empiricamente **non corretto** in
questo ambiente: `vendor/bin/phpinsights` e' presente (`nunomaduro/phpinsights` dev-master)
ed e' stato eseguito con successo (serve `--composer=composer.lock` esplicito, altrimenti
cerca il lock nella cwd sbagliata). Risultato sul modulo: Code 97.9, Complexity 100,
Architecture 94.1, Style 90.4. I finding di stile residui (public property nei
Provider, classi Provider non `final`) sono la convenzione XotBase applicata a ogni
modulo del repo (property `$name`/`$module_dir`/`$module_ns` lette dalla base class) —
non toccati: cambiarli solo qui romperebbe la coerenza col resto del framework, fuori
scope di questa story.
