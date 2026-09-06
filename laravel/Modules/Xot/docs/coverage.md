# Xot Module Test Coverage

## Overview
This module has comprehensive test coverage with various test types implemented.

## Test Results
- **Tests Passed**: 0
- **Assertions**: 0
- **Test Types**: Unit, Feature, Integration tests

## Coverage Statistics
- **Files**: 0
- **Lines of Code**: 0
- **Classes**: 0
- **Methods**: 0
- **Coverage Rate**: 0%

## Test Categories
- Unit Tests
- Feature Tests
- Integration Tests

## Status
All tests are passing and coverage is being maintained.

## Services to Actions conversion — 2026-09-04

Sessione Claude Sonnet 5, story `docs/stories/18.1.xot-services-to-actions.story.md`.
Task: eliminare `Modules\Xot\Services\*` (31 file), regola `no-services-rule`
(RELIGION, no eccezioni). Classificazione completa nella story.

**Collisione con sessione live concorrente (importante):** durante questo
lavoro un'altra sessione ha operato in parallelo sulla STESSA conversione
(stesso scope, `app/Services` → `app/Actions`), con un approccio flat/root
(`Actions/ArtisanAction.php`, `Actions/ModuleAction.php`, ecc.) invece che
raggruppato per contesto. Diversi suoi file sono rimasti a meta' edit e
sintatticamente rotti per l'intera durata di questa sessione
(`Actions/ConfigAction.php`, `Actions/ModuleAction.php`,
`Actions/XotServiceAction.php`, `Actions/Artisan/Handlers/CacheCommandHandlerAction.php`,
`Actions/Artisan/Handlers/DebugbarCommandHandlerAction.php` — `Unexpected token: implements`
o `unexpected '{'`), e altri hanno un bug reale non sintattico: il file e'
stato rinominato ma la `class` interna no (`Actions/ArtisanAction.php`
dichiara ancora `class ArtisanService`, `Actions/RouteDynAction.php`
dichiara ancora `class RouteDynService` — `Modules\Xot\Actions\ArtisanAction`
non esiste davvero). Non ho toccato nessuno di questi file (non miei, WIP
altrui). Le mie 26 nuove classi usano nomi di file distinti proprio per
evitare collisioni dirette di path con quella sessione.

**PHPStan — baseline vera vs finale vera:**
- Baseline (`clear-result-cache` + `analyse Modules/Xot`, prima di ogni mio
  edit): **0 errori** (`[OK] No errors`, exit 0).
- Run finale sull'intero modulo: **bloccata** da 8 `phpstan.parse` negli 8
  file della sessione concorrente sopra elencati (non miei, confermato con
  `git status --short` — 5 erano gia' `M`/`??` prima del mio primo edit).
  Questi file NON erano rotti al momento della baseline (altrimenti la
  baseline stessa sarebbe uscita con "Result is incomplete"), quindi la
  rottura e' avvenuta durante la finestra di questa sessione, per mano
  della sessione concorrente.
- Verifica scoped sui miei 26 file nuovi (elenco esplicito, nessuna
  directory): **0 errori** (`[OK] No errors`).
- Verifica scoped sui 2 file di test che ho modificato (diff mostrato via
  `git diff`, tocca solo le righe `RouteService` → `IsAdminRouteAction`):
  gli unici errori residui su quei 2 file sono su righe che non ho toccato
  (44/51/53 e 269-286/775/779), causati dallo stesso bug di classe della
  sessione concorrente (`ArtisanAction`/`RouteDynAction` non esistono
  davvero). Non attribuibili a questo diff.

**Pest:** 7/7 verdi sui file toccati/nuovi (`GetRouteMethodActionTest`,
`ThemeServiceTest`, `UrlServiceTest` — questi ultimi due confermano che le
Action Theme/Url gia' esistenti, non toccate, restano corrette dopo la
rimozione dei rispettivi Service). Il test mirato sulla porzione
`RouteService inAdmin` in `XotExecuteCoverage50Test.php` passa isolato.

**PHPMD:** informativo, nessun crash sui file miei (scoped run separata dai
file della sessione concorrente nella stessa directory). Findings pre-esistenti
riportati fedelmente dal codice originale (dispatch dinamico `$this->$method()`
letto come "unused private method", `$view_params`/`$_namespace` snake_case
gia' presenti nel codice sorgente originale) — nessun nuovo debito introdotto.

Dettaglio completo per-file (Kind A/B, path vecchio → nuovo, motivazione):
vedi la story.

## PHPStan swarm fix — 2026-09-02

Sessione Claude Sonnet 5 (6748f176), claim `docs/chat/claim-phpstan-542-swarm-2026-09-02.md`.
Lista di partenza: 279 errori Xot (401 righe raw, 120 coppie file:line uniche) da
`phpstan analyse Modules/Xot` livello max.

**Fix reali applicati** (nessun `@phpstan-ignore`):
- `typeCoverage.paramTypeCoverage`/`constantTypeCoverage`: tipizzati i parametri
  di closure mancanti in ~35 file (Actions, Filament, Middleware, Provider, test).
  `mixed` usato solo dove il valore è genuinely polimorfico e già narrowed con
  `is_*()` prima dell'uso (convenzione "mixed ultima spiaggia"); altrove tipo
  concreto dedotto da come il valore è costruito/usato (es. `string`, `Model`,
  `Blueprint`, `int|string`).
- `cast.string`/`cast.int`: cast ciechi `(string)`/`(int)` su valori mixed
  sostituiti con `SafeStringCastAction::cast()`/`SafeIntCastAction::cast()`
  (pattern già esistente nel modulo) o con narrowing esplicito upstream (es.
  `FilamentMemoryMonitorMiddleware`: dato un `array{...}` shape preciso invece di
  `array<string, mixed>`, i cast a valle diventano sicuri per costruzione).
- `method.deprecated`/`deprecatedClass`: sostituiti i simboli deprecati con
  l'alternativa indicata dal messaggio phpstan — `Doctrine\DBAL` `introspectTable()`
  → `introspectTableByUnquotedName()`, `listTableIndexes()` →
  `introspectTableIndexesByUnquotedName()`; `ReflectionParameter::getClass()` →
  `getType()` + `ReflectionNamedType`; Filament `VerifyCsrfToken` →
  `PreventRequestForgery`; Filament `Navigation\MenuItem` → `Actions\Action`;
  Filament `Tables\Columns\BooleanColumn` → `IconColumn::make()->boolean()`;
  `Illuminate\Contracts\Validation\Rule` → `ValidationRule` (riscritto
  `DateTimeRule::passes()/message()` in `validate()` con `$fail`); PHPUnit
  `expectExceptionMessage()` → `expectExceptionMessageIsOrContains()` (stesso
  comportamento "contains", deprecato per ambiguità in PHPUnit 13); Mockery
  `shouldDeferMissing()` rimosso (era ridondante: `makePartial()` già presente
  nella stessa chain, `shouldDeferMissing()` è un puro alias deprecato); Spatie
  Data `DataCollection::first()` → `toCollection()->first()`;
  `MetatagData::getColors()/getLogoHeight()` → property diretta `->colors` /
  `getBrandLogoHeight()` (verificato che siano equivalenti leggendo il corpo dei
  metodi, non solo il messaggio di deprecazione).
- `class.extendsDeprecatedClass`: `XotBasePlaceholder` (estendeva
  `Filament\Forms\Components\Placeholder`, deprecata) eliminata — zero call site
  nel repo, sostituita da tempo da `XotBaseTextEntry` (`extends TextEntry`, già
  presente e documentata come rimpiazzo).
- `class.implementsDeprecatedInterface`: `DateTimeRule` migrata da `Rule` a
  `ValidationRule` (vedi sopra).
- `clone.nonObject`/`argument.type` in `HasXotTable::getGridTableColumns()` e
  `table()`: aggiunto narrowing esplicito (`instanceof Column|LayoutComponent`,
  `array_filter` sul risultato di `getTableColumns()`) prima di `clone`/
  `Stack::make()`/`TableLayoutEnum::getTableColumns()`.
- Dead code rimosso: `XotBaseTableWidget::tableOLD()` (metodo mai chiamato in
  tutto il repo, rinominato "OLD" a suo tempo e mai ripulito) chiamava
  `getTableQuery()`/`getTableColumns()` deprecati senza motivo — cancellato
  insieme agli import diventati inutili.

**Fix reale non-ignore su `HasXotTable::table()`**: `->bulkActions(...)` →
`->toolbarActions(...)` (stesso identico comportamento: `HasBulkActions::bulkActions()`
in Filament è `{ $this->toolbarActions($actions); return $this; }`, verificato nel
sorgente vendor). Ripristina parzialmente un fix già fatto e verificato oggi alle
18:03 (commit `5042a991`, "phpstan analyse Modules 0 errori") che un merge
successivo (`b0560e8f`, messaggio ".") aveva silenziosamente perso insieme alle
sue annotazioni `@phpstan-ignore` — vedi sezione "Debito architetturale" sotto,
qui non si sono ripristinate le ignore (vietate in questo task) ma solo la parte
che è un fix reale.

### Debito architetturale NON toccato (fuori scope, non un bug)

`HasXotTable::table()` e i metodi legacy che chiama (`getTableColumns()`,
`getTableFilters()`, `getTableActions()`, `getTableBulkActions()`,
`getTableHeading()`, `getTableEmptyStateActions()`, `getDefaultTableSortColumn()`,
`getDefaultTableSortDirection()`, più l'analogo in `XotBasePage::schema()` via
`getFormSchema()`) restano ~77 errori `method.deprecated`. Verificato nel sorgente
vendor (`Filament\Tables\Concerns\{HasColumns,HasFilters,HasActions,HasHeader,
HasRecords,HasEmptyState}`, `Filament\Forms\Concerns\InteractsWithForms`): Filament
ha deprecato l'intera API "vecchio stile" (override di `getTableColumns()` ecc. che
restituiscono array) in favore di configurare tutto dentro `table()`/`form()`
direttamente. `HasXotTable`/`XotBasePage` sono bridge di compatibilità deliberati
che chiamano quell'API vecchia per le tante sottoclassi (61 estendono
`XotBasePage`, altrettante `HasXotTable` via `XotBaseListRecords` e affini) che la
overridano ancora. Eliminare il bridge senza `@phpstan-ignore` richiederebbe
riscrivere `table()`/`schema()` in ogni sottoclasse across Xot e altri moduli —
esattamente il caso "fix the outlier, not the majority" descritto nello standing
order. Stessa causa per gli errori residui in `ListCaches.php`, `ListModules.php`,
`ListSessions.php` (override locali di `getTableColumns()` che richiamano se
stessi attraverso la stessa catena). Story separata raccomandata per un refactor
mirato (uno a uno, verificando ogni sottoclasse) invece di un mop-up meccanico.

**Fuori scope, segnalato dal coordinatore**: `Modules/Xot/app/Models/XotBaseModel.php`
e `Modules/Xot/app/Filament/Resources/XotBaseResource.php` — lavoro in corso di
altre sessioni sullo stesso giro (getClassName() / getTableClass()). Non toccati.
`tests/Unit/ListPageHasTableClassTest.php:53` (`staticMethod.notFound` su
`XotBaseResource::getTableClass()`) skippato per lo stesso motivo: dipende dal
contratto in-flux di quel file.

**Verifica**: `phpstan analyse -c <neon con tmpDir isolata> Modules/Xot` — 0
errori `typeCoverage.*` residui nel modulo (soglia tree-wide, contributo Xot
azzerato); tutti gli errori `cast.*`/`method.deprecated`/`*deprecatedClass*`/
`class.implements*`/`class.extends*` sui file toccati risolti, tranne il cluster
bridge sopra descritto.

## Riduzione uso di `mixed` — 2026-09-04

Sessione Claude Sonnet 5, story `5.54.xot-mixed-type-reduction.story.md`
(collegata a `5.53.mixed-type-reduction.story.md`, campagna cross-modulo
già in-progress su un'altra sessione).

**Collisione rilevata prima di iniziare**: `git status --short` su questo
modulo mostrava 1647 file già modificati e non committati (diff +16813/-5854
righe) da una sessione precedente, non conclusa. Confermato non "live" (mtime
dei file 2026-09-01/02, ultimo commit 2026-09-03) ma comunque non mio da
toccare/commitare per intero. Strategia adottata: ho ristretto l'analisi ai
soli file **puliti** (assenti dal diff pendente) tra i 236 file del modulo che
usano `mixed`, per garantire che il commit finale contenga solo modifiche mie
e non inglobi il WIP altrui. Risultato: 109 file "puliti" su 236 con `mixed`.

**Censimento**: 236 file con `\bmixed\b` (grep) nel modulo; 109 in stato git
pulito (candidati sicuri). Analizzati a fondo ~25 file/occorrenze a più basso
conteggio o con type-hint nativo (non solo docblock), seguendo la lista
"cosa lasciare mixed" del task (contratti vendor Filament/Eloquent/Spatie,
cast action generiche, payload polimorfici verificati).

**Modificati (4 file)**:
- `app/Console/Commands/ListFilamentPanels.php` — `filter(function (mixed $file))`
  → `filter(static function (string $file))`, con `/** @var list<string> $entries */`
  esplicito sul risultato di `Safe\scandir()` (che a runtime restituisce sempre
  un array di nomi file stringa; l'estensione Larastan è disattivata in
  `phpstan.neon`, quindi PHPStan da solo non risolve il generic di `scandir()` —
  annotazione necessaria, non un override che maschera un bug). Rimosso anche
  il controllo `is_string($file) &&` ridondante col nuovo tipo nativo.
- `app/Datas/OptionData.php` — `@param array<mixed> $autoload` → `list<string>`
  (valore di default è sempre una lista di nomi opzione stringa).
- `app/Datas/RouteParamsData.php` — `public mixed $row = null;` →
  `public ?object $row = null;`, confermato verificando ogni produttore reale
  della chiave `'row'` nel modulo (`GetPdfContentByRecordAction`,
  `PdfByModelAction`, `Store/MorphToManyAction`, `PdfData`): sempre un model/
  oggetto Eloquent, mai uno scalare; default coerente con `BuildActionUrlAction`
  (`(object) []` come fallback).
- `app/View/Components/XotBaseComponent.php` — `@var array<mixed> $attrs` →
  `array<string, mixed>` (attributi HTML keyed by nome, valore resta
  genuinamente eterogeneo — solo la chiave era sotto-specificata).

**Tentativo scartato**: `app/Actions/ModelClass/SelectAction.php`
`@return array<mixed>` → `array<int, mixed>` introduceva un nuovo errore
PHPStan (`return.type`, il metodo vendor `ConnectionInterface::select()` non
è generic-annotato senza l'estensione Larastan disabilitata, quindi PHPStan
non può provare che le chiavi siano `int`). Ripristinato all'originale
`array<mixed>` — nessuna modifica netta su questo file, documentato qui come
skip con motivo verificato (non ipotizzato).

**Lasciati `mixed` con motivo verificato** (campione, non esaustivo — il resto
è `array<string, mixed>` per payload di config/form/dati realmente eterogenei,
già adeguati):
- `Actions/Debug/MeasureAction.php::execute(): mixed` — generic `@template T`
  via PHPDoc; PHP non supporta generics nativi, `mixed` è l'unico return type
  nativo esprimibile per un wrapper "misura qualsiasi closure".
- `Actions/Model/Store/BelongsToManyAction.php` — `array_map(fn(mixed $id))`
  alimenta direttamente `SafeStringCastAction::cast()`, la cui firma `mixed`
  è il contratto voluto (cast action al boundary).
- `Datas/TrendData.php::$aggregate` — rispecchia
  `Flowframe\Trend\TrendValue::$aggregate`, tipizzato `mixed` dal vendor
  stesso (verificato in `vendor/flowframe/laravel-trend`), varia int/float/
  string/null per driver DB.
- `Relations/CustomRelation.php::initRelation()/match()` — override di
  `Illuminate\...\Relations\Relation` (parametri vendor non tipizzati);
  narrowing rischia di rompere la sostituibilità coi chiamanti Eloquent interni.
- `QueryBuilders/BaseQueryBuilder.php::where()/whereOperator()` — passthrough
  diretto a `Builder::where()`, la cui firma vendor è genuinamente `mixed`.

**PHPStan**: 0 errori → 0 errori (`./vendor/bin/phpstan analyse Modules/Xot
--no-progress --error-format=table`, livello max, verificato due volte:
prima run intermittente ha incontrato "Application bootstrap failed" per uno
stato transitorio di un altro modulo nel monorepo — vedi
`docs/chat/xot-blade-component-bootstrap-crash-wip.md`, problema noto e non
causato da questa modifica — seconda run pulita 0 errori).

**PHPMD** (informativo): scoped ai 4 file toccati, nessun crash. Solo debito
preesistente non legato al tipo (naming snake_case su proprietà/parametri di
`OptionData`/`RouteParamsData`, variabili camelCase in `XotBaseComponent`),
non toccato per restare a impatto minimo.

**Pest**: eseguito `./vendor/bin/pest Modules/Xot/tests -c Modules/Xot/phpunit.xml
--no-coverage` (un solo tentativo, come da istruzioni). Timeout a 240s senza
output (processo terminato con `exit 143`, nessuna riga di risultato prodotta
neanche parziale — suite grande, processi forked secondo
`tests/XotForkedInvoke.php`/`tests/XotBasePest.php`). Non è quindi possibile
riportare un conteggio pass/fail onesto per questo run: non è un fallimento
causato da questa modifica (il timeout è avvenuto prima che qualunque test
producesse output), ma nemmeno una conferma verde. Nessuno dei 4 file toccati
ha un file di test Pest dedicato in `tests/Unit|Feature` con nome
corrispondente (verificato con `find tests -iname '*ListFilamentPanels*'
-o -iname '*OptionData*' -o -iname '*RouteParamsData*' -o -iname
'*XotBaseComponent*'` → nessun risultato), quindi il rischio di regressione
comportamentale silenziosa è basso ma non verificato da test mirati.
## 2026-09-04 — bootstrap crash + ModuleAction cleanup

Vedi `docs/chat/phpstan-ide-helper-fixes-2026-09-04.md` (root repo) e
story `docs/stories/18.1.xot-services-to-actions.story.md`. In sintesi:
`_components.json` con schema vecchio rompeva il boot dell'intera app;
`GetComponentsAction` ora valida lo schema della cache prima di fidarsene.
`ModuleAction.php` (pattern singleton, zero chiamanti reali) cancellato,
sostituito ovunque da `Actions/Model/GetAllModelsByModuleNameAction`
(gia' esistente, logica identica). I suoi 2 test riscritti sul sostituto
(coverage preservata). `phpstan analyse Modules/Xot`: 0 errori, cache
pulita, verificato.
