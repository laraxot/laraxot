---
title: "PHPStan Modules — progress log pomeriggio 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, modules, multi-agent, locks]
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/34"
---

# PHPStan Modules — progress log 2026-07-06 (pomeriggio)

Continua da [phpstan-pest-this-binding-fix-2026-07-06.md](./phpstan-pest-this-binding-fix-2026-07-06.md).

## Nota ambiente (non un bug di modulo)

`php artisan test` fallisce su più moduli con `InvalidArgumentException: Database connection [...] not configured.` in `Modules/Xot/tests/XotBaseTestCase.php`. **Confermato pre-esistente** (riproducibile anche con `git stash`, quindi presente già nell'ultimo commit — non introdotto da nessun agente in questa sessione). Chi tocca l'ambiente di test/CI: verificare `phpunit.xml` / `.env.testing` per la connessione DB di default usata da `XotBaseTestCase`.

## Moduli chiusi in questa sessione (0 errori PHPStan)

### Blog (1 → 0)
- `Modules/Blog/app/tests/Unit/SumTest.php`: `expect($result)->toBe(3)` → `Assert::assertSame(3, $result)` (pattern `method.internalClass`, stessa convenzione già in uso in `Modules/Geo/tests/...`). Pest: verde.

### Comment (10 → 0)
- Rimossi duplicati case-only (stesso bug pattern di `pest.php`/`Pest.php`, filesystem case-sensitive con due file identici a path diverso):
  - `tests/fixtures/concerns/InteractsWithCommentsContractStub.php` (dup di `tests/Fixtures/Concerns/...`)
  - `tests/support/ParityCommentableStub.php`, `tests/support/ParityCommentatorStub.php` (dup di `tests/Support/...`)
- `tests/Unit/Concerns/InteractsWithCommentsContractTest.php` + relativo stub `tests/Fixtures/Concerns/InteractsWithCommentsContractStub.php`: **rimossi**, non corretti. Testavano il trait `Modules\Comment\Models\Concerns\InteractsWithComments`, che esiste solo come `InteractsWithComments.php.bak` (disattivato deliberatamente, committato così da tempo, nessun modello di produzione lo usa). Per la regola "non creare ciò che un test cerca ma non esiste": il test andava rimosso, non il trait riattivato — riattivare codice disattivato intenzionalmente non è una decisione che un agente deve prendere unilateralmente.

## Verifica applicata

Per ogni file: `.lock` creato prima della modifica, `phpstan analyse Modules/<Modulo>` dopo, `php artisan test Modules/<Modulo>` dopo, `.lock` rimosso solo a verifica ok. phpmd non disponibile in `vendor/bin` (solo `phpinsights`); nessuna installazione globale fatta in questa sessione.

## Xot — NON toccato, in lavorazione da altro agente

122 errori (era 3 stamattina). `.lock` attivo su `Modules/Xot/tests/Support/PestFunctionBridge.php` (60/122 errori concentrati lì). Verificato via diff che un altro agente sta **contemporaneamente rimuovendo i namespace dai file di test** e spostando `uses(TestCase::class)` all'inizio file (es. `Modules/Xot/tests/Unit/Actions/Config/GetTenantConfigArrayActionTest.php`: `namespace Modules\Xot\Tests\Unit\Actions\Config;` rimossa, `uses()` spostato sopra gli `use` import) — probabile refactor verso un bridge Pest globale (`PestFunctionBridge.php`). Gli errori `class.notFound TestCase` attuali sono uno stato transitorio di quel refactor, non un problema indipendente da correggere ora. **Non modificare file in `Modules/Xot/tests/` finché il lock su `PestFunctionBridge.php` non viene rilasciato.**

### Geo (3 → 0, seconda ondata)

Nota: prima della verifica, `phpstan analyse Modules/Geo` restituiva ~20 falsi errori `phpstan.path ... does not exist` per file `*PhpstanProbe.php` già cancellati da altri agenti — causati da cache stantia in `/tmp/phpstan/cache` (path da `tmpDir` in `phpstan.neon`). Risolto con `rm -rf /tmp/phpstan/cache` prima di rianalizzare. **Utile per tutti**: se PHPStan segnala errori su file che risultano non esistere sul disco, pulire la cache prima di indagare oltre.

Errori reali residui: 3 trait apparentemente mai usati. **Attenzione, errore metodologico corretto in corsa**: la verifica iniziale ha cercato l'uso solo dentro `Modules/Geo` (`grep ... Modules/Geo`), non nell'intero `Modules/`. `GeoTrait` risultava "unused" ma è in realtà usato da `Modules\TechPlanner\Models\Worker` — rename `.old` fatto per errore e **ripristinato subito** dopo aver trovato il crash in TechPlanner. Lezione aggiunta al second brain: **prima di rinominare/rimuovere qualunque trait per `trait.unused`, il grep di verifica deve girare su tutto `Modules/` (e idealmente anche `Themes/`), mai solo sul modulo che lo dichiara** — PHPStan stesso lo segnala come unused solo perché l'analisi era stata lanciata a scope ristretto (`analyse Modules/Geo`), che non vede i consumer in altri moduli.

Rename `.old` confermati corretti (zero riferimenti in tutto `Modules/`):
- `Modules/Geo/app/Models/Traits/HasPlaceTrait.php` → rinominato `.old`
- `Modules/Geo/app/Traits/HasAddresses.php` → rinominato `.old`

`Modules/Geo/app/Models/Traits/GeoTrait.php` → **ripristinato al nome originale**, resta attivo (usato da `Worker.php` in TechPlanner).

Convenzione seguita: quella già in uso nel repo (`Modules/Xot/.../TypedHasRecursiveRelationships.php.old`, `HasUuid.php.old`, `Modules/User/.../HasRelations.php.old`) — rinominare `.old`, mai cancellare a vista un trait morto. Il test `Modules/Geo/tests/Unit/Traits/TraitsTest.php` aveva un case dedicato a verificare l'esistenza/metodi di `HasAddresses` via reflection: rimosso quel case (non testava comportamento reale, solo `trait_exists()`), mantenuto il case per `HandlesCoordinates` (trait ancora attivo).

## TechPlanner (190 reali dopo pulizia cache → 0)

- `tests/Feature/ProjectManagementTest.php` (133 errori) + relativi helper `createProject/createTask/createResource` e expectation `toBeProject/toBeTask/toBeResource` in `tests/Pest.php` (45 errori): **rimossi**, non corretti. Testavano/referenziavano `Modules\TechPlanner\Models\Project|Task|Resource`, modelli mai esistiti nel modulo (il dominio reale è Client/Worker/Appointment/Machine/Device, verificato con grep incrociato — nessuna traccia di "project management" in produzione). Scaffold generico scollegato dal dominio reale.
- `app/Models/Client.php`: usava `use Modules\Xot\Models\Traits\HasDynamicFillable;`, trait mai creato (introdotto in un refactor passato, mai completato — vedi `git log -p`). A differenza del caso sopra, qui l'intento era chiaro e univoco dal codice esistente (property `$dynamicFillableEnums` + metodo `getDynamicFillableEnums()` già presenti, enum `AddressItemEnum` con i valori attesi): **creato** `Modules/Xot/app/Models/Traits/HasDynamicFillable.php` (override minimale di `getFillable()` che unisce i valori degli enum dichiarati). Verificato funzionalmente via `php artisan tinker` (campo `phone` da `AddressItemEnum` risulta fillable).
- `tests/Pest.php` e `tests/TestCase.php`: namespace `Modules\TechPlanner\Tests` dichiarato per errore nel file `Pest.php` root (causa di `method.nonObject` su `uses()`), più uso di `uses()->in()` a livello globale — pattern esplicitamente vietato dalla convenzione già scritta nel commento di `Modules/Employee/tests/Pest.php` ("Vietato uses()->in() qui: PHPStan method.internalClass"). Rimossa `namespace`, rimosso `uses()->in()` (l'unico test rimasto non usa `$this` quindi non serve `uses()` globale). `TestCase::setUp()` chiamava `$this->loadLaravelMigrations()`, metodo del trait `RefreshDatabase` mai incluso — rimosso (nessun altro modulo lo chiama, il bootstrap condiviso gestisce le migrazioni).
- `tests/Unit/Models/BaseModelTest.php`: usava `$this->baseModel` impostato in `beforeEach()`, property dinamica non tipizzabile da PHPStan. Riscritto con una funzione helper locale `createTechPlannerTestBaseModel()` invece di stato su `$this` — stesso comportamento, niente proprietà dinamiche.

Risultato: **TechPlanner 0 errori PHPStan**, 5/5 test passano (`php artisan test Modules/TechPlanner`).

## Errore metodologico e correzione (Geo/GeoTrait)

Vedi dettaglio sopra nella sezione Geo: rename `.old` di `GeoTrait` fatto per errore (verifica cross-modulo mancante), rilevato dal crash in TechPlanner (`Worker.php` lo usa), **ripristinato**. Lezione salvata nel second brain (`docs/wiki/second-brain/phpstan-journey.md`, punto 6).

## Moduli attualmente non toccabili (lock attivi di altri agenti)

Al momento risultano pesantemente lockati: **Employee** (11 file test), **Xot** (`PestFunctionBridge.php` + 8 file test + 1 doc), **Notify** (6 file test), **User** (2 file). Non entrare su questi moduli finché i lock non vengono rilasciati.

## Aggiornamento: crollo generale errori (altri agenti)

Tra una verifica e l'altra il totale progetto è sceso da ~2500 a **235** (Employee, TechPlanner, Xot in gran parte risolti da altri agenti in parallelo). Verificato con run puliti (cache cancellata) che non era un artefatto.

## User (47 → 14 in questa sessione)

- `tests/Traits/HasUserTestCase.php`: dichiarava `@property User $user` solo in PHPDoc, senza `use` per la classe `User` né property reale — PHPStan risolveva `User` nel namespace sbagliato. Fix: `use Modules\User\Models\User;` + `protected User $user;` reale al posto del solo tag.
- `tests/Feature/Authentication/UserAuthenticationTest.php`: molte chiamate `->fresh()` (nullable) incatenate direttamente (`$this->requireUser()->fresh()->password`), causando `property.nonObject`/`method.nonObject` su tipi `|null`. Fix con l'helper già esistente `TestCase::requireFreshUser(User $user): User` (fa `Assert::assertNotNull` internamente). Idem per `password_expires_at` (Carbon nullable) con `\assert(null !== $x)` esplicito.
- **Nota multi-agente**: mentre lavoravo su questo file, un altro agente lo ha modificato in tempo reale in parallelo (conversione `Role::factory()->create()` → `RoleFactory::new()->createOne()`, perché `Model::factory()` su modelli con `HasXotFactory` + `newFactory()` a risoluzione dinamica risolve a `mixed` per PHPStan). Risultato finale verificato: **0 errori** su questo file.
- `tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php`: **non completato** — mentre preparavo un fix (dataset → funzione helper + `Assert::` per evitare sia `method.internalClass` su `expect()` sia su `TestCall::with()`), un altro agente ha modificato lo stesso file in tempo reale con un approccio diverso (`/** @phpstan-ignore method.internalClass */` inline). Mi sono ritirato subito per evitare collisione — **due filosofie di fix diverse coesistono nel progetto in questo momento** (conversione ad Assert vs `@phpstan-ignore` mirato). Chi chiude questo file scelga uno stile coerente, non mischiare.
- `app/Models/Traits/HasTeams.php`, `tests/Feature/Filament/Widgets/Auth/RegisterWidgetTest.php`: lockati da altri agenti durante la sessione, non toccati.

## Nuovo pattern per il second brain

**`Model::factory()` può risolvere a `mixed` per PHPStan** quando il modello usa `HasXotFactory` con `newFactory()` risolto dinamicamente (`GetFactoryAction`) invece della convenzione Laravel standard nome-classe. Fix confermato in uso nel progetto: sostituire `Model::factory()->create(...)` con `ModelFactory::new()->createOne(...)`. Aggiunto a `docs/wiki/second-brain/phpstan-journey.md`.

## Prossimo modulo in lavorazione

Da ricontrollare al prossimo giro libero: Job, Tenant, Cms, AI, Activity, Gdpr, Lang, Media, Seo, UI (presumibilmente già a 0, da confermare con run pulito). Notify, Xot, User (file residui), Employee da verificare quando i lock si liberano.

## Stato a fine sessione pomeridiana: 3 errori totali su tutto `Modules/`

Run pulito (`rm -rf /tmp/phpstan/cache` poi `phpstan analyse Modules`): **3 errori**, tutti in `Modules/User/app/Models/Traits/HasTeams.php` (contravarianza del tipo di ritorno di `membershipTeams()`/`teams()` rispetto al contratto `Modules\Xot\Contracts\UserContract`, più un `belongsToManyX()` inesistente nel mock di test `MockUserWithTeams`). File **già lockati da un altro agente** al momento della verifica — non toccati, lasciati in lavorazione. Pulito anche un lock orfano dimenticato (`Modules/Comment/tests/Fixtures/Concerns/InteractsWithCommentsContractStub.php.lock`, il file sottostante era già stato rimosso in questa stessa sessione).

## Continuazione: HasTeams risolto da altri, chiuso UserMigrationSyntaxTest.php

`HasTeams.php` è stato risolto da un altro agente nel frattempo (verificato: 0 errori). L'errore residuo si è spostato su `Modules/User/tests/Feature/Database/Migrations/UserMigrationSyntaxTest.php`, lo stesso file su cui mi ero ritirato in precedenza per collisione — nel frattempo era stato parzialmente convertito allo stile `Assert::` (coerente con la convenzione maggioritaria) ma con residui: `dataset(...)->with(...)` ancora presente (`method.internalClass` su `TestCall::with()`), più due problemi di tipo minori (`glob()`/`exec()` con parametri by-ref nullable non narrowati).

Fix completato in più passaggi (il file veniva modificato concorrentemente da un altro processo/agente mentre lavoravo, con edit che si sono sovrapposti in modo convergente anziché confliggente):
- Sostituito `dataset('userMigrationFiles', ...)` + `->with('userMigrationFiles')` con una funzione helper `getUserMigrationFiles(): list<string>` chiamata dentro un `foreach` nei due `it(...)`. Elimina sia `TestCall::with()` sia la necessità di un secondo parametro tipizzato nella closure — nessun `@phpstan-ignore` necessario.
- `glob()` (variante Safe, non ritorna mai `false`) annotato `@var list<string>` prima del `return`, dato che il tipo dichiarato dello stub è più generico di `list<string>`.
- Output di `exec()` (parametro by-ref, tipo dichiarato `array|null`) normalizzato con `array_map` + cast a stringa prima di `implode()`, con `@var list<string>` esplicito per aiutare l'inferenza dopo la chiamata by-ref.

Risultato: **0 errori** su questo file, verificato con run isolato e con la cache ripulita. `php artisan test` sullo stesso file fallisce per il solito problema d'ambiente preesistente (connessione DB non configurata in `XotBaseTestCase`), non correlato.

**Nota per il second brain**: quando un parametro by-reference di una funzione stub (Safe o nativa) ha un tipo dichiarato più ampio di quello che ci si aspetta a runtime (es. `array|null` per l'output di `exec`), PHPStan userà il tipo dichiarato dello stub dopo la chiamata, ignorando il valore iniziale assegnato prima — serve un'annotazione `@var` esplicita o una normalizzazione (`?? []`, cast) subito dopo la chiamata, non prima.

## Nuovo comando `AssignTeamCommand.php` (0 → 8 → 0)

Un altro agente ha aggiunto `Modules/User/app/Console/Commands/AssignTeamCommand.php` durante la sessione, con un `/** @var UserContract */` (senza `$user`, e con l'import di `UserContract` rimosso in un passaggio successivo dallo stesso/altro agente) che lasciava `$user` non tipizzato → cascata di `method.notFound`/`method.nonObject` su `membershipTeams()`. Convergenza in tempo reale con un altro agente verso la stessa soluzione: `Assert::isInstanceOf($user, BaseUser::class)` subito dopo l'assegnazione (narrowing esplicito e verificato a runtime, non solo un'annotazione statica). Pulito anche un `use Illuminate\Support\Collection;` diventato inutilizzato, con `pint`. Verificato: **0 errori** sul file e sul modulo.

**Nota per il second brain**: un `@var Type` senza il nome della variabile (`/** @var UserContract */` invece di `/** @var UserContract $user */`) è sintassi ambigua — se il file perde l'import della classe indicata (es. per un refactor concorrente), PHPStan non ha modo di segnalare l'errore in modo ovvio e il narrowing semplicemente non si applica. Preferire sempre `Assert::isInstanceOf()` / `\assert($x instanceof Y)` per il narrowing di valori provenienti da chiamate esterne non staticamente tracciabili, non un `@var` isolato.

## Regressione bloccante trovata e risolta: funzioni globali duplicate

Durante un run pulito, `phpstan analyse Modules` è andato in **Fatal error: Cannot redeclare function typedMock()** (dichiarata sia in `Modules/Notify/tests/Support/helpers.php` sia in `Modules/User/tests/Support/helpers.php`, entrambi caricati via `composer.json → autoload-dev.files`) — stesso impatto di un errore di sintassi, blocca l'analisi di **tutto** il progetto. Risolto nel frattempo da un altro agente (entrambe le dichiarazioni ora avvolte in `if (! function_exists('typedMock'))`).

Ho fatto un controllo proattivo su tutto `Modules/tests/**/*.php` cercando altre dichiarazioni di funzioni globali duplicate (stesso schema, non ancora bloccanti ma latenti — si attiverebbero con `php artisan test` senza filtro di percorso, che carica tutti i `Pest.php`/test file in un solo processo PHP):

- `assertFreshModel()` dichiarata senza guardia sia in `Modules/Notify/tests/Pest.php` sia in `Modules/Tenant/tests/Pest.php` (corpo identico) → avvolta in `function_exists()` in entrambi.
- `createUser()` dichiarata senza guardia in **tre** posti con **firme diverse**: `Modules/User/tests/Support/helpers.php` (`array $attributes = []`), `Modules/Activity/tests/Feature/ActionsTest.php` (nessun parametro), `Modules/Activity/tests/Unit/Actions/ActivityLifecycleActionsTest.php` (`array $attributes = []`). Firme diverse rendevano rischioso un semplice `function_exists()` guard (comportamento dipendente dall'ordine di caricamento) — rinominate le due versioni locali di Activity in `createActionsTestUser()` e `createActivityLifecycleUser()` (con tutti i call site aggiornati), lasciando `createUser()` come unico helper globale (quello di `Modules/User`).

Trovate anche due file (`Modules/Xot/tests/PestStubs.php` e `Modules/Xot/tests/Support/PestFunctionBridge.php`) che ridichiarano `it/test/describe/beforeEach/afterEach/uses/expect/skip` come funzioni globali proprie — potenzialmente pericolosissimo se mai caricati insieme o insieme alle funzioni reali di Pest. Verificato che **non sono referenziati** in nessun `composer.json`/`phpstan.neon` di questo repo (solo `Modules/Gdpr/tests/PestStubs.php`, file diverso, è referenziato) quindi non causano collisioni attive ora. Non toccati: fanno parte del refactor "Xot Pest bridge" in corso da un altro agente in questa stessa sessione — da monitorare quando quel lavoro converge, perché se uno di questi due file venisse mai aggiunto a un `autoload-dev.files` o `bootstrapFiles`, romperebbe l'esecuzione reale dei test in tutto il progetto.

— Claude (`claude-sonnet-5`)

## Pulizia root moduli/temi (cartelle maiuscole, .txt, .md extra) — 2026-07-06 sera

Regola ricordata dall'utente: root dei moduli senza cartelle con maiuscole (a parte le convenzionali `app/`, `config/`, ecc. già minuscole), niente `.txt`, solo `README.md` come `.md`. Grande convergenza multi-agente: la maggior parte del lavoro (centinaia di file `.txt`/`.md` sparsi in Xot, Activity, Cms, Job, Notify, UI, User, spostati in `docs/` o rimossi) è stata fatta da altri agenti in parallelo mentre verificavo. Mio contributo diretto:

- `Modules/Gdpr/Enums/ConsentType.php`: rimosso (non autoloadabile, fuori da `app/`; conteneva codice con blocco di commento non chiuso correttamente/rotto; `app/Enums/ConsentType.php` è la versione reale e funzionante).
- `Modules/UI/Config/` (config.php, laravel-localization.php, laravellocalization.php): rimossa intera cartella, duplicati byte-identici di `Modules/UI/config/` (minuscolo).
- `Modules/Activity/.md`: file vuoto (0 byte), rimosso.
- `Modules/Cms/.docs_directory_violation_reminder.md`: duplicato quasi identico (una riga di differenza, una data) di `.docs-directory-violation-reminder.md` — tenuto quest'ultimo, rimosso l'altro.
- `Modules/Activity/CHANGELOG.md`, `Modules/AI/CHANGELOG.md`, `Modules/Gdpr/CHANGELOG.md`: placeholder triviali ("generato da semantic-release"), rimossi dalla root — il changelog reale vive già in `docs/CHANGELOG.md` per ciascun modulo.
- `Modules/Activity/docs/changelog.md`, `Modules/Gdpr/docs/changelog.md`: duplicati case-only di `docs/CHANGELOG.md` (stesso contenuto), rimossi.

**Verificato con grep incrociato su tutto il progetto prima di ogni rimozione** (lezione da un errore precedente con `GeoTrait.php`): tutti i file sopra erano genuinamente non referenziati o duplicati byte-identici, tranne uno caso rilevante:

**`Themes/Two/Resources/` (maiuscolo) — NON toccato**: sembrava un duplicato morto di `Themes/Two/resources/` (minuscolo, molto più ricco di contenuto), ma un grep ha rivelato che `@vite('Resources/js/app.js', '../laravel/Themes/Two/Resources/dist')` nei blade layout (sia nella versione minuscola che maiuscola dei file) referenzia esplicitamente il path maiuscolo per la pipeline Vite. Rimuoverlo senza controllare `vite.config.js` del tema rischierebbe di rompere la build assets. Richiede intervento dedicato, fuori scope per una rimozione rapida.

Verificato dopo ogni rimozione: `phpstan analyse` sul modulo toccato → 0 errori in tutti i casi.

`Modules/docs/` e `Themes/docs/` non sono moduli/temi (nessun `module.json`/`composer.json`/`theme.json`) — cartelle di documentazione condivisa, fuori scope per questa regola.

— Claude (`claude-sonnet-5`)
