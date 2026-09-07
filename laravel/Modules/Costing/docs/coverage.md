---
title: "Coverage del modulo Costing"
type: report
module: Costing
updated: 2026-09-06
qmd: "coverage costing pest phpstan work center connections to transact"
---

# Coverage del modulo Costing

## Misura del 6 settembre 2026

Comando canonico (stesso pattern AD-25 di Rating/altri moduli — serve un
`Modules/Costing/phpunit.xml` proprio, con lo stesso blocco `<php>` del
`phpunit.xml` di root, altrimenti l'ambiente non risolve `APP_ENV=testing` e i test
puntano al DB reale invece che a quello di test):

```bash
cd laravel
XDEBUG_MODE=coverage ./vendor/bin/pest Modules/Costing/tests -c Modules/Costing/phpunit.xml --coverage --min=0
```

| | Prima (baseline sessione) | Dopo |
|---|---:|---:|
| **Coverage di riga** | non misurabile (test instabili, vedi sotto) | **100.0 %** |
| Test passati | 10 (falliva il 2° run consecutivo) | 10, stabili su run ripetuti |
| Falliti | 0 al 1° run, 2-3 al 2° run (dati residui) | 0 |
| Asserzioni | 19 | 19 |
| PHPStan (`Modules/Costing`, level max) | 2 errori | 0 errori |

## Cosa e' cambiato

1. **`app/Models/WorkCenter.php`** — rimossa un'override incompatibile di
   `factory()` (LSP violation, 2 errori PHPStan `parameter.missing`) e l'import
   diventato inutile; la trait `HasXotFactory` gia' in uso fornisce l'implementazione
   corretta.
2. **`tests/TestCase.php`** — `connectionsToTransact` non includeva `'costing'`, la
   connessione reale di `BaseModel`/`WorkCenter`: le scritture di test non venivano
   racchiuse in transazione ne fatte rollback, quindi un secondo run consecutivo di
   `WorkCenterTest` falliva con `UniqueConstraintViolationException` su dati
   residui del run precedente. Allineato al pattern gia' in uso in Job/Activity/
   Customer.
3. **`phpunit.xml`** (nuovo) — perimetro di coverage scoperto su `./app`, stesso
   blocco `<php>` del root per non perdere `APP_ENV=testing` (e quindi la
   connessione al DB di test) quando si passa `-c`.
4. Migrazione `2026_08_30_220000_create_costing_work_centers_table` era `Pending`
   sul DB di test: applicata in avanti (mai `fresh`/`refresh`), nessun dato
   esistente toccato.

## Dettaglio per classe (100.0% su tutte)

```
Datas/MarkupDecision ................. 100.0%
Models/BaseModel ...................... 100.0%
Models/WorkCenter ..................... 100.0%
Providers/CostingServiceProvider ...... 100.0%
Providers/EventServiceProvider ........ 100.0%
Providers/RouteServiceProvider ........ 100.0%
Support/MarkupResolver ................ 100.0%
```

## Precondizioni che invalidano la misura

Stesse di Rating (vedi `Modules/Rating/docs/coverage.md`):

1. `bootstrap/cache/config.php` non deve esistere.
2. Nessun altro agente deve stare scrivendo sullo stesso tree in quel momento.
3. Il DB di test (`workorder_data_test`, connessione `costing`) deve avere la
   tabella `costing_work_centers` (migrazione applicata, vedi sopra).

## Storico

| Data | Passati | Falliti | Coverage | PHPStan |
|---|---:|---:|---|---:|
| 2026-09-06 (baseline) | 10 | 0 (2-3 al 2° run) | non misurabile | 2 |
| 2026-09-06 (fix) | 10 | 0 | 100.0 % | 0 |
| 2026-09-07 (regressione + fix) | 10 | 0 | non rimisurata | 22 -> 19 (3 reali fissati, 19 residuo strutturale Pest) |

## Update 2026-09-07

Il baseline "0 errori" sopra e' stato invalidato dalla modifica owner
`6eacf7c38` (22:05 UTC, 2026-09-06) che disabilita gli include Larastan/
Carbon/Pest in `phpstan.neon` repo-wide — vedi second-brain
`project_phpstan_neon_larastan_disabled_2026_09_07.md`, owner ha confermato
di tenere quella config cosi' com'e'. Scan completo (`Modules/` intero, gli
scan isolati per modulo sottostimano): 22 findings, 3 reali:

- `tests/Feature/WorkCenterTest.php:15` — `method.notFound` su
  `WorkCenter::query()->forContext()` + 2x `method.nonObject` a cascata
  (`->pluck()->all()`): senza Larastan, PHPStan non risolve gli scope
  Eloquent dinamici. Fix: annotazione `@method static Builder<WorkCenter>
  forContext(string $contextCode)` sulla classe `WorkCenter` — PHPStan
  nativo (non Larastan) risolve i `@mixin`/`@method` generici dichiarati
  sul model quando si chiama tramite `Builder<Model>` (verificato: fix
  sufficiente da solo, senza toccare `phpstan.neon`).
- 19 residui `method.internalClass` su `expect()->toBe()`/`toContain()` in
  `WorkCenterTest.php` e `MarkupResolverTest.php` — **non risolvibili nei
  vincoli attuali** (stessa classe strutturale del blocker `@mixin` gia'
  documentato; estensione Pest disabilitata, `@phpstan-ignore` vietato da
  policy). Lasciati com'erano, non sono un difetto di codice.

**Incidente concorrente rilevato e corretto**: durante questo fix, un edit
non documentato e non tracciato (nessun claim in `docs/chat/`, nessun lock)
ha rimosso `use HasXotFactory;` (e il relativo import) da `WorkCenter.php`,
in contraddizione con la story del 2026-09-06 sopra che dichiara esplicitamente
la trait "gia' in uso nel model" come parte della soluzione. Nessun codice
vivente chiama oggi `WorkCenter::factory()` (i test usano `WorkCenterFactory::new()`
direttamente), ma la rimozione ripete lo stesso pattern di incidente ricorrente
gia' documentato su `HasXotFactory.php` stesso ("rimosso per errore e
ripristinato 3 volte") — trait ripristinata qui prima di committare.

PHPInsights (config ephemeral in `/tmp`): Code 97.9, Complexity 100,
Architecture 94.1, Style 92.8 — tutti sopra min-70.
