---
title: "Struttura canonica di un modulo Laraxot/XotBase"
type: concept
created: 2026-07-23
---

# Struttura canonica di un modulo Laraxot/XotBase

Documento di riferimento, basato su ispezione diretta di `laravel/Modules/Employee`, `laravel/Modules/User` e `laravel/Modules/Xot` (il modulo fondazionale) in questo repo, più le regole scritte in `docs/wiki/rules/*.md`. Ogni affermazione è ancorata a un file letto realmente; dove i moduli ispezionati divergono, la divergenza è segnalata esplicitamente invece di essere "risolta" per congettura.

## Struttura standard cartelle

Albero copiato/verificato su `laravel/Modules/Employee` (modulo maturo, coerente con `docs/wiki/rules/module-directory-structure-extended.mdc`):

```
Modules/Employee/
  app/
    Actions/                  # una classe = un'azione, trait QueueableAction, entrypoint execute()
    Console/Commands/
    Contracts/                # (vuoto in Employee: nessun Contracts custom necessario)
    Filament/
      Resources/
        WorkHourResource.php          # estende Modules\Xot\Filament\Resources\XotBaseResource
        WorkHourResource/
          Pages/                      # Pages specifiche (TimeClockPage, ecc.)
          Schemas/                    # WorkHourForm, WorkHourInfolist (getFormSchema/getInfolist)
      Widgets/                        # estendono XotBaseWidget/XotBaseTableWidget/ecc.
    Http/
      Controllers/
      Livewire/                       # NON app/Livewire — deve stare sotto Http/
      Middleware/
      Requests/
      Resources/
      Volt/
    Models/
      Employee.php
      WorkHour.php
      User.php                        # override locale di modulo (vedi sotto: divergenza)
      Admin.php
      Position.php
      Department.php
      TimeRecord.php / TimeEntry.php  # NB: nomi sovrapposti — vedi "ambiguità" più sotto
      BaseModel.php                   # base locale di modulo, a sua volta figlia di XotBase*
    Providers/
      EmployeeServiceProvider.php     # provider principale
      Filament/AdminPanelProvider.php # provider Filament del pannello
    Traits/
  config/
  database/
    factories/
    migrations/                       # 1 file per tabella, timestamp progressivo (vedi sotto)
    seeders/
  docs/                                # tutto minuscolo tranne README.md
  lang/{it,en,...}/
  resources/
    assets/{js,css}
    views/filament/{resources,pages,widgets}
  routes/
  tests/
    Feature/
    Unit/
    TestCase.php                      # estende Modules\Xot\Tests\XotBaseTestCase
    Pest.php                          # PascalCase, non pest.php
  composer.json                       # dipendenze proprie del modulo (autoload PSR-4 dedicato)
  module.json                         # metadati nwidart (providers, seeds, alias)
```

Confermato anche in `User` (stessa ossatura `app/{Actions,Filament,Http,Models,Providers,...}`, `database/{factories,migrations,seeders}`, `tests/{Feature,Unit}`, `lang/*`), con l'aggiunta in User di `app/Application/UseCases`, `app/Datas`, `app/Enums`, `app/Events`, `app/Facades`, `app/Notifications`, `app/Observers`, `app/Rules` — cartelle opzionali usate quando il dominio lo richiede, non obbligatorie in ogni modulo.

`Xot` (il modulo fondazionale, da cui derivano le classi base) ha una struttura molto più estesa perché contiene *tutte* le classi `XotBase*` usate da ogni altro modulo: `app/Models`, `app/Filament/{Resources,Pages,Widgets,Clusters,Blocks,Forms,Tables,Schemas,Infolists,...}`, `app/Database/Migrations/XotBaseMigration.php`, `app/Contracts`, `app/Traits`. Un modulo applicativo (Employee, User, ecc.) non duplica queste basi: le estende.

## Classi base obbligatorie

Verificate per nome file e/o `use` reali (namespace esatti da `laravel/Modules/Xot/app/...`):

| Categoria | Classe base | Namespace |
|---|---|---|
| Model generico | `XotBaseModel` | `Modules\Xot\Models\XotBaseModel` |
| Model con PK UUID | `XotBaseUuidModel` | `Modules\Xot\Models\XotBaseUuidModel` |
| Model gerarchico (tree) | `XotBaseTreeModel` | `Modules\Xot\Models\XotBaseTreeModel` |
| Pivot | `XotBasePivot` / `XotBaseMorphPivot` | `Modules\Xot\Models\XotBasePivot` / `XotBaseMorphPivot` |
| Migrazione | `XotBaseMigration` | `Modules\Xot\Database\Migrations\XotBaseMigration` |
| Filament Resource | `XotBaseResource` | `Modules\Xot\Filament\Resources\XotBaseResource` (confermato in uso reale: `Modules\Employee\Filament\Resources\WorkHourResource extends XotBaseResource`) |
| Filament Resource Pages | `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`, `XotBaseManageRecords`, `XotBaseManageRelatedRecords` | `Modules\Xot\Filament\Resources\Pages\*` |
| Filament Page | `XotBasePage` | `Modules\Xot\Filament\Pages\XotBasePage` |
| Filament Dashboard | `XotBaseDashboard` | `Modules\Xot\Filament\Pages\XotBaseDashboard` |
| Filament Cluster | `XotBaseCluster` | `Modules\Xot\Filament\Clusters\XotBaseCluster` |
| Filament Widget (generico) | `XotBaseWidget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` |
| Filament Widget tabella | `XotBaseTableWidget` | `Modules\Xot\Filament\Widgets\XotBaseTableWidget` |
| Filament Widget form/schema | `XotBaseSchemaWidget` | `Modules\Xot\Filament\Widgets\XotBaseSchemaWidget` |
| Filament Widget wizard | `XotBaseWizardWidget` | `Modules\Xot\Filament\Widgets\XotBaseWizardWidget` |
| Filament Widget stats | `XotBaseStatsOverviewWidget` | `Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget` |
| Filament Widget chart | `XotBaseChartWidget` | `Modules\Xot\Filament\Widgets\XotBaseChartWidget` |
| Relation Manager | `XotBaseRelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |
| Form Field custom | `XotBaseField` / `XotBaseFormComponent` | `Modules\Xot\Filament\Forms\Components\*` |
| Table Column custom | `XotBaseColumn` / `XotBaseTextColumn` / ecc. | `Modules\Xot\Filament\Tables\Columns\*` |
| Action Filament | `XotBaseAction` / `XotBaseActionGroup` / `XotBaseAttachAction` | `Modules\Xot\Filament\Actions\*` |
| Test case | `XotBaseTestCase` | `Modules\Xot\Tests\XotBaseTestCase` (confermato: `Modules\Employee\Tests\TestCase extends XotBaseTestCase`) |

Regola generale scritta in `docs/wiki/rules/module-directory-structure-extended.mdc`: "Regola Inviolabile: I componenti Filament devono seguire una struttura di directory specifica e estendere solo classi `XotBase*`." Nessun Resource/Page/Widget di modulo estende direttamente classi Filament core.

Il modulo `Employee` ha anche una `BaseModel.php` locale — un ulteriore livello di astrazione di modulo sopra `XotBase*`, coerente con il pattern "wrapper di modulo che poi estende Xot" già visto in altri moduli maturi. Non è un obbligo architetturale documentato esplicitamente, ma un pattern osservato.

## Convenzioni naming

- Nome modulo: **PascalCase singolare** (`Employee`, `User`, `Xot`), alias lowercase in `module.json` (`"alias": "employee"`).
- Model: **PascalCase singolare** (`Employee.php`, `WorkHour.php`, `Department.php`), corrisponde 1:1 alla tabella al plurale snake_case (`work_hours`, `departments`).
- Filament Resource: `{Model}Resource` (`WorkHourResource`), sottocartella omonima `{Model}Resource/Pages` e `{Model}Resource/Schemas` per Form/Infolist.
- Action: suffisso `*Action`, cartella `app/Actions/`, trait `Spatie\QueueableAction\QueueableAction`, singolo entrypoint pubblico `execute()` (verificato in `Employee/app/Actions/ExportTimeDataAction.php`). Skill dedicata: `queueable-action-trait`.
- Contract: suffisso `*Contract`, cartella `app/Contracts/` (mai `app/Interfaces/` né suffisso `*Interface` — regola in skill `module-contracts-naming-placement`); Employee non ne ha di custom, ma la cartella esiste come slot standard.
- Migrazioni: `{timestamp}_create_{table_plural}_table.php`, classe anonima `return new class() extends XotBaseMigration`.
- Documentazione: tutto `docs/` in minuscolo tranne `README.md` (regola esplicita in `module-directory-structure-extended.mdc`).
- Livewire: sempre sotto `app/Http/Livewire/`, mai `app/Livewire/` a livello di modulo (regola esplicita, con eccezione tollerata solo dentro Filament Widgets — vedi skill `no-module-livewire-use-filament-widgets`).
- Namespace PSR-4: `Modules\{ModuleName}\` → `app/`, mappato in `composer.json` del modulo stesso (autoload dedicato, non nel composer.json di root — vedi `NWIDART_MODULAR_ARCHITECTURE.md`).

## Ambiguità osservate (da segnalare, non risolte per congettura)

- In `Employee/app/Models` esistono sia `TimeRecord.php` sia `TimeEntry.php` e sia `Admin.php` sia `User.php` accanto a `Employee.php`: più modelli "utente"-simili convivono nello stesso modulo. Non è chiaro dalla sola ispezione quale sia il canonico attivo per la tabella "utenti"; un agente di conversione deve verificare le migrazioni corrispondenti (1 modello = 1 migrazione, vedi sotto) prima di assumere equivalenze.
- La migrazione di esempio letta (`2025_08_27_121400_create_work_hours_table.php`) usa `$table->timestamps()` esplicito nel blocco `tableCreate`, **in apparente contraddizione** con la regola scritta in `docs/wiki/rules/migration-update-timestamps-only.md` che vieta `$table->timestamps()` e impone `$this->updateTimestamps($table, hasSoftDeletes: ...)` solo dentro `tableUpdate()`. Questo è probabilmente debito residuo nel modulo Employee stesso (la regola è più recente, `created: 2026-06-05`, della migrazione osservata) — un agente di conversione deve seguire la regola scritta (updateTimestamps), non imitare questo file specifico.

## Regole migrazioni

- Base obbligatoria: `Modules\Xot\Database\Migrations\XotBaseMigration` (`laravel/Modules/Xot/app/Database/Migrations/XotBaseMigration.php`).
- **1 modello persistibile = 1 file `create_{table}_table.php`** (`docs/wiki/rules/one-migration-per-model.md`). Vietato creare nuove migrazioni `add_*`/`alter_*` per evolvere lo schema: si modifica il file canonico esistente e si fa bump del timestamp nel nome file, cosa che forza il re-run di `tableUpdate()` sulle installazioni esistenti.
- Struttura interna canonica:
  ```php
  return new class() extends XotBaseMigration
  {
      protected ?string $model_class = MyModel::class; // opzionale, altrimenti dedotto dal nome file

      public function up(): void
      {
          $this->tableCreate(static function (Blueprint $table): void {
              $table->id();
              // colonne per installazioni nuove
          });

          $this->tableUpdate(function (Blueprint $table): void {
              if (! $this->hasColumn('campo')) {
                  $table->string('campo')->nullable();
              }
              $this->updateTimestamps(table: $table, hasSoftDeletes: true); // SOLO qui
          });
      }
  };
  ```
- **Vietato** nello stesso file: `$table->timestamps()`, `$table->softDeletes()`, `$table->string('created_by')/updated_by/deleted_by` manuali — sostituiti dall'helper `updateTimestamps()` che usa `foreignIdFor(User)` (non colonne string). Regola: `docs/wiki/rules/migration-update-timestamps-only.md`.
- Contratto ID+UUID: ogni tabella dovrebbe avere sia `id` bigint auto-increment sia `uuid` char(36) nullable indicizzato (da `one-migration-per-model.md`).
- `XotBaseMigration` fornisce helper idempotenti pronti all'uso: `tableExists()`, `hasColumn()`, `hasTable()`, `hasIndex()`, `tableCreate()`, `tableUpdate()`, `renameColumn()`, `dropTableIfExists()`, oltre a utility di conversione UUID→bigint per migrazioni legacy.
- Audit disponibili: `bashscripts/tools/audit-module-artifact-parity.sh`, `audit-duplicate-create-migrations.sh`, `audit-migration-timestamp-redundancy.sh`.

## Regole test

- Framework: **Pest** esclusivamente (skill `psr4-test-filename-pascalcase`, regola `docs/wiki/rules/testing-modules-pest.md`).
- File Pest bootstrap: solo `Modules/<M>/tests/Pest.php`, **mai** `pest.php` minuscolo.
- Config PHPUnit: **solo** `laravel/phpunit.xml` centrale, eseguito da `laravel/` con `--configuration phpunit.xml` (mai `phpunit-{Modulo}.xml` locali).
- `TestCase` di modulo estende `Modules\Xot\Tests\XotBaseTestCase` (verificato: `Modules\Employee\Tests\TestCase extends XotBaseTestCase`), mai `Tests\TestCase` di root (che porta `RefreshDatabase`).
- **Vietato** `RefreshDatabase`/`DatabaseMigrations`; usare `DatabaseTransactions` solo se il test tocca davvero il DB (regola "dati sacri" — `data-sacred-no-destructive-db.md`, `never-use-refresh-database.md`).
- Test in `tests/Feature/` e `tests/Unit/`, stile Pest funzionale (`test('...', function () {...})` o `it('...')`), non `@test` PHPUnit-style.
- Comando di esecuzione canonico (da `laravel/`):
  ```bash
  ./vendor/bin/pest Modules/<Modulo>/tests/ \
    --configuration phpunit.xml \
    --coverage --coverage-filter=Modules/<Modulo>/app \
    --only-summary-for-coverage-text --colors=never --compact
  ```
- Factory: se la migrazione usa `$table->id()` auto-increment, la factory non deve mai valorizzare manualmente `id`.

## Owned File/Module Scope e dipendenze modulo

- Ogni modulo è un package nwidart indipendente: `composer.json` proprio (autoload PSR-4 dedicato `Modules\{Nome}\` → `app/`, più `Database\Factories` e `Database\Seeders`), `vendor/` e strumenti (phpstan, pest) eseguiti **da dentro** la cartella del modulo, non dalla root (`docs/wiki/rules/NWIDART_MODULAR_ARCHITECTURE.md`).
- `module.json` è la fonte di verità (SSoT) per: `providers[]` (elenco dei service provider da registrare), `migration.seeds[]`, `alias`, `keywords`. **Vietato** registrare manualmente provider di dominio dentro `register()` del provider principale del modulo (`$this->app->register(...)`) — vanno dichiarati in `module.json.providers[]` **e** allineati in `composer.json.extra.laravel.providers[]` (stessa lista, stesso ordine). Regola: `docs/wiki/rules/module-providers-manifest.md`.
- Root `composer.json` resta uno skeleton minimo — non riceve dipendenze specifiche di dominio (skill `nwidart-composer-root-minimal`, `composer-root-skeleton-modular.md`).
- Dipendenze inter-modulo si dichiarano importando la classe con full namespace (`Modules\User\Models\User`) — non c'è autoload implicito cross-modulo. Se un modulo dipende da un altro (es. Employee dipende da User, Media, Gdrp, Activity — visibile dagli `use` in `Employee/app/Models/User.php`), quella dipendenza va anche riflessa nel `require` del `composer.json` del modulo se il pacchetto è esterno, o semplicemente tollerata via path-repository se è un altro modulo del monorepo.

## Checklist di conversione da struttura DDD esterna a questa struttura

Passi concreti per un agente che porta un modulo da `_bases/gestionale_commesse` (stile Domain/Application/Infrastructure) a un modulo Laraxot/XotBase:

1. **Crea lo scheletro del modulo target** con `php artisan module:make {Nome}` (o copia manuale della struttura standard sopra) sotto `laravel/Modules/{Nome}/`, popolando subito `module.json` (name, alias, providers vuoti) e `composer.json` (autoload PSR-4 `Modules\{Nome}\` → `app/`).

2. **Domain/Models/*.php → app/Models/*.php**
   - Ogni Entity/Model del dominio esterno diventa un Eloquent Model in `app/Models/`.
   - Deve estendere `Modules\Xot\Models\XotBaseModel` (o `XotBaseUuidModel` se la chiave primaria è UUID, o `XotBaseTreeModel` se gerarchico).
   - Rimuovere qualunque logica di persistenza custom (repository pattern manuale) se l'Eloquent Model può gestirla nativamente con relazioni.
   - Se il modulo ha bisogno di una base condivisa locale (come `Employee/app/Models/BaseModel.php`), crearla come sottoclasse di `XotBase*`, non come sostituto.

3. **Infrastructure/Persistence/Migrations → database/migrations/*.php**
   - Una migrazione per modello (`create_{table}_table.php`), classe anonima `extends XotBaseMigration`.
   - Colonne principali dentro `tableCreate()`; qualunque colonna aggiuntiva "evolutiva" dentro `tableUpdate()` con guardie `hasColumn()`.
   - Timestamps/soft delete/audit-by SOLO tramite `$this->updateTimestamps($table, hasSoftDeletes: true/false)` dentro `tableUpdate()` — mai `$table->timestamps()` esplicito.
   - Se la struttura esterna prevedeva più migrazioni evolutive per la stessa tabella (tipico in Laravel classico `add_x_to_table`), **consolidarle in un solo file canonico**.

4. **Application/UseCases o Domain/Services → app/Actions/*.php**
   - Ogni Use Case / Service con singola responsabilità diventa una classe `{Verbo}{Oggetto}Action` in `app/Actions/`.
   - Aggiungere `use Spatie\QueueableAction\QueueableAction;` e un solo metodo pubblico `execute(...)`.
   - Se il codice esterno ha un Service con più metodi pubblici, **spezzarlo in più Action**, una per operazione.

5. **Domain/Contracts o Interfaces → app/Contracts/*Contract.php**
   - Rinominare ogni `*Interface`/`*RepositoryInterface` in `*Contract`.
   - Spostare sotto `app/Contracts/` (mai `app/Interfaces/`).

6. **Infrastructure/Filament o presentazione admin → app/Filament/**
   - Resource CRUD esterni → `app/Filament/Resources/{Model}Resource.php extends XotBaseResource`, con sottocartelle `{Model}Resource/Pages/` e `{Model}Resource/Schemas/` per form/infolist se il resource è complesso.
   - Ogni pagina Filament custom → `app/Filament/Pages/*.php extends XotBasePage` (o `XotBaseDashboard`).
   - Ogni widget → `app/Filament/Widgets/*.php`, scegliendo la sottoclasse `XotBase*Widget` corretta in base al tipo (tabella, form/schema, wizard, stats, chart).
   - Nessuna estensione diretta di classi Filament core (`\Filament\Resources\Resource`, `\Filament\Pages\Page`, ecc.) — sempre tramite `XotBase*`.

7. **Infrastructure/Http (controller esterni, se presenti) → app/Http/**
   - Controller → `app/Http/Controllers/`.
   - Middleware → `app/Http/Middleware/`.
   - Form Request → `app/Http/Requests/`.
   - Componenti Livewire standalone → valutare se convertirli in Filament Widget invece (regola `no-module-livewire-use-filament-widgets`); se restano Livewire puri, vanno comunque sotto `app/Http/Livewire/`.

8. **Bootstrap/ServiceProvider esterno → app/Providers/{Nome}ServiceProvider.php + app/Providers/Filament/AdminPanelProvider.php**
   - Non registrare manualmente provider di dominio in `register()`.
   - Dichiarare tutti i provider (incluso quello Filament) in `module.json.providers[]` e nello stesso ordine in `composer.json.extra.laravel.providers[]`.

9. **Test esterni (PHPUnit/altro) → tests/Feature|Unit con Pest**
   - Convertire ogni test in sintassi Pest (`test()`/`it()`), niente `@test`.
   - `TestCase` del modulo deve estendere `Modules\Xot\Tests\XotBaseTestCase`.
   - Rimuovere ogni uso di `RefreshDatabase`/`DatabaseMigrations`; usare `DatabaseTransactions` se serve accesso reale al DB.
   - Verificare che le factory non forzino `id` se la tabella usa `$table->id()` auto-increment.

10. **Traduzioni/lang esterne → lang/{it,en,...}/**, seguendo la struttura piatta già presente negli altri moduli (namespace di traduzione = nome modulo lowercase).

11. **Verifica finale per ogni modulo portato**:
    - `cd laravel/Modules/{Nome} && composer install` (dipendenze isolate).
    - `./vendor/bin/phpstan analyse Modules/{Nome} --memory-limit=-1` da `laravel/` (livello max, regola in skill `phpstan-module-scope`).
    - `./vendor/bin/pest Modules/{Nome}/tests/ --configuration phpunit.xml` da `laravel/`.
    - Eseguire gli audit strutturali: `audit-module-artifact-parity.sh`, `audit-duplicate-create-migrations.sh`, `audit-migration-timestamp-redundancy.sh`, `audit-pest-bootstrap-naming.sh`, `audit-phpunit-central-config.sh`.
    - Verificare manualmente che nessuna classe Filament/Model estenda direttamente una classe core invece di `XotBase*` (`grep -rn "extends \\\\Filament\|extends Model\b" app/`).

## Fonti citate

- `laravel/Modules/Employee/` (struttura, migrazione `work_hours`, `WorkHourResource.php`, `ExportTimeDataAction.php`, `tests/TestCase.php`, `module.json`, `composer.json`)
- `laravel/Modules/User/` (struttura estesa di confronto)
- `laravel/Modules/Xot/app/Models/*.php`, `laravel/Modules/Xot/app/Filament/**/XotBase*.php`, `laravel/Modules/Xot/app/Database/Migrations/XotBaseMigration.php`
- `docs/wiki/rules/module-directory-structure-extended.mdc`
- `docs/wiki/rules/NWIDART_MODULAR_ARCHITECTURE.md`
- `docs/wiki/rules/one-migration-per-model.md`
- `docs/wiki/rules/migration-update-timestamps-only.md`
- `docs/wiki/rules/testing-modules-pest.md`
- `docs/wiki/rules/module-providers-manifest.md`
