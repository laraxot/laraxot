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
| [phpstan-modules-zero-20260906](../stories/phpstan-modules-zero-2026-09-06.story.md) | PHPStan zero su tutti i Modules — nuova baseline ~4.740 errori/35 moduli, fix per wave + phpmd/phpinsights/pest + git sync per modulo | in corso 2026-09-06 (agente qwen) |
| [sonnet5-pest-fixes-plus-unclaimed-modules-wave](2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md) | Pest v5 crash-fix (9 test-file bugs) + claim moduli PHPStan non ancora presi: Rating/Thermo/Vehicle/Job/Signature/Lang/Xot/PublicProcurement/Inventory/Tenant/Media/Platform poi Signage/WhatsApp/MetalWork/WorkOrder/User/Quotation/Wts/Timber | in corso 2026-09-06 (claude sonnet 5) |
| [platform-duplicate-assignment-standdown](2026-09-06-platform-duplicate-assignment-standdown.md) | Dispatch duplicato su Platform: lock per-file freschi (~3min) + claim già attivo trovato → nessun file toccato, stand down | standdown 2026-09-06 (claude sonnet 5, sessione separata) |
| [phpstan-metalwork-claim](2026-09-07-phpstan-metalwork-claim.md) | PHPStan Modules/MetalWork: 19→6 errori (6 residui = skip approvato ImportSupplierInvoiceAction/smalot-pdfparser), Filament actions()→recordActions() x4, seeder cast fix | completato 2026-09-07 (claude sonnet 5) |
| [phpstan-full-baseline-and-6-modules-fixed](2026-09-07-phpstan-full-baseline-and-6-modules-fixed.md) | Baseline reale 13417 errori/43 moduli, root cause confermato (phpstan.neon Larastan/Pest disabilitati, owner conferma keep-as-is), bucket strutturale ~3629 method.internalClass non fixabile, 6 moduli chiusi (AsdSoci/Giveback/Seo/Thermo/Document/Costing) | completato 2026-09-07 (claude sonnet 5) |
| [2.8.constant-type-coverage](../stories/2.8.phpstan-constant-type-coverage.story.md) | PHPStan `typeCoverage.constantTypeCoverage` 49%→99% project-wide, 82 occorrenze/19 moduli, tipizzare costanti di classe senza tipo esplicito | in corso 2026-09-07 (opencode) |

## Swarm attivo (migration-foreignidfor-swarm)
Moduli in lavorazione parallela: User, PublicProcurement, TimberBilling, Intervention, Compliance, Billing, Inventory, HR, EnergyBroker, WorkOrder, WhatsApp, Quotation, Job, Gdpr, Employee, Comment, AiAssistant, AI, Email, Customer, UI, Signature, Production, Notify, Lang, Geo, Catalog, Bom, Blog, Xot, Vehicle, Media, Document, Cms, Activity.
Ogni agente: lock per-file (`bashscripts/lock/{check,lock,unlock}.sh`) prima di editare, converte a `foreignIdFor()` preservando nomi colonna e semantica `constrained()`, poi phpstan/pint/pest sul proprio scope. Dettagli in migration-foreignidfor-swarm.md.
