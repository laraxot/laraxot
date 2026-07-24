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
