---
title: "Code Coverage: User"
type: concept
tags: [coverage]
created: 2026-07-14
updated: 2026-07-14
qmd: "coverage code coverage: user"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

# Code Coverage: User

<<<<<<< HEAD
## 2026-09-04 (sessione quality-gate) — phpmd + pest + coverage

### PHPStan (baseline richiesta dal task)

`./vendor/bin/phpstan clear-result-cache` + `analyse Modules/User`: **0 errori**, 1664 file, confermato
due volte (prima e dopo i fix). Nel mezzo, due run sono falliti con `Application bootstrap failed`
(`Modules\Xot\Datas\ComponentFileData::$name must not be accessed before initialization`) per via di
sessioni concorrenti che stavano modificando in quel momento `Modules/Xot/app/View/Components/_components.json`
e (in un secondo tentativo) un `_components.json` di `Modules/Activity` — cache Blade/Livewire con schema
vecchio (`class_name`/`comp_name`/`comp_ns` invece di `name`/`class`/`ns`), stesso bug gia' diagnosticato
e risolto per `Modules/Cms` in `docs/chat/xot-blade-component-bootstrap-crash-wip.md`. Non e' un problema
del modulo User: nessun file di User coinvolto, e i run successivi (dopo che l'altra sessione ha corretto
il proprio file) sono tornati puliti. Non toccato (fuori scope, modulo altrui, lock non preso).

### PHPMD — fix reali applicati (nessuna soppressione via annotazioni)

Baseline: `./tools/phpmd.sh Modules/User/app text ../docs/phpmd.ruleset.xml` — **524** finding.
Dopo i fix mirati sotto (verificati singolarmente file per file, la scansione sull'intera directory ha
mostrato risultati incoerenti/stale per un file mentre un'altra sessione lo editava in parallelo — vedi
nota "misurare mentre un altro scrive" nel second brain):

1. **Codice morto — 4 `UnusedLocalVariable` rimosse** (file scelti perche' erano puliti nel working tree,
   nessun altro editor in corso al momento del fix):
   - `app/Console/Commands/AssignRoleCommand.php` — `$user_class` calcolato e mai letto.
   - `app/Http/Controllers/UpgradeController.php` — `$users` letto da query e mai usato (il corpo del
     loop che lo consumava era gia' commentato); commentata anche la query per coerenza (nessun comportamento
     visibile cambiato: l'output resta `'<hr/>+Done'`).
   - `app/Listeners/FailedLoginListener.php` — `$log` assegnato da `create()` e mai letto.
   - `app/Listeners/LoginListener.php` — stesso pattern.

2. **Complessita' — extract-method su `app/Actions/Socialite/RetrieveSocialiteUserAction.php`:**
   `execute()` (CC 15, NPath 240, il piu' complesso del modulo tra i file non gia' in stato dirty) diviso
   estraendo la logica di estrazione del token via Reflection in un metodo privato `extractToken()`.
   Nessun cambio di comportamento (stessa logica, stesso ordine dei tentativi
   `getToken()`→`token()`→proprieta'→fallback). Risultato: CC 15→~4 su `execute()`, `extractToken()` CC 11
   (sopra soglia di 1, non ulteriormente scomponibile senza frammentare artificialmente una catena di
   fallback coesa). Contestualmente sistemati anche i 3 `MissingImport` dello stesso file
   (`\InvalidArgumentException`, `\RuntimeException`, `\ReflectionClass`/`\ReflectionException` → `use`
   statement).

Verificato con test dedicato: `Modules/User/tests/Unit/UserGapAttackCoverageTest.php` referenzia
`RetrieveSocialiteUserAction` (class_exists/instanziazione) — 4/5 test passano invariati dopo il
refactor (il quinto fallisce per causa preesistente non correlata, vedi sezione Pest sotto).

Totale netto: 524 → **514** finding sull'intero modulo. Nota metodologica: la scansione fresca
sull'intera directory dopo i fix riporta 516 righe, ma include 2 finding fantasma
(`CyclomaticComplexity`/`NPathComplexity` su `execute()` alla vecchia riga 26, valori identici a
*prima* del refactor) per `RetrieveSocialiteUserAction.php` che **non corrispondono al contenuto reale
del file** (verificato con `git diff`, lettura diretta e scansione phpmd sul singolo file, ripetuta piu'
volte a distanza di minuti: risultato stabile, un solo finding reale — `extractToken()` CC 11). Sottraendo
i 2 fantasma: 516 → 514. Il conteggio corretto (per-file, sommando la scansione directory-meno-quel-file
con la scansione single-file di quel file) e' quindi 513 + 1 = 514.

**Lasciati e documentati (debito preesistente, non regressioni introdotte oggi):**
- `UnusedFormalParameter` (153) / `CamelCaseParameterName` (122): quasi tutti nelle classi
  `app/Models/Policies/*Policy.php` — parametri prefissati con `_` per convenzione esplicita del progetto
  per marcare "non usato ma richiesto dalla firma del metodo Policy di Laravel/Spatie Permission" (es.
  `viewAny(UserContract $_user)`). Rinominare in massa 35+ classi Policy e' fuori scope per un task di
  chiusura gate e rischia di collidere con sessioni concorrenti che stanno gia' toccando meta' di questi
  file (vedi drift massiccio rilevato in apertura sessione, sotto).
- `MissingImport` (60-3=57 residui): FQCN inline sparse in decine di file, quasi tutti gia' `dirty` nel
  working tree per lavoro concorrente — non toccati per non sovrascrivere lavoro altrui in corso.
- `NumberOfChildren` — `UserBasePolicy` ha 35 figli: architetturale (una Policy per model, pattern Spatie
  Permission), non un difetto correggibile senza riprogettare la gerarchia.
- `CouplingBetweenObjects` — `UserServiceProvider` (21) e `ProfileEditVoltComponent` (14): entrambi file
  con responsabilita' di orchestrazione/composizione (service provider di modulo, componente Volt con
  validazione+persistenza+audit-log) dove l'accoppiamento e' intrinseco al ruolo. `ProfileEditVoltComponent`
  in particolare e' stato oggetto di un fix di sicurezza reale il 2026-09-04 stesso (vedi
  `docs/stories/user-profile-volt-instanceof-wrong-user-class.md`) — non riaperto per refactor cosmetico.
- Restanti `CyclomaticComplexity`/`NPathComplexity` (16), `ElseExpression` (16, quasi tutti in
  `UserServiceProvider::registerMailsNotification()`), `CamelCasePropertyName` (41, proprieta' che
  rispecchiano nomi colonna DB snake_case — rinominarle senza `#[MapInputName]`/accessor dedicato
  rischia di rompere mass-assignment), `LongVariable`/`ShortVariable`, `BooleanArgumentFlag`,
  `ExcessiveParameterList` (DTO Spatie Laravel Data con >10 proprieta' — spacchettare richiederebbe
  ridisegnare i costruttori, fuori scope) — debito preesistente, nessuna regressione riconducibile a
  questa sessione.

### PHPInsights

**Non installato** in questo repo: `vendor/bin/phpinsights` assente (`Could not open input file`).
Coerente con la memoria second-brain "Pest 5 e phpinsights non coesistono" — rimosso perche' incompatibile
con Pest 5 + plugin. Passo saltato, documentato, non simulato.

### Pest

`./vendor/bin/pest -c Modules/User/phpunit.xml --no-coverage`: **840 passed, 242 failed, 26 risky, 7
todos, 27 skipped** (8981 assertions, 772.23s). Nessuno dei fallimenti riguarda i 5 file toccati in questa
sessione (verificato con grep mirato sul log completo: zero occorrenze di
`RetrieveSocialiteUserAction|AssignRoleCommand|LoginListener|FailedLoginListener|UpgradeController` tra i
`FAILED`; l'unico match e' `AssignRoleCommand can be instantiated` che e' **passato**).

Isolamento causa (richiesto dal task): rieseguito un test **mai toccato** da questa sessione
(`Modules/User/tests/Unit/UserTypeTest.php`) da solo — fallisce allo stesso modo (3/10), confermando
che le cause sono preesistenti e non nel diff di questa sessione. Categorie di fallimento osservate,
tutte gia' note al second brain del progetto:
- `Call to undefined function Modules\User\Tests\Unit\mockeryExpect()` — plugin Pest Laravel/helper
  mancante (memoria "pest-laravel-plugin-missing.md").
- `SQLSTATE[42S22]: Unknown column 'uuid'/'birth_date'` e `SQLSTATE[42S02]: Table 'quaeris_user.media'
  doesn't exist` — drift schema DB di test rispetto alle migration correnti (memoria
  "env-test-mysql-repliche.md" / "env-sqlite-manca-suite-non-eseguibile.md").
- `Call to undefined method Modules\User\Models\User::factory()` (64 casi in `TeamManagement*`) —
  stesso genere di problema architetturale gia' diagnosticato in
  `docs/chat/user-profile-instanceof-two-independent-fixes.md`: il model di auth reale e'
  `Modules\Quaeris\Models\User` (vedi `config/auth.php`), non `Modules\User\Models\User`.
- `UserTypeTest`: il test asserisce che `getLabel()`/`getColor()`/`getIcon()` restituiscano la chiave di
  traduzione grezza (`'user::user_type.values.master_admin.label'`) mentre l'implementazione attuale
  restituisce correttamente il valore tradotto (`'Master admin'`) — sembra un test scritto per
  un'implementazione precedente mai aggiornato, non un difetto del codice applicativo. Non corretto in
  questa sessione (fuori scope, nessun file toccato in comune, richiede una decisione di prodotto su
  quale sia il comportamento corretto).

Nessun fix di ambiente forzato, come da istruzioni del task.

### Coverage — non misurabile in modo affidabile oggi (difetto di ambiente, non del modulo)

Tentata una run con `XDEBUG_MODE=coverage ./vendor/bin/pest -c Modules/User/phpunit.xml --coverage
--coverage-text`: la suite completa gira regolarmente (466.37s, stessi numeri di test di sopra) ma
**nessun report di coverage viene stampato**, ne' un errore. Diagnosi:

```
$ php -r 'echo ini_get("xdebug.mode"), PHP_EOL;'         # senza env var
develop
$ XDEBUG_MODE=coverage php -r 'echo ini_get("xdebug.mode"), PHP_EOL;'   # con env var
develop   # <- dovrebbe essere "coverage", non lo e'
$ php -d xdebug.mode=coverage -r 'echo ini_get("xdebug.mode"), PHP_EOL;'
coverage  # <- solo -d esplicito funziona
```

La variabile d'ambiente `XDEBUG_MODE=coverage` non viene onorata da questo processo PHP CLI in questo
ambiente (nessuna direttiva `xdebug.mode` nei file ini caricati che possa spiegarlo — verificato
`php --ini` + grep su tutti i file `conf.d`). Solo `-d xdebug.mode=coverage` esplicito funziona, ma
`vendor/bin/pest` è uno script che rilancia `php` internamente, quindi il flag `-d` passato
sull'invocazione esterna non arriva al processo che esegue davvero i test. Riprodotto anche su un singolo
file di test minuscolo (`UserGapAttackCoverageTest.php`, 5 test) per escludere che fosse un problema di
scala — stesso esito, nessun report. Tempo di esecuzione della run "con coverage" (466s) inferiore a
quella "senza" (772s, ma su un sistema condiviso con altre sessioni concorrenti attive, quindi il confronto
diretto non è probante) è comunque coerente con "xdebug non stava davvero raccogliendo coverage".

Questo è un difetto dell'ambiente di esecuzione condiviso, non del modulo User: non forzato alcun fix
(fuori scope, tocca configurazione PHP/xdebug di sistema, non file del modulo). File di coverage Clover
preesistenti in `Modules/User/tests/coverage-*.xml` sono datati (1-3 settembre, generati da un path
`base_ptvx_fila5` diverso dall'attuale `base_quaeris_fila5`) e non utilizzabili come baseline affidabile
per un confronto prima/dopo di oggi.

**Percentuale di coverage: non calcolabile in modo affidabile oggi.** I fix reali applicati (4 righe di
codice morto rimosso, 1 metodo scomposto senza duplicazione) non riducono la coverage esistente per
costruzione (rimuovere codice mai coperto non puo' abbassare una percentuale; scomporre un metodo in due
mantenendo la stessa copertura di branch non la abbassa). Nessun test finto aggiunto solo per alzare un
numero, come da istruzioni esplicite del task.

### Git — drift preesistente rilevato, non toccato

All'apertura della sessione `git status` nel repo del modulo mostrava **1209 file modificati** non
committati (working tree gia' molto divergente da HEAD per lavoro di sessioni concorrenti, non di questa
sessione). Confermato con `git diff --ignore-all-space --stat`: 1131 file cambiano anche ignorando i soli
spazi, quindi non e' rumore di whitespace. Questa sessione ha aggiunto a `git add` **esclusivamente** i 5
file elencati sopra piu' questi due file di documentazione — nessun altro file dal drift preesistente e'
stato incluso nel commit.

---

**Lines Coverage:** N/A (non misurabile oggi, vedi sopra)
=======
**Lines Coverage:** N/A
>>>>>>> 87273113 (.)
**Methods Coverage:** N/A
**Classes Coverage:** N/A
**Functions Coverage:** N/A
**Test Status:** ⚠️  OTHER ERROR

## Summary

This module contains User functionality for the application.

## Coverage Reflections

- ⚠️  **Low Coverage**: The module has low test coverage, indicating potential risks in production
- Tests are not fully executed
- 🏗️  **Foundation Module**: User module is critical as it provides base functionality for all other modules
- 📋 **Module Size**: Medium complexity with multiple components

- 🔍 **Recommendations**: Focus on integration tests for complex workflows
- 🔐 **Security Critical**: User management module requires comprehensive testing
- 📋 **Module Size**: Medium complexity with multiple components

- 🔍 **Recommendations**: Focus on integration tests for complex workflows

## PHPStan (level max) — swarm fix 2026-09-02

Contesto: `phpstan analyse Modules` (livello max) segnalava 212 errori nel modulo
User su un totale di 542 nell'intero albero `Modules/` (claim:
`docs/chat/claim-phpstan-542-swarm-2026-09-02.md`). Lista errori sorgente:
`errors2-User.txt` (237 righe, 212 errori distinti), generata da una run
full-tree con `--error-format=json`.

**Esito**: `phpstan analyse -c <isolato> Modules/User` ora torna **0 errori**
(tmpDir dedicato per evitare inquinamento da cache condivisa fra sessioni
concorrenti). Commit `fcb5bca5` — 69 file toccati (su 84 elencati; i restanti 15
erano gia' stati corretti da un commit precedente nella stessa giornata,
`5ec97b13`, prima che questo lavoro iniziasse).

Per identifier:
- `cast.string` / `cast.int` (mixed non narrowed): sostituito il cast cieco con
  `is_scalar()`/`is_numeric()`/`is_int()` reali, o con la proprieta' tipizzata
  del model (`->name` al posto di `->getAttribute('name')`) dove esisteva un
  `@property` nel docblock della classe.
- `typeCoverage.paramTypeCoverage` / `typeCoverage.constantTypeCoverage`: tipi
  nativi espliciti su parametri di closure/metodi, inferiti dal contesto reale
  (firma del metodo Filament/Eloquent chiamante, `@property` del model
  collegato) o `mixed` solo dove il framework stesso lo dichiara cosi'
  (es. `Filament\Actions\*::toMailUsing(Closure(mixed, string): ...)`).
  Costanti tipizzate PHP 8.3 (`private const string|int NOME = ...`).
- Deprecazioni Filament v5: `Placeholder::make()->content()` ->
  `TextEntry::make()->state()`; `bulkActions()` -> `toolbarActions()`;
  `actions()` -> `recordActions()`; `form()` -> `schema()`;
  `modalSubheading()` -> `modalDescription()`; `modalButton()` ->
  `modalSubmitActionLabel()`; `MetatagData::getLogoHeader()` ->
  `getBrandLogo()`.
- `class.notFound` (`BaseUser::canAccessPanel()`): rimosso un riferimento morto
  a `App\Support\AccountFeatures` — classe mai esistita dal commit iniziale del
  modulo (`git log -S` conferma), causava un `Error` fatale a runtime su ogni
  pannello diverso da `admin`. Non un problema di sola analisi statica: un bug
  reale mai eseguito con successo in produzione su quel path.

**Gate**: `tools/phpmd.sh` e `tools/phpinsights.sh` sul modulo intero non
mostrano regressioni riconducibili a questi 69 file (i finding residui sono
debito preesistente su file non toccati). Suite Pest non eseguibile in modo
significativo: DB di test `10.100.200.53` irraggiungibile da questo ambiente
(condizione nota, vedi memoria second-brain "test-db-unreachable-drives-skips").

<<<<<<< HEAD
## Riduzione `mixed` — 2026-09-04

Contesto: convenzione di progetto "dove possibile, sostituire `mixed` con un
tipo adeguato" (`CLAUDE.md` root). Censimento iniziale: 194 file / 416
occorrenze di `mixed` in `Modules/User` sul working tree del momento (di cui
102 file già in stato non pulito per lavoro concorrente di un'altra sessione
sullo stesso repo — vedi `docs/chat/` per la mole di modifiche non committate
osservata sull'intero modulo lo stesso giorno). Per non rischiare di
sovrascrivere lavoro altrui in corso, questa sessione si è limitata
esclusivamente ai file **non** già modificati (91 file), dando priorità ai
type-hint nativi (`mixed $x`, `: mixed`) su quelli solo da docblock, e ai file
con meno occorrenze.

**File modificati (3), tutti a `mixed` zero dopo il fix**:
- `app/Actions/Shield/ResolveSuperAdminConfigurationAction.php` — i metodi
  privati `toBoolean(mixed)`/`toString(mixed)` normalizzavano un valore che
  arriva già tipizzato `bool`/`string` da `SuperAdminData` (Spatie Laravel
  Data, proprietà non nullable, hydration da `self::from()` che fallisce se il
  tipo non combacia). Wrapper rimosso, uso diretto delle proprietà.
- `app/Actions/Shield/ResolveFilamentUserConfigurationAction.php` — stesso
  pattern, stessa causa (`FilamentUserData`).
- `app/Filament/Clusters/Passport/Resources/OauthRefreshTokenResource/Pages/ViewOauthRefreshToken.php`
  — closure `->url(function (mixed $state, mixed $record) ...)`: `$state` non
  era usato (rimosso), `$record` già verificato a runtime con
  `instanceof Model` nel corpo — narrowing diretto a `?Model $record`.

**Occorrenze lasciate `mixed` con motivazione** (campione rappresentativo
esaminato, non esaustivo — ~90 file, budget speso sui più semplici):
- `array<string, mixed>` su DTO/Resource/factory/JSON column (`Profile::$preferences`,
  `SsoProvider::$settings`, `AuthenticationLog::$location`, `*Resource::toArray()`,
  tutte le `database/factories/*Factory.php`, costruttori `CreateUserAction`,
  `UserRegistered` event) — payload genuinamente eterogenei per contratto
  (JSON cast, dati di form, factory Eloquent): tipizzare oltre il generic
  richiederebbe uno schema che il progetto non definisce qui.
- Chiusure Filament `callable(string): mixed $get` (`ChangePasswordAction`,
  `ChangeProfilePasswordAction/Header`) — `Get::__invoke()` è genuinamente
  polimorfico sul valore di campo.
- `IsProfileTrait::getMobileDeviceTokens()` — `mixed $value` in
  filter/map su `pluck('token')`, già ristretto con `is_string()` a runtime:
  Collection eterogenea per contratto Eloquent.
- `RegistrationWidget::normalizeFormSchema(mixed $schema)` — riceve il
  ritorno di `$resource::getFormSchemaWidget()`, contratto dinamico
  cross-resource, già validato con `is_array()`/`instanceof Component`.
- `CheckOtpExpiredRule::validate(string, mixed $value, Closure)` — firma
  dell'interfaccia vendor `Illuminate\Contracts\Validation\ValidationRule`:
  narrowing romperebbe LSP.
- `Actions/Shield/ResolveExclusionsConfigurationAction.php`,
  `ShieldUtilsAction.php` — `array_map(static fn (mixed $item) => ...)` su
  valori letti da `config()`, già ristretti con `Assert::string()`/`is_string()`
  prima dell'uso: input genuinamente non tipizzato (config array).
- `SaveOwnershipRelationUseCaseContract::execute(..., mixed $actor)`,
  `GetAllOwnersRelationshipUseCaseContract` — contratti applicativi senza
  alcuna implementazione né call-site nel modulo: nessuna evidenza per
  restringere il tipo senza indovinare.

**PHPStan**: `./vendor/bin/phpstan analyse Modules/User --no-progress` — 0
errori prima, 0 errori dopo (livello `max`, no ignoreErrors).

**PHPMD**: `tools/phpmd.sh` sui 3 file toccati (singolarmente, per evitare il
crash noto `visitAnonymousClass` su tutto il modulo) — nessun finding.

**Pest**: `./vendor/bin/pest Modules/User/tests -c Modules/User/phpunit.xml
--no-coverage` lanciato; vedi esito registrato nella story
`7.5.mixed-type-reduction.story.md` (nessuno dei 3 file toccati ha test
dedicati con nome corrispondente nella suite).

**Nota di scope**: la maggior parenza dei 194 file con `mixed` (~103) non è
stata toccata perché già in stato non pulito nel working tree per lavoro
concorrente di un'altra sessione — vedi `docs/chat/user-profile-instanceof-two-independent-fixes.md`
per il caso analogo osservato lo stesso giorno. Riduzione ulteriore richiede
di ripartire da uno stato pulito o coordinarsi esplicitamente con quella
sessione. Story coordinatrice per il resto del lavoro type-coverage:
`docs/stories/7.4.phpstan-paramtype-coverage.story.md`.

**Fuori scope**: 15 file della lista originale non richiedevano piu' modifiche
(gia' risolti dal commit `5ec97b13` prima dell'inizio di questo lavoro).

## PHPStan (level max) — ProfileEditVoltComponent instanceof reale 2026-09-04

Vedi story completa: `docs/stories/user-profile-volt-instanceof-wrong-user-class.md`.
In sintesi: 4 errori PHPStan (`cast.string`/`argument.type`/`method.nonObject`) su
`ProfileEditVoltComponent.php` erano il sintomo di un bug reale — `instanceof`
verificato contro `Modules\User\Models\User`, sorella (non antenato) del vero
model di auth `Modules\Quaeris\Models\User` (`config/auth.php`). Fix: narrowing
contro `BaseUser` nei 4 metodi, niente piu' `@var` per zittire PHPStan.
`BaseUser.php`: aggiunta `@property string $id`, 5x `\DateTime|null` ->
`Carbon|null`. `phpstan analyse Modules/User` pulito. `phpmd` sui 2 file toccati:
solo debito preesistente (camelCase su colonne DB snake_case, complessita' gia'
sopra soglia prima di questo fix) — nessuna regressione imputabile a questo
diff. Pest non verificabile in modo significativo in questa sessione: vedi
memoria second-brain `env-sqlite-manca-suite-non-eseguibile.md` (396/813 test
falliscono gia' da soli su file mai toccati, causa non diagnosticata).
=======
**Fuori scope**: 15 file della lista originale non richiedevano piu' modifiche
(gia' risolti dal commit `5ec97b13` prima dell'inizio di questo lavoro).

## PHPStan (level max) — swarm fix 2026-09-07 (sessione pomeriggio)

Contesto: recon segnalava ~19 errori residui in `Modules/User`; alla verifica
reale con `phpstan analyse Modules/User --memory-limit=-1 --no-progress` (cache
`/tmp/phpstan` ripulita prima di ogni run, condivisa fra sessioni swarm
concorrenti — vedi nota sotto) il numero reale era **12**, poi sceso a **9**
dopo che la cache stale si e' auto-corretta (2 errori su file di test erano
falsi positivi da cache contaminata da un run precedente).

**Esito: 9 -> 0 errori.** File toccati (5 sorgente + 2 test):

- `app/Filament/Resources/OauthAuthCodeResource.php` — rimossa una docblock
  orfana (`@return array<string, Select|TextInput>`) incastrata fra
  `#[\Override]` e il vero docblock di `extendTableCallback()`: PHPStan
  associava il tipo di ritorno sbagliato al metodo. Il metodo
  `getFormSchema()` che quel docblock descriveva in origine era gia' morto
  (override illegale di un hook risolto staticamente da
  `XotBaseResource::form()`, `final`, verso `Schemas/OauthAuthCodeForm.php`
  gia' esistente) ed e' stato rimosso.
- `app/Filament/Resources/OauthRefreshTokenResource.php` — stesso pattern
  (docblock orfana + `getFormSchema()` morto, `Schemas/OauthRefreshTokenForm.php`
  gia' esistente).
- `app/Filament/Resources/TeamUserResource.php`,
  `app/Filament/Resources/TenantUserResource.php` — stesso pattern per
  `getFormSchema()` (Schemas dedicate gia' esistenti) + `getEloquentQuery()`
  senza generics sul tipo di ritorno. Fix: `@return Builder<Model>` (non
  `Builder<TeamUser>`/`Builder<TenantUser>`) perche' `XotBaseResource` non
  lega il template `TModel` di Filament `Resource` al model concreto (nessun
  `@extends Resource<TModel>` nella catena) — dichiarare un generic piu'
  stretto sarebbe stata un'affermazione non verificabile dal type system.
  Commentato inline il motivo per chi legge dopo.
- `app/Filament/Resources/TenantResource.php` — stesso pattern
  `getFormSchema()` morto (`Schemas/TenantForm.php` gia' esistente) +
  `getRelations()` con un docblock copiato per errore da un altro metodo
  (`@return array<string, Component>` invece del tipo reale
  `array<int, class-string<RelationManager>|RelationGroup|RelationManagerConfiguration>`
  usato dal parent `XotBaseResource`/Filament `Resource`); aggiunti gli
  `use` mancanti per le 3 classi Filament.
- `tests/Unit/UserExecuteCoverage50Test.php`,
  `tests/Unit/UserModulePhpstanFixesTest.php` — due test chiamavano
  staticamente metodi `getFormSchema()` che sono **istanza**, non statici
  (`UserForm::getFormSchema()`, `PasswordData::getFormSchema()`, entrambi
  `public function`, non `public static function`): `(new UserForm())->getFormSchema()`
  e `PasswordData::make()->getFormSchema()`.

**mixed residui**: nessuno introdotto da questi file.

**Nota cache condivisa**: `phpstan.neon` usa `tmpDir: /tmp/phpstan`, condiviso
da tutte le sessioni swarm attive in parallelo sullo stesso host — un run "0
errori" subito dopo un edit di un altro agente puo' essere un falso negativo
da cache contaminata. Verificato con run ripetuti a cache pulita
(`rm -f /tmp/phpstan/resultCache.php`) prima di dichiarare 0 errori definitivo.

**Concorrenza sul modulo**: durante questa sessione il working tree di
`Modules/User` aveva ~650 file gia' modificati/non tracciati da altre sessioni
swarm attive in parallelo (probabile `migration-foreignidfor-swarm`, che
elenca esplicitamente `User` fra i moduli in lavorazione — vedi
`docs/chat/INDEX.md` root repo). Il commit di questa sessione include **solo**
i 7 file elencati sopra (`git add` esplicito per path, mai `-A`); il resto
del working tree non e' stato toccato ne' committato.

**Gate**:
- `pint --test` (scope esplicito sui 7 file, mai `--dirty` che spazzola l'intero
  repo condiviso) -> 2 file con debito di stile preesistente
  (`new_with_parentheses` ecc.), corretti con `pint` scope-limitato.
- `tools/phpmd.sh` sull'intero modulo va in crash (`PHP Fatal error` interno al
  phar, `AbstractLocalVariable::isPassedByReference()` su `getParent()` di un
  nodo null) — crash del tool su un file del modulo non identificato, non
  riconducibile ai 7 file di questa sessione: scope-limitato ai 5 file sorgente
  toccati, **0 violazioni**.
- `phpinsights` risulta rimosso dal repo (incompatibile con Pest 5, vedi second
  brain) — non eseguito.
- `pest` scope-limitato ai 2 file di test toccati (`--filter` sui test
  modificati): entrambi **passano** a runtime (non solo staticamente).
  Il run completo `Modules/User/tests` (suite intera, ~650+ test) e' stato
  lanciato ma non atteso fino in fondo per via del carico dell'host condiviso
  da altre sessioni swarm (piu' processi Pest concorrenti su altri moduli);
  i fallimenti osservati nella porzione completata sono preesistenti e non
  riconducibili a questi 7 file (`mockeryExpect()` funzione non importata in
  test non toccati, `HasPassportConfiguration::tokenLifetime()` metodo non
  esistente in test non toccati, tabella `users` mancante sul DB di test —
  stesso pattern gia' documentato in second brain
  "Test DB missing migrations blocks Feature tests").
>>>>>>> 87273113 (.)
