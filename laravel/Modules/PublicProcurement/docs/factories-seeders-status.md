---
title: "Factory & Seeder status — PublicProcurement"
type: concept
tags: [publicprocurement, factory, seeder, database, ponytail]
created: 2026-07-24
updated: 2026-07-24
qmd: "PublicProcurement factory seeder copertura modelli concreti"
related:
  - "./README.md"
  - "./import-status.md"
---

# Factory & Seeder status — PublicProcurement

Copertura factory/seeder per ogni modello concreto del modulo. Obiettivo Ponytail:
ogni modello concreto ha migration + factory + seeder.

## Meccanismo

- Namespace factory: `Modules\PublicProcurement\Database\Factories\<Model>Factory`
- Namespace seeder: `Modules\PublicProcurement\Database\Seeders\<Model>Seeder`
- Risoluzione factory via `XotBaseModel` → trait `HasXotFactory` → `GetFactoryAction`
  (convenzione `\Models\` → `\Database\Factories\`).
- Seeder idempotenti: guard `if (Model::query()->exists()) return;` + `factory()->count(3)->create()`.
- FK verso moduli esterni nullable (customer, media, invoice, intervention, user,
  compliance_document, work_order) impostate a `null` nelle factory per evitare
  coupling cross-modulo; FK parent obbligatorie (`public_procurement_contract_id`,
  `tender_source_id`, `tender_opportunity_id`) risolte via `Model::factory()`.

## Matrice copertura (15 modelli concreti)

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| PublicProcurementTenderSource | `public_procurement_tender_sources` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementTenderOpportunity | `public_procurement_tender_opportunities` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementContract | `public_procurement_contracts` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementTenderEligibilityCheck | `public_procurement_tender_eligibility_checks` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementContact | `public_procurement_contacts` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementDeadline | `public_procurement_deadlines` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementDocumentRequirement | `public_procurement_document_requirements` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementEconomicFrame | `public_procurement_economic_frames` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementPriceItem | `public_procurement_price_items` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementMeasurement | `public_procurement_measurements` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementProgressReport | `public_procurement_progress_reports` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementPaymentCertificate | `public_procurement_payment_certificates` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementOfficialRecord | `public_procurement_official_records` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementGuarantee | `public_procurement_guarantees` | ✅ | ✅ nuovo | ✅ nuovo |
| PublicProcurementAiExtraction | `public_procurement_ai_extractions` | ✅ | ✅ nuovo | ✅ nuovo |

Aggregatore: `PublicProcurementDatabaseSeeder` chiama i 15 seeder in ordine di
dipendenza (source → opportunity → contract → figli).
`PublicProcurementDemoSeeder` (preesistente) resta la fonte dati demo realistica
guidata dalle Action e non è incluso nell'aggregatore.

## Skip motivati

Nessun modello saltato: tutti i 15 modelli in `app/Models/` sono classi concrete
(`final class ... extends XotBaseModel`), ognuna mappata su una tabella propria.

- Nessun modello astratto / trait / contract / enum in `app/Models/`.
- Nessun modello pivot (le relazioni many-to-many non usano modelli Eloquent dedicati).
- Nessuna Single Table Inheritance né tabella condivisa tra più modelli.

## Migration

Non sono state create nuove migration: tutte le 15 tabelle hanno già la relativa
migration `XotBaseMigration` (`2026_07_24_0001xx_*`, forward-only con
`updateTimestamps()`). Esiste anche una migration legacy raw
(`2026_05_08_090000_create_public_procurement_tables.php`) che crea lo stesso set
di tabelle: non toccata (fuori scope, forward-only).

## Validazione

Tutti i file validati con `php -l` (nessun errore di sintassi). Non eseguiti
composer/phpstan/pest come da vincoli del task.

## Aggiornamento 2026-09-06 — fix PHPStan `Model::factory()` mixed

Il meccanismo generico `HasXotFactory::newFactory()` (via `GetFactoryAction`, che a
runtime funziona correttamente con la convenzione `\Models\` → `\Database\Factories\`)
**non è risolvibile staticamente da Larastan**: `ModelFactoryDynamicStaticMethodReturnTypeExtension`
(attivo via `phpstan/extension-installer`, indipendentemente dal fatto che
`vendor/larastan/larastan/extension.neon` sia commentato in `phpstan.neon`) ispeziona il
return-type di `newFactory()` e richiede che si risolva a **una singola classe Factory
concreta**. Con `@return Factory<static>` (generico) risolve alla classe base
`Factory` oppure fallisce la risoluzione del template `static` a seconda del contesto,
e il fallback per convenzione naming (`App\Models\X` → `Database\Factories\XFactory`)
non riconosce il namespace dei moduli (`Modules\X\Models\Y`) — risultato:
`Model::factory()` tipizzato `*ERROR*`/`mixed`, quindi `Cannot call method count()/create()
on mixed` su ogni seeder.

**Fix applicato** (root cause, nessun `@phpstan-ignore`/baseline/`@var` override): ognuno
dei 15 modelli concreti ora dichiara esplicitamente

```php
protected static function newFactory(): PublicProcurementXFactory
{
    return PublicProcurementXFactory::new();
}
```

con return type **concreto non generico** (non `Factory<static>`). Larastan risolve
questo caso esattamente come lo scaffolding standard Laravel (`make:model -f`) e
tipizza correttamente l'intera catena `factory()->count()->create()`. Rimossi anche i
tag `@phpstan-use HasXotFactory<XFactory>` (invalidi: il trait `HasXotFactory` non è
generico, causavano `generics.notGeneric`) e i tag `@method static XFactory factory(...)`
(ridondanti, ora la verità è nel `newFactory()` concreto). `use HasXotFactory;` resta
per la logica `count()`/`state()` di `factory()`.
