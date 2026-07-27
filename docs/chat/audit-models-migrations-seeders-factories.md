# Audit: Modelli ↔ Migration ↔ Seeder ↔ Factory (tutti i moduli)

> Handoff multi-agente. Regola richiesta: per ogni modello concreto → 1 migration + 1 seeder + 1 factory.
> Attenzione Ponytail §7: NON generare stub vuoti per modelli pivot/astratti/STI o tabelle condivise.
> Migration ≠ sempre 1:1 col modello (alter/pivot/view). Verificare caso per caso.

## Matrice (models = classi concrete in app/Models, esclusi abstract)

| Modulo | Models | Migr | Seed | Fact | Gap principale |
|--------|-------:|-----:|-----:|-----:|----------------|
| Activity | 4 | 4 | 5 | 4 | ok |
| AI | 4 | 4 | 1 | 0 | seed, factory mancanti |
| AiAssistant | 4 | 4 | 5 | 4 | ok |
| Billing | 7 | 7 | 0 | 0 | seed+factory assenti |
| Blog | 15 | 15 | 17 | 13 | 2 factory |
| Bom | 2 | 2 | 0 | 2 | seed assenti |
| Catalog | 7 | 7 | 1 | 0 | seed+factory |
| Cms | 7 | 3 | 9 | 8 | migration < models |
| Comment | 4 | 3 | 5 | 4 | migration |
| Compliance | 9 | 9 | 2 | 0 | seed+factory |
| Customer | 5 | 5 | 6 | 5 | ok |
| Document | 1 | 1 | 2 | 1 | ok |
| Email | 3 | 3 | 1 | 3 | seed |
| Employee | 9 | 3 | 2 | 9 | migration+seed |
| EnergyBroker | 6 | 6 | 0 | 0 | seed+factory |
| Gdpr | 4 | 8 | 6 | 4 | migration > models (ok se alter) |
| Geo | 12 | 4 | 15 | 11 | migration << models |
| HR | 5 | 5 | 0 | 5 | seed |
| Intervention | 8 | 8 | 10 | 8 | ok |
| Inventory | 5 | 5 | 0 | 4 | seed, 1 factory |
| Job | 15 | 13 | 16 | 15 | 2 migration |
| Lang | 4 | 3 | 4 | 3 | migration, factory |
| Media | 3 | 3 | 4 | 3 | ok |
| Notify | 12 | 16 | 15 | 12 | ok (migration alter) |
| Platform | 2 | 2 | 12 | 0 | factory |
| Production | 2 | 2 | 0 | 2 | seed |
| PublicProcurement | 15 | 16 | 1 | 0 | seed+factory |
| Quotation | 5 | 5 | 7 | 5 | ok |
| Rating | 2 | 6 | 3 | 1 | factory |
| Seo | 0 | 0 | 1 | 0 | n/a |
| Signature | 2 | 2 | 3 | 2 | ok |
| Tenant | 7 | 0 | 9 | 8 | migration assenti (host module?) |
| TimberBilling | 28 | 17 | 2 | 23 | migration+seed |
| UI | 3 | 3 | 4 | 4 | ok |
| User | 38 | 57 | 43 | 38 | ok (molte alter) |
| Vehicle | 2 | 2 | 3 | 2 | ok |
| WhatsApp | 3 | 4 | 1 | 3 | seed |
| WorkOrder | 5 | 5 | 6 | 5 | ok |
| Xot | 12 | 10 | 13 | 13 | ok |

## Priorità gap (seed/factory a 0)
- Billing, EnergyBroker: seed=0 factory=0
- AI, Catalog, Compliance, PublicProcurement: factory=0
- Bom, HR, Inventory, Production, Tenant(migr=0): seed=0

## Note metodo
- `models` = classi concrete (grep `class`, esclusi `abstract`).
- Un numero uguale NON garantisce corrispondenza 1:1 nominale: va incrociato nome-modello ↔ nome-migration/seeder/factory.
- Aggiornare `docs/` del modulo ad ogni fix.

---

## ESITO SWARM (14 agenti, completato)

**141 file database creati** (31 factory, 98 seeder, 12 migration) + **24 docs modulo** create/aggiornate. 0 errori `php -l`. `phpstan.neon` intatto.

### Migration reali create (gap veri, non pivot/Sushi)
- Geo: `states`, `counties`, `geonames_cap`
- Employee: `departments`, `positions`, `time_records`, `time_entries`
- Tenant: `tenant_settings`, `tenant_subscriptions`, `database_configs`
- Comment: `comment_notification_opt_outs` (era un bug: seeder senza tabella)
- Job: `task_comments`

### Skip motivati ricorrenti (Ponytail-corretti)
- **Pivot puri**: InvoiceWorkOrderLink (Billing), RatingMorph (Rating), timber_processing_step_processable, inventory_movement_product_units, Comment pivot.
- **Sushi/read-model/JSON** (nessuna tabella): Geo (Comune/Province/Region/Locality/ComuneJson), Cms (Page/Section/Attachment/Conf/Module), Lang (TranslationFile), Inventory (InventoryOverviewRow), Tenant (Domain/TenantDomain/TestSushiModel).
- **Tabella condivisa (owner altrove)**: Employee User/Admin/Employee → `users` (Auth); Tenant `Tenant` → `tenants` (User); JobsWaiting → `jobs`.

### Follow-up segnalati (fuori scope, preesistenti)
- ⚠️ Violazione dipendenza Geo→UI: `Modules/UI/app/Filament/Forms/Components/LocationSelector.php:11` importa `Modules\Geo\Models\Comune`.
- 🧹 File backup spuri in Lang: `Post.php.fixed`, `post.php.fixed`.
- 📌 TimberBilling: 5 modelli ora con `HasFactory` + `newFactory()` (patch necessaria per seeder funzionanti).

## Nota coordinamento — timber_processing_step_processable (2026-07-27)

Deciso definitivamente: **plain `Migration`** (non `XotBaseMigration`), hardcoded
table name OK — è l'eccezione pivot-senza-model di
`docs/wiki/rules/009-one-migration-per-model.md`. Nessun model Eloquent dedicato
esiste (`TimberProcessingStep::inputLots()/inputDerivedMaterials()` usano
`morphedByMany()` con la tabella raw, senza pivot model). Non ri-convertire a
XotBaseMigration: `getModelClass()` non ha nulla da risolvere per questa tabella.

## Aggiornamento — timber_processing_step_processable, soluzione finale (2026-07-27)

Superata la nota precedente. Soluzione adottata (file:
`Modules/TimberBilling/database/migrations/2026_07_03_100008_create_timber_processing_step_processable_table.php`):
`XotBaseMigration` con `$model_class = TimberProcessingStep::class` (il model
proprietario, solo per soddisfare il costruttore) + nome tabella esplicito passato
come secondo argomento a `tableCreate($closure, 'timber_processing_step_processable')`.
Evita sia `extends Migration` nudo sia un model-pivot fittizio mai usato altrove.
Pattern riutilizzabile per altre pivot morph senza model dedicato.

## Deduplicazione TimberBilling (2026-07-27)

Due agenti hanno splittato in parallelo lo stesso `create_timber_tables.php` legacy
in file 1-modello-1-migration, con timestamp diversi (`07_03_1000xx` vs
`07_24_0008xx`). Risolto mantenendo un solo file per modello: tenuto `07_03_1000xx`
per agents/purchase_records/lots/processing_steps/drying_cycles/humidity_checks/
packaged_units/movements/label_prints/processing_step_processable; tenuto invece
`07_24_000807_create_timber_derived_materials_table.php` (07_24) per
`timber_derived_materials` perché include le colonne di tracciabilità
(`root_lot_id`, `traceable_code`, `stage_type_code`, `stage_history`) già live in
DB, assenti nella versione `07_03_100007` (rimossa). Righe orfane in `migrations`
ripulite di conseguenza. `php artisan migrate` → 0 pending, verificato pulito dopo
il merge.

## Correzione — pivot SEMPRE con model dedicato (2026-07-27, seconda revisione)

Le mie due note precedenti su `timber_processing_step_processable` (prima "plain
Migration senza model", poi "XotBaseMigration con model_class del proprietario +
tableCreate(closure, tableName)") sono **entrambe superate**. Soluzione finale e
corretta, applicata da altro agente: model dedicato reale
`TimberProcessingStepProcessable extends XotBaseModel` (con relazioni
`processingStep()`/`processable()`, factory propria) + migration XotBaseMigration
standard con `$model_class = TimberProcessingStepProcessable::class`. Nessuna pivot
è mai "senza model" in questo codebase — vedi anche `RatingMorph`, `Taggable`.
Skill canonica: `xotbase-migration-religion`.

## Incidente — Comment module rimosso, Blog\Article dipendeva ancora (2026-07-27)

`composer go` (`composer update -W` + `artisan package:discover`) ha fatto emergere
che `Modules/Comment` (490 file) era stato rimosso dal disco (non committato, da un
altro agente) mentre `Modules/Blog/app/Models/Article.php` importava ancora
`Modules\Comment\Models\{Comment,CommentNotificationSubscription,Concerns\HasComments,Contracts\SupportsCommentNotifications}`
→ fatal error su **qualsiasi** comando `php artisan` (bootstrap rotto per tutti gli
agenti). Utente confermato: il modulo Comment non serve in questo progetto.

Fix applicato:
- Rimossi import/trait/interfaccia/metodi Comment-dipendenti da `Article.php`
  (`use HasComments`, `implements SupportsCommentNotifications`, i due
  `@property` doc, i metodi `commentableName()`/`commentUrl()` richiesti
  dall'interfaccia ora rimossa).
- Rimossa entry `"Comment": true` da `modules_statuses.json` (root).
- Rimosso `config/comments.php` orfano (referenziava `Spatie\Comments\*`, pacchetto
  non installato).
- Verificato: nessun altro riferimento reale a `Modules\Comment` nel codebase
  (solo `Modules/Xot/tests/Support/PestFunctionBridge.php`, stub Pest generato,
  namespace-only, innocuo).

Nessun `git checkout`/restore usato (regola git-forward-only): la rimozione delle
490 file di Comment è stata lasciata come stato di fatto (deciso da altro agente,
confermato dall'utente), non ripristinata.
