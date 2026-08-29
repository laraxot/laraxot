---
title: "Import status — Timber (ex TimberBilling)"
type: concept
tags: [import, gestionale-commesse, timber, bmad]
created: 2026-07-23
updated: 2026-07-27
qmd: "import status Timber (ex TimberBilling) enabled gate basemodel connection foreignIdFor ordering"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Timber (ex TimberBilling)

## 2026-07-27 — Riabilitato, BaseModel, 8 bug FK/naming corretti

`modules_statuses.json`: **true** (riabilitato, confermato dall'utente). PHPStan e Pest
riverificati da zero, non presunti dallo stato di sessioni precedenti.

### `Modules/Timber/app/Models/BaseModel.php` — creato (mancava)

29 modelli su 29 estendevano `Illuminate\Database\Eloquent\Model` o `XotBaseModel`
direttamente, nessuno dichiarava una connection esplicita (violazione
`docs/wiki/rules/basemodel-connection`, skill `basemodel-connection`). Creato
`Modules/Timber/app/Models/BaseModel.php extends XotBaseModel` con
`protected $connection = 'timber_billing';` (= `Module::find('Timber (ex TimberBilling)')->getSnakeName()`);
tutti i 29 modelli ora `extends BaseModel` (namespace condiviso, nessun nuovo `use`
necessario). `tests/TestCase.php`: `$connectionsToTransact` aggiornato con
`'timber_billing'` (altrimenti le scritture su quella connection non rientrano nel
rollback per-test → dati che sopravvivono tra test, causa di
`UniqueConstraintViolationException` intermittenti osservate prima del fix).

### Bug reali corretti (FK ordering / naming — non solo infra)

| File | Problema | Fix |
|---|---|---|
| `create_timber_agent_commissions_table` | FK `agent_id`→`timber_agents` creata dopo (timestamp) | bump timestamp dopo `timber_agents` |
| `create_timber_derived_materials_table` | FK verso `timber_lots`/`timber_processing_steps` create dopo | bump timestamp tra le due dipendenze e il suo dipendente (`timber_packaged_units`) |
| `create_timber_processing_step_processable_table` | nome vincolo FK auto-generato >64 char (MySQL 1059) | `constrained(indexName: 'timber_step_processable_step_fk')` |
| stesso file | colonna FK generata come `timber_processing_step_id` ma il model (`morphedByMany(..., 'processing_step_id')`) si aspettava `processing_step_id` | `foreignIdFor(TimberProcessingStep::class, 'processing_step_id')` |
| `create_timber_purchase_records_table` | `foreignIdFor(TimberSupplier::class)`/`(TimberTransporter::class)` senza 2° argomento → colonna `timber_supplier_id`/`timber_transporter_id`, ma model/Action si aspettano `supplier_id`/`transporter_id` | aggiunto nome colonna esplicito a entrambi |
| `create_timber_invoice_lines_table` | stesso pattern: `TimberInvoice::class` → colonna attesa `invoice_id`, non `timber_invoice_id` | nome esplicito |
| `create_timber_lots_table` | stesso pattern: `TimberPurchaseRecord::class` → atteso `purchase_record_id` | nome esplicito |
| `create_timber_humidity_checks_table` | stesso pattern: `TimberDryingCycle::class` → atteso `drying_cycle_id` | nome esplicito |
| `create_timber_packaged_units_table` | stesso pattern: `TimberDerivedMaterial::class` → atteso `derived_material_id` | nome esplicito |
| `2026_07_09_100300_drop_unique_label_code_on_timber_label_prints` | interamente ridondante: `create_timber_label_prints_table` corrente crea già `label_code` come indice non-unique | archiviato in `_bak/*.merged` |

**Pattern sistemico**: `foreignIdFor(Model::class)` senza secondo argomento genera il
nome colonna dalla classe intera (es. `timber_processing_step_id`), non dal nome
"naturale" più corto che modelli/Action esistenti si aspettano. Da verificare per
ogni futura conversione `foreignId()→foreignIdFor()` in questo modulo: controllare
sempre le relazioni `belongsTo()` del modello target prima di omettere il 2° argomento.

### Pest: 86/169 passed (era 13/169 in sessione 2026-07-24, poi 0 con DB test assente)

Residuo (~80 fail) quasi tutto `Table 'roles' doesn't exist` — **non un bug
Timber (ex TimberBilling)**: conseguenza diretta della rimozione di `Modules/Auth` (proprietario
di ruoli/permessi) decisa dall'utente 2026-07-27, vedi
`docs/chat/modules-auth-blog-comment-removed.md`. Non risolvibile dentro questo
modulo; richiede una decisione di design su dove vive ora la gestione ruoli/permessi.

- [x] Cartella / reshape `app/` + XotBase SP
- [ ] Resources → XotBaseResource (wave)
- [ ] PHPStan L10 zero reali
- [ ] `modules_statuses=false` — gate aperto

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## 2026-07-24 — Porting `app/Timber` + `app/Filament/Timber` (namespace fantasma mai migrato)

Scoperta: `gestionale_commesse/app/Timber/` (66 file: Models, Actions, Queries, Data,
Support, Legacy) e `gestionale_commesse/app/Filament/Timber/` (Resources/Pages) non
erano MAI stati copiati in nessuna wave precedente, pur essendo referenziati da
`Modules/Timber` (`App\Timber\Models\TimberDocument`,
`App\Timber\Actions\RegisterTimberStockMovementAction`, ecc. — namespace inesistente).

### Migrato

- `app/Timber/{Models,Actions,Queries,Data,Support,Legacy}` → `app/{Models,Actions,Queries,Data,Support,Legacy}`
  del modulo (merge con le cartelle già esistenti Models/Actions/Support).
- `app/Filament/Timber/{Pages,Resources}` → `app/Filament/{Pages,Resources}`.
- `database/factories/Timber*Factory.php` (19 file) — mancavano, referenziati da
  `@use HasFactory<...>` sui modelli.
- `database/seeders/TimberDemoSeeder.php` — mancava, referenziato da
  `TimberDemoSeederTest`.
- `database/migrations/*` (13 file: `create_timber_tables`, `create_timber_customers_table`,
  `create_timber_products_table`, `create_timber_stock_movements_table`,
  `create_timber_documents_tables`, `create_timber_wood_species_table`,
  `create_timber_price_lists_tables`, `create_timber_agent_commissions_table`,
  `create_timber_contact_notes_table` + 4 migrazioni di alter) — necessarie perché i
  modelli migrati referenziano tabelle mai create in questo repo.
- `config/timber.php` (root config dell'app sorgente) → `Modules/Timber/config/timber.php`,
  con **tutti** i `config('timber.xxx')` nel codice migrato riscritti in
  `config('timber.timber.xxx')` (namespacing standard XotBase per config di modulo).
- `tests/Feature/Timber/*.php` (32 file) → `tests/Feature/` del modulo, namespace
  `Modules\Timber (ex TimberBilling)\Tests\Feature`; creato `tests/TestCase.php` (mancava, pattern
  identico a `Modules/Customer/tests/TestCase.php` con
  `prepareSharedFixcitySqliteForTesting()`) e `tests/Pest.php`.

Tutti i `namespace App\Timber\...` / `use App\Timber\...` → `Modules\Timber (ex TimberBilling)\...`;
`App\Filament\Timber\...` → `Modules\Timber (ex TimberBilling)\Filament\...`; `App\Models\User` →
`Modules\User\Models\User` (stesso bug-pattern già risolto in `Modules/Auth` in questa
sessione).

### Bug funzionali reali corretti

- `IssueTimberInvoiceAction`, `GetTimberAgendaQuery`, `GetTimberBusinessAnalyticsQuery`,
  `GetTimberVatLiquidationQuery`: `use App\Timber\...` puntava al namespace fantasma →
  ora puntano alle classi `Modules\Timber (ex TimberBilling)\...` appena migrate.
- `SyncEasyfattProductsAction`/`SyncEasyfattCustomersAction`: importavano
  `Modules\Catalog\Infrastructure\Models\Product` e
  `Modules\Customers\Infrastructure\Models\Customer` (namespace DDD dell'app sorgente,
  mai esistiti in questo repo) → corretti a `Modules\Catalog\Models\Product` e
  `Modules\Customer\Models\Customer` (namespace reali di questo monorepo).
- Migrazioni con `$table->foreignId('*_user_id')->constrained('users')`: incompatibili,
  perché `users.id` in questo repo è `string(36)` (UUID), non bigint auto-increment →
  riscritte in `string(36)` + `foreign()->references('id')->on('users')`. Bug bloccante:
  senza questo fix nessuna migrazione del batch passava su MariaDB (FK type mismatch,
  errore 3780).
- `timber_processing_step_processable`: `$table->morphs('processable')` generava un
  nome indice MySQL/MariaDB > 64 caratteri (errore 1059) → nome indice esplicito
  `timber_step_processable_morph_idx`.
- `app/Support/AccountFeatures.php` (root, condiviso): mancava `canAccessPanel()`,
  chiamato da 16 pagine Filament Timber appena migrate (`TimberDashboard::canAccess()`
  e affini) → sarebbe stato un fatal error a runtime. Aggiunto shim permissivo
  coerente con lo stile Wave 1 del file (ruolo admin o ruolo `{panel}`/`{panel}_operator`/
  `{panel}_admin`).
- Factory `TimberCustomerFactory`/`TimberSupplierFactory`: `$this->faker->stateAbbr()`
  non esiste nel locale di questo progetto (bug — mai eseguibile) → sostituito con
  `randomElement(['MI','RM','TO',...])`, stesso pattern di `Modules/Customer`.
- `TimberPriceListFactory`: `ucfirst($this->faker->unique()->words(2, true))` — lo stub
  Faker tipizza `words()` come `array|string` → cast esplicito a stringa.
- `@use HasFactory<...>` e `newFactory()` su 18 modelli: FQCN senza `\` iniziale nel
  docblock/tipo di ritorno veniva risolto relativo al namespace corrente
  (`Modules\Timber (ex TimberBilling)\Models\Modules\Timber (ex TimberBilling)\Database\Factories\...`) →
  aggiunto leading backslash.
- Nei test con `namespace Modules\Timber (ex TimberBilling)\Tests\Feature;`, le classi native
  non importate (`DateTimeImmutable`, `RuntimeException`, `InvalidArgumentException`)
  si risolvevano nel namespace del test invece che in quello globale (le funzioni PHP
  fanno fallback al namespace globale, le classi no) → aggiunti `use` espliciti.

### PHPStan

`./vendor/bin/phpstan analyse Modules/Timber --memory-limit=-1`: da 338 errori
(namespace fantasma + modelli senza `@property` + factory rotte) a **48 errori residui**
su solo `app/` (senza `tests/`, dove Pest namespaced genera rumore `method.internalClass`
pre-esistente e comune a tutti i moduli, es. `Modules/Customer/tests`). Aggiunti
`@property` a 24 modelli (generati dalle migration reali via script una-tantum) per
risolvere gli `property.notFound`.

Residuo aperto (49 errori su `app/`, non toccato in questa sessione, priorità bassa):
`BaconQrCode` non ha stub PHPStan (falsi positivi `class.notFound` su
`RenderTimberQrSvgAction`), wrapper `thecodingmachine/safe` non applicato in alcuni file
copiati (`fopen`/`fputcsv`/`preg_split` ecc.), `Barryvdh\DomPDF` stub incompleto,
mismatch di tipo reale fra `Modules\Timber (ex TimberBilling)\Models\TimberProduct`/`TimberCustomer`
(registro Timber) e `Modules\Catalog\Models\Product`/`Modules\Customer\Models\Customer`
(registro Billing) in `IssueTimberInvoiceAction` → `RegisterTimberStockMovementAction`:
sono due modelli di dati paralleli (mai pensati per interoperare), richiede una decisione
di design (quale registro è quello "vero" per fatturazione) fuori scope per questa
sessione.

### Test

`vendor/bin/pest Modules/Timber/tests`: **13 passed / 156 failed** (169 test).//
Root cause dei 156 fallimenti, NON regressioni di questa migrazione:

1. **`modules_statuses.json` → `"Timber (ex TimberBilling)": false`** (vincolo esplicito di questa
   sessione, non modificabile): il `ServiceProvider` del modulo non viene mai bootato da
   `nwidart/laravel-modules` a runtime, quindi `registerConfig()` (che carica
   `config/timber.php` e `config/config.php` sotto la chiave `timber.*`) non
   gira mai. `XotBaseTestCase`/`TestCase::getPackageProviders()` (pattern copiato da
   `Modules/Customer`) presuppone Orchestra Testbench, ma questo repo usa
   `Illuminate\Foundation\Testing\TestCase` con boot reale di `bootstrap/app.php” — quel
   metodo non viene mai chiamato, quindi il provider non si registra nemmeno nei test.
   Risultato: tutti i test che leggono `config('timber.timber.*')` (business
   rules, prefissi, ecc.) vedono fallback vuoti.
2. Tabelle `roles`/`permissions`/`model_has_roles`/`users` assenti dal fixture sqlite
   condiviso `database/fixcity_data.sqlite` (usato via `prepareSharedFixcitySqliteForTesting()`,
   stesso meccanismo di `Modules/Customer`) → tutti i test Filament/Livewire che fanno
   `Role::findOrCreate(...)`/`$user->syncRoles(...)` falliscono con "no such table".
   Limitazione nota e già documentata in `Modules/Auth/tests/TestCase.php` per lo stesso
   motivo (assenza di un DB di test reale per questo monorepo).
3. Le 13 tabelle `timber_*` sono state migrate manualmente contro
   `database/fixcity_data.sqlite` per questa sessione (`DB_DATABASE=.../fixcity_data.sqlite
   php artisan migrate --database=sqlite --path=Modules/Timber/database/migrations`);
   non c'è un meccanismo automatico che le applichi in CI per un modulo `disabled`.

I 13 test che passano sono quelli puramente di logica pura (Eloquent + Support, senza
Filament/ruoli/config): `TimberCommissionResolverTest`, `TimberTraceCodeTest`,
`TimberPriceResolverTest` (parziale), ecc.

### Aperto

- Estendere il DB di test (`workorder_data_test` / `database/fixcity_data.sqlite`) con
  `roles`/`permissions`/`model_has_roles` — **assenti dopo la rimozione di `Modules/Auth`**
  (decisione utente 2026-07-27, non un bug di Timber (ex TimberBilling)). Causa la maggioranza dei
  fallimenti Pest residui (assert su ruoli `timber_admin`/`timber_operator`). Richiede
  prima una decisione di design su dove vive ora la gestione ruoli/permessi in questo
  progetto (era in Auth), non risolvibile dentro Timber (ex TimberBilling).
- Decidere se `IssueTimberInvoiceAction` deve fatturare sui modelli Timber-nativi
  (`TimberProduct`/`TimberCustomer`) o sui modelli Catalog/Customer del resto del
  monorepo — oggi sono mischiati e PHPStan lo segnala come `argument.type`.
- Stub PHPStan per `BaconQrCode` e wrapper `thecodingmachine/safe` non applicati in
  ~15 punti del codice copiato (basso rischio, solo qualità tipizzazione).

## Rename 2026-08-29: `TimberBilling` → `Timber`

Il modulo contiene l'intero bounded context del verticale legno (lotti, essiccazione, listini, giacenze,
tracciabilità, etichette, agenti, fatturazione timber-specifica), non solo la fatturazione: il nome è stato
allineato al dominio (screaming architecture). Non spezzato: i 4 modelli invoice sono sotto-dominio interno,
la fatturazione generica resta nel modulo `Billing`. Rinominati: cartella, namespace `Modules\Timber`,
`TimberServiceProvider`, alias `timber`, config `config('timber.*')`, testsuite phpunit, `modules_statuses.json`.
Tabelle DB invariate (`timber_*`). Vedi `docs/gestionale-commesse-comparison/delta-second-tranche.md`.
