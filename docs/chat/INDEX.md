# docs/chat — Indice handoff agenti

| Slug | Ambito | Stato |
|------|--------|-------|
| [bmad-deep-document-project](bmad-deep-document-project.md) | BMAD deep scan codice → project-documentation + religion | completato 2026-07-27 |
| [second-brain-max-power-always](second-brain-max-power-always.md) | Rule alwaysApply + bootstrap/skill — QMD obbligatorio ogni task | attivo 2026-07-27 |
| [audit-models-migrations-seeders-factories](audit-models-migrations-seeders-factories.md) | Audit modelli↔migration↔seeder↔factory tutti i moduli + fix swarm | completato (14 agenti, esito in fondo al file) |
| [phpstan-modules-zero](phpstan-modules-zero.md) | PHPStan level-max su tutti Modules + coordinamento swarm | completato (0 errori codice, 2026-07-27) |
| [phpstan-modules-zero-swarm](phpstan-modules-zero-swarm.md) | Handoff swarm PHPStan — milestone e pattern | completato |
| [phpstan-zero-structural-blocker](phpstan-zero-structural-blocker.md) | Unico errore residuo: ignore @mixin non matchato in neon | documentato |
| [document-media-table-collision-blocker](document-media-table-collision-blocker.md) | Modules\Document\Media e Modules\Media\Media collidono sulla tabella fisica `media` (connessioni document/media stesso DB) | documentato, non risolto (decisione owner) |
| [migration-foreignidfor-swarm](migration-foreignidfor-swarm.md) | Conversione foreignIdFor() in migration | in corso |

## Swarm attivo (migration-foreignidfor-swarm)
Moduli in lavorazione parallela: User, PublicProcurement, TimberBilling, Intervention, Compliance, Billing, Inventory, HR, EnergyBroker, WorkOrder, WhatsApp, Quotation, Job, Gdpr, Employee, Comment, AiAssistant, AI, Email, Customer, UI, Signature, Production, Notify, Lang, Geo, Catalog, Bom, Blog, Xot, Vehicle, Media, Document, Cms, Activity.
Ogni agente: lock per-file (`bashscripts/lock/{check,lock,unlock}.sh`) prima di editare, converte a `foreignIdFor()` preservando nomi colonna e semantica `constrained()`, poi phpstan/pint/pest sul proprio scope. Dettagli in migration-foreignidfor-swarm.md.
