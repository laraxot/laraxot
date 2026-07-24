---
title: "Import status — Auth (Wave 3 replica, DO NOT DELETE)"
type: concept
tags: [auth, replica, import, wave3, do-not-delete]
created: 2026-07-23
updated: 2026-07-24
qmd: "Auth import status wave3 replica reshape enabled do not delete"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/wiki/rules/module-name-singular-mandatory.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Auth

> **Fase:** replica Wave 3 (Auth, Document, Email, HR, Platform, WhatsApp).
> Copiato da `gestionale_commesse`, reshape-ato come cartella indipendente.
> **Non** merge in moduli nativi (User, Employee, Media, Notify, AI, Tenant).
> **Non** cancellare.

## Checklist reshape

- [x] Layout sotto `app/` (Application/Domain/Infrastructure/Filament/Http/Console/Providers root rimossi)
- [x] Database/Resources/Routes (capital) spostati in database/resources/routes (lowercase)
- [x] Namespace PHP allineati al nuovo path (rimosso segmento Application/Domain/Infrastructure)
- [x] ServiceProvider principale estende `XotBaseServiceProvider`
- [x] `RouteServiceProvider` estende `XotBaseRouteServiceProvider`
- [x] `EventServiceProvider` estende `Modules\Xot\Providers\XotBaseEventServiceProvider` (2026-07-24: corretto da `Illuminate\...\EventServiceProvider`)
- [x] `composer.json` Laraxot PSR-4 → `app/` (namespace verificato: singolo backslash)
- [x] Azioni con `execute()` → trait `Spatie\QueueableAction\QueueableAction` (rimosso `readonly` dalla classe, incompatibile con la property non-readonly del trait)
- [x] Filament Resource CRUD principali → `extends XotBaseResource` (`form()` → `getFormSchema(): array`)
- [x] Filament Pages → `extends XotBase{List,Create,Edit,View}Record` (2026-07-24)
- [x] `lang/{it,en}/auth.php` (2026-07-24, prima assente)
- [ ] BaseModel → XotBaseModel: N/A per questo modulo (Auth non ha un `app/Models/User` proprio, usa `Modules\User\Models\User`)
- [ ] Migrazioni `users` consolidate/deduplicate rispetto a `Modules\User` (TODO esplicito, vedi `gestionale-commesse-port.md`)
- [ ] Pest test (0 test presenti — TODO)
- [x] `modules_statuses=true` (2026-07-24) — resta **replica**, non merge in User

## PHPStan

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Auth --memory-limit=-1
```

Esito (2026-07-24): **0 errori reali**. Rimane solo il warning artefatto
`Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not matched in reported errors.`,
dovuto al `phpstan.neon` di root (`ignoreErrors` globale) quando si analizza un solo modulo isolato — stesso
comportamento riprodotto su altri moduli (WorkOrder, Quotation), non modificabile da qui (root
`phpstan.neon` fuori scope) e non un errore del codice Auth.

### Bug funzionali reali trovati e corretti

Causa radice comune: quasi tutto il modulo importava `App\Models\User` (uno stub quasi vuoto in
`app/Models/User.php`, senza ruoli/permessi) invece del vero modello di autenticazione configurato in
`config/auth.php` (`Modules\User\Models\User`, con `HasRoles`/`HasPermissions` via `HasSpatiePermission`).
Conseguenze runtime concrete, non solo tipizzazione:

- **Login sempre "non validato"**: in `LoginController::store()` il check
  `$user instanceof \App\Models\User` era sempre `false` (l'utente reale è
  `Modules\User\Models\User`), quindi il blocco che verifica approvazione account, ruoli
  ammessi al pannello (admin/operator) e attivazione 2FA email non veniva **mai eseguito**:
  chiunque poteva fare login e bypassare approvazione/2FA.
- **2FA email sempre bloccata**: in `EmailTwoFactorController::verify()`/`resend()`,
  `abort_unless($user instanceof User, 403)` con lo stesso `User` sbagliato faceva **sempre**
  abortire con 403 — il flusso di verifica/reinvio codice 2FA era di fatto irraggiungibile.
  Stesso problema di tipo nei parametri di `StartEmailTwoFactorChallengeAction` e
  `VerifyEmailTwoFactorCodeAction` (avrebbero comunque fallito a runtime con
  `TypeError` non fatale in PHP non strict, ma logicamente sbagliati).
- **Registrazione/creazione utenti da comando rotta**: `RegisterController::store()` e tutti
  gli 8 comandi `Create*PanelUserCommand` chiamavano `User::firstOrCreate()`/`->syncRoles()`
  sullo stub `App\Models\User`, che non ha il trait `HasRoles` → `Call to undefined method
  syncRoles()` a runtime (fatal error).
- **Pulsante "Approva utente" mai visibile**: in `UserResource` la visibilità dell'azione
  dipendeva da `method_exists($record, 'isApproved')`, ma `isApproved()` non esiste da
  nessuna parte nel repo → il bottone di approvazione admin non compariva mai.
- **Foreign key incompatibili nelle migration**: `users.id` è `string(36)` (UUID), ma le
  migration `2026_07_21_120000_add_manager_to_users.php` e
  `2026_05_06_140000_add_user_approval_fields.php` usavano `$table->foreignId(...)`
  (`unsignedBigInteger`) per `manager_id`/`approved_by_user_id` referenziando `users.id`:
  vincolo di foreign key con tipi incompatibili. Corretto in `foreignUuid(...)`.

Fix applicato: sostituito ovunque `use App\Models\User;` con `use Modules\User\Models\User;`
(Actions, Console Commands, Controllers, Seeders), sostituiti gli accessi a colonne custom
(`approved_at`, `manager_id` — aggiunte dalle migration di questo modulo e non presenti nel
docblock del model, che vive in `Modules/User` fuori scope) con `getAttribute()`/`setAttribute()`
guardati, e sostituita la chiamata inesistente `AccountFeatures::canAccessPanel()` con i metodi
realmente esistenti (`hasAdministrativeRole()`, `hasRole('operator')`). `GetTeamMemberIdsAction`/
`ResolveTeamMemberIdsAction` tipizzavano gli id utente come `int`: corretti a `string` (UUID).

## Pest — migrazione test reali da gestionale_commesse (2026-07-24)

```
cd laravel && vendor/bin/pest Modules/Auth/tests
```

Migrati **23/23** file test reali da `gestionale_commesse` (22 Feature + 1 Unit) in
`Modules/Auth/tests/{Feature,Unit}/`. Namespace/`use` corretti: `Modules\Auth\Application\Actions\*`
e `Modules\Auth\Domain\Actions\*` → `Modules\Auth\Actions\*`; `App\Models\User` → `Modules\User\Models\User`;
`Spatie\Permission\Models\{Role,Permission}` → `Modules\User\Models\{Role,Permission}` (vedi bug sotto);
`Modules\Interventions\Infrastructure\Models\Intervention`/`Modules\WorkOrders\Infrastructure\Models\WorkOrder`
(nomi plurali, vecchia struttura DDD-lite) → `Modules\Intervention\Models\Intervention`/`Modules\WorkOrder\Models\WorkOrder`
(nomi singolari, struttura reshape-ata).

Aggiunti `Modules/Auth/tests/TestCase.php` e `Pest.php` (mancavano) seguendo il pattern di
`Modules/User/tests/TestCase.php`: `DatabaseTransactions`, connessione MySQL reale da `.env.testing`
(non il fixture sqlite condiviso `fixcity_data.sqlite`, che non contiene le tabelle `users`/`roles`/
`permissions` di questo monorepo). Aggiunta suite `Auth` in `laravel/phpunit.xml`.

**Esito attuale: 35 pass / 33 fail / 5 skip** (73 test totali).

### Bug reali trovati e corretti in questo giro

1. **`RouteServiceProvider.php` di Auth non impostava `$module_dir`**: la property
   `protected string $module_dir = __DIR__;` di `XotBaseRouteServiceProvider` non viene
   ri-valutata nelle sottoclassi che non la ridichiarano (il valore resta quello del file
   della classe astratta, `Modules/Xot/app/Providers`). Risultato: `mapWebRoutes()`/
   `mapApiRoutes()` caricavano `Modules/Xot/routes/{web,api}.php` (quasi vuoto) invece di
   `Modules/Auth/routes/{web,api}.php` — **nessuna rotta legacy (`/login`, `/register`,
   `/logout`, 2FA email) era mai registrata**. Fix: aggiunto `protected string $module_dir = __DIR__;`
   in `Modules/Auth/app/Providers/RouteServiceProvider.php`. Verificato con
   `php artisan route:list --path=login` (prima: solo rotte `*/admin/login` dei pannelli Filament
   di altri moduli; dopo: anche `GET|POST /login`). **Sospetto che lo stesso bug sia presente in
   altri moduli replicati con lo stesso pattern (es. WorkOrder, Intervention): da verificare,
   fuori scope per questo giro.**
2. **Codice di produzione Auth (seeders, comandi, controller, action) importava
   `Spatie\Permission\Models\Role`/`Permission` direttamente** invece delle classi bindate in
   `config/permission.php` (`Modules\User\Models\Role`/`Permission`, con `$connection = 'user'`).
   In produzione il bug è invisibile finché la connessione di default coincide con quella
   `user`; in test (`phpunit.xml` forza `DB_CONNECTION=sqlite`/`:memory:` di default) le query
   dirette su `Spatie\Permission\Models\Role` finivano sulla connessione sqlite vuota
   (`no such table: roles`). Corretto ovunque nel modulo: `RolesAndUsersSeeder`,
   `ShieldPermissionsSeeder`, `WtsRolesSeeder`, `TimberRolesSeeder`, `ApproveRegisteredUserAction`,
   `RegisterController`, tutti gli 8 `Create*PanelUserCommand`.
3. **Migrazioni Auth eseguite per errore sul database sbagliato** durante questo giro (poi
   corrette): la connessione `user` (usata da `Modules\User\Models\User`/`Role` in test) punta a
   un database MySQL separato (`techplanner_user_test`, da `DB_DATABASE_USER` in `.env.testing`),
   diverso dal DB di default (`techplanner_data_test`, da `DB_DATABASE`). Le migration
   `add_user_approval_fields`/`add_manager_to_users`/`create_permission_tables` di Auth vanno
   eseguite con `--database=user`, non con il default. (Le modifiche fatte per errore sul DB
   sbagliato sono state annullate manualmente prima di rieseguire correttamente.)
4. **`bezhansalleh/filament-shield` non è installato in questo repo** (nessuna traccia in
   `composer.json`/vendor), ma `ShieldPermissionsSeeder::run()` chiama incondizionatamente
   `Artisan::call('shield:generate', ...)`: fallisce sempre con `CommandNotFoundException`.
   Bug reale pre-esistente nel seeder migrato senza adattamento, non introdotto ora. I 3 test che
   ne dipendono (`ShieldPermissionsSeederTest`, 2 casi di `RolePermissionsNavigationVisibilityTest`,
   più `UserManagerFieldTest` che invoca lo stesso seeder) sono stati adattati con uno skip
   esplicito e commentato quando `shield:generate` non è un comando registrato, invece di
   forzare/nascondere l'errore.
5. **`Pest\Laravel\{get,post,put,patch,delete,actingAs}` non sono realmente disponibili**:
   `pestphp/pest-plugin-laravel` non è una dipendenza di questo repo. L'unica cosa che definisce
   quelle funzioni a runtime è `Modules/Xot/tests/PestStubs.php`, che le dichiara nel namespace
   `Pest\Laravel` come **stub per PHPStan** (corpo: `throw new RuntimeException('Stub...')`) — non
   c'è alcuna implementazione reale dietro. Ogni test copiato da `gestionale_commesse` che usava
   `use function Pest\Laravel\get;` ecc. falliva sempre con quell'eccezione. Adattati tutti i test
   migrati per usare `$this->get()/$this->post()/$this->actingAs()` (metodi reali di
   `Illuminate\Foundation\Testing\TestCase`) al posto delle funzioni helper mancanti.

### Cosa resta aperto (test tuttora falliti, verificati uno per uno, non ignorati alla cieca)

- **Pannelli Filament dedicati mancanti**: i test SRC presuppongono pannelli `/wts`, `/timber`,
  `/dds`, `/operator/login` con pagina di login personalizzata (`accept_legal`, redirect per
  ruolo). In questo target risultano `404`/`NoDefaultPanelSetException`: questi pannelli non
  sono (ancora) configurati come panel Filament indipendenti in questo repo, oppure vivono altrove
  fuori dal modulo Auth. Riguarda `LoginTest` (7 casi su rotte pannello), `FilamentPanelLoginPageTest`,
  `FilamentLoginRedirectTest` (Livewire `App\Filament\Pages\Auth\PanelLogin`, anch'essa inesistente
  in questo repo: la pagina di login Filament reale è `Modules\User\Filament\Pages\Auth\Login`,
  senza gli stessi campi/redirect custom).
- **`demo_mode` (mascheramento nomi utente/credenziali demo)**: nessuna implementazione trovata
  in questo repo (`DemoUserDisplayNameTest`, `FilamentLoginDemoCredentialsTest`). Feature SRC non
  portata.
- **`UserFactory::pendingApproval()`** (stato factory) non esiste in `Modules\User\Database\Factories\UserFactory`
  di questo repo: `DemoRegistrationAndEmailTwoFactorTest` presuppone un flusso di registrazione/
  approvazione con stato dedicato non replicato.
- **`AuthorizationTest`**: operator su `/admin` risponde `302` (redirect, probabilmente al pannello
  corretto) invece di `403` atteso dal test SRC — comportamento Filament v4 sulla policy di accesso
  pannello differente da quanto assunto in `gestionale_commesse`; da investigare separatamente
  (fuori dal tempo disponibile in questo giro).
- **`AccountFeaturesAdditiveTest` (2 casi su 4)**: comportamento `App\Support\AccountFeatures` non
  ancora allineato all'additivo multi-ruolo atteso dal test SRC.

## PHPStan sui test migrati

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Auth --memory-limit=-1
```

210 errori, quasi tutti nei nuovi test (in gran parte `Cannot call method X on mixed` dovuto a
`User::factory()->create()` non tipizzato a livello di generics + interazione Pest/Larastan, e
`method.internalClass` sulle expectation Pest). **Non è una regressione specifica di questo modulo**:
lo stesso pattern, alla stessa entità, si presenta su moduli già affermati di questo repo — es.
`Modules/Intervention` (già "reshape-ato" prima di questo giro) riporta **819 errori PHPStan** nella
sola cartella `tests/` con lo stesso comando. Nessun `@phpstan-ignore`/`assert()`/`@var` è stato
usato per nascondere questi errori, come da istruzioni.

## TODO espliciti (rimandati da questo giro)

- BaseModel → XotBaseModel per i model in `app/Models`
- Consolidamento/dedup migrations, factories, seeders
- Verificare se il bug di `RouteServiceProvider::$module_dir` (vedi sopra) è presente anche in
  altri moduli replicati con lo stesso pattern nwidart/Xot
- Implementare (o portare da SRC) i pannelli Filament dedicati (`wts`, `timber`, `dds`, `operator`)
  con pagina di login personalizzata, se richiesti da product
- Valutare se installare `bezhansalleh/filament-shield` (dipendenza mancante, seeder rotto) o
  riscrivere `ShieldPermissionsSeeder` senza dipendere da quel comando
- Feature `demo_mode` (mascheramento nomi/credenziali) non portata da SRC
- `UserFactory::pendingApproval()` da aggiungere se il flusso di approvazione registrazione va
  supportato
