# Handoff — PHPStan fix modulo Geo (2026-07-16)

## Esito
- PHPStan livello max: **50 → 0 errori reali** su `Modules/Geo`.
- Unico residuo: avviso di config non ignorabile «Ignored error pattern
  #@mixin contains unknown class# was not matched» — artefatto dello scoping a
  singolo modulo (`laravel/phpstan.neon`, immutabile), **non** un errore di
  codice Geo.
- Commit `c19eb30` pushato su `laraxot/dev` (module_geo_fila5), rebasato su
  `377cb8e`.

## Root cause per categoria

1. **`new.noConstructor` (44 occorrenze, i test)** — Le Action usano
   `QueueableAction`, non hanno costruttore e risolvono le dipendenze via
   `app()`. I test le istanziavano con `new Action($stub)`. **Fix del test**
   (non dell'Action): bind dello stub nel container
   (`app()->instance(Dep::class, $stub)`) + resolve dell'Action con `app()`.
   Per le catene (Filter → CalculateDistance → Matrix) basta bindare la
   dipendenza foglia.

2. **`ignore.unmatchedIdentifier` (3, i widget)** — `@phpstan-ignore
   property.defaultValue` obsoleti su `LatLngWidget`, `LocationWidget`,
   `WebbingbrasilMap`: l'errore sottostante era già risolto. Rimossi (più un
   `@var view-string` duplicato in `LocationWidget`).

3. **`method.childReturnType` (1, Dashboard)** — `getWidgets(): list<class-string>`
   non covariante con `XotBaseDashboard::getWidgets(): array<string, mixed>`.
   Allineato a `array<class-string>` (come gli altri moduli).

4. **`missingType.generics` (1, BuildGeoMapWidgetPayloadActionTest)** — risolto;
   file riformattato da Pint.

## Conversione a Pest
Tutti i test toccati passati da `PHPUnit\Framework\Assert` a `expect()` /
`->toThrow()`. Corrette due aspettative su `ElevationException` (risposta vuota
/ struttura non valida) al messaggio realmente emesso da
`ElevationException::invalidResponse()`: «Risposta non valida dal servizio di
elevazione» (i messaggi precedenti non esistevano nel codice).

## Quality gate
- **PHPStan**: 0 errori reali (vedi sopra).
- **Pest**: `Elevation` + `IPGeolocation/GetLocationFromIPActionTest` (LightTestCase)
  **passano**. I test `TestCase` (Calculate/Filter) falliscono in setUp per
  problema d'ambiente **pre-esistente e trasversale al repo**: `.env.testing`
  impone `DB_CONNECTION=mysql` mentre `phpunit.xml` imposta
  `DB_DATABASE=:memory:` → «Unknown database ':memory:'». I corpi dei test
  convertiti non usano il DB (stub bindati nel container).
- **phpmd**: solo finding pre-esistenti (StaticAccess ai SafeCast di Xot —
  pattern di progetto; `err_code`/`err_message` non camelCase nei widget).
  Nessun nuovo finding introdotto.
- **phpinsights**: eseguibile con `--disable-security-check`, output di summary
  instabile in questo ambiente (plugin ForbiddenSecurityIssues).
- **Pint**: applicato solo ai file in scope; revertite le modifiche fuori scope.

## Documentazione / regole
- Nuovo: `laravel/Modules/Geo/docs/queueable-action-testing.md`.
- Rinforzata la regola canonica
  `docs/wiki/rules/action-execution-pattern.md` con sezione «Nei test: bindare,
  non iniettare» (repo root, non ancora committata nel repo padre).

## Follow-up per il prossimo agente
- L'ambiente Pest è rotto repo-wide (mismatch `:memory:` / mysql). Va risolto a
  monte per far girare i test `TestCase`.
- Valutare se allineare la PHPDoc di `XotBaseDashboard::getWidgets()` a
  `array<class-string>` (modulo Xot) per coerenza.
