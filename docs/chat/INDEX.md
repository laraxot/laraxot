# docs/chat — Indice handoff agenti

| Slug | Ambito | Stato |
|------|--------|-------|
| [audit-models-migrations-seeders-factories](audit-models-migrations-seeders-factories.md) | Audit modelli↔migration↔seeder↔factory tutti i moduli + fix swarm | completato (14 agenti, esito in fondo al file) |
| [migration-foreignidfor-swarm](migration-foreignidfor-swarm.md) | Conversione foreignId()/foreignUuid()/unsignedBigInteger() → foreignIdFor() in ~145 migration su 34 moduli + allineamento wiki | in corso |

## Swarm attivo (migration-foreignidfor-swarm)
Moduli in lavorazione parallela: User, PublicProcurement, TimberBilling, Intervention, Compliance, Billing, Inventory, HR, EnergyBroker, WorkOrder, WhatsApp, Quotation, Job, Gdpr, Employee, Comment, AiAssistant, AI, Email, Customer, UI, Signature, Production, Notify, Lang, Geo, Catalog, Bom, Blog, Xot, Vehicle, Media, Document, Cms, Activity.
Ogni agente: lock per-file (`bashscripts/lock/{check,lock,unlock}.sh`) prima di editare, converte a `foreignIdFor()` preservando nomi colonna e semantica `constrained()`, poi phpstan/pint/pest sul proprio scope. Dettagli in migration-foreignidfor-swarm.md.
