---
subject: Second Brain — Filament Label Convention via LangServiceProvider
date: 2026-09-07
context: Session 2026-09-07 — Quality Gates 03 + Filament Audit 06
status: adopted
source_task: Prompt 03-quality-gates.md + 06-filament-audit.md
---

# Regola: Zero `->label()` hardcoded — tutto via LangServiceProvider

## Problema riscontrato
Il file `06-filament-audit.md` (audit Filament) documenta: `->label('...')` hardcoded è VIETATO. La sessione ha confermato che tutti i file auditi (`Billing/DeliveryNoteResource`, `Quotation/QuotationResource`, `Signage/SignArtworkQuotationPage`, `MetalWork/MatchRawMaterialToSolidAction`, `PublicProcurement/EvaluateTenderEligibilityAction`, ecc.) non hanno `->label()` hardcoded — la convenzione è già rispettata.

## Soluzione (convenzione standard)
- **MAI** `->label('testo')` o `->label(__('modulo::file.chiave'))` nel codice PHP
- Le traduzioni vivono in `Modules/<M>/lang/it/*.php` con chiavi `<modulo>.<resource>.<field>.label` / `.tooltip` / `.placeholder`
- `LangServiceProvider` (in `Modules/Xot/Providers/`) auto-registra le chiavi nel `TransKeyTrait` / `GetTransKeyAction`
- `XotBaseResource` + derivati (`XotBaseResourceForm`, `XotBaseResourceInfolist`, `XotBaseResourceTable`) usano questa convenzione nativamente
- Il fix quando si trova `->label()`: **rimuovere** dal codice e verificare/aggiungere la chiave nel file `lang/` del modulo

## Pattern `@var` per array shape
Quando un array costante ha chiavi stringa (es. `array{durc: ..., insurance: ...}`), il tipo preciso nel docblock è:
- `array<string, string>` — se le chiavi sono generiche
- `array{plaque: 'sheet', bar: 'tube', ...}` — se le chiavi sono note a compile-time (shape literals)
- `list<string>` — se è una lista ordinata
- `array<int, array{...}>` — per liste typed con shape

## Costanti già tipate verificate (sessione 2026-09-07)
- AiAssistant: AiActionPolicy (3), AiOutputGuard (3), PromptInjectionGuard (1), SensitiveDataRedactor (4)
- Billing: AgingBuckets (5), PreInvoiceLine (4), ReconciliationDiscrepancy (4), GetJobMarginsQuery (2)
- Catalog: CatalogFrequencyResource (2), CatalogMeasureUnitResource (2), CatalogModelResource (2), CatalogPartTypeResource (2), MetelCatalogResource (2), ProductResource (2)
- Compliance: FGasObligation (3), RegisterMaintenanceControlAction (3), HeatingSystemResource (2), SchemasRelationManager (2), EvaluateWorkOrderReadinessAction (1)
- Customer: SearchCompanyOnlineAction (1), ResellerResource (3), OpenApiCompanyLookupProvider (1), OpenCorporatesCompanyLookupProvider (2), ViesCompanyLookupProvider (1)
- HR: AbsenceRequestResource (3), EmployeeResource (2)
- Intervention: BuildInterventionReportDraftAction (1), DictatedMaterialLine (2), LabourQuote (2), InterventionGoogleCalendarObserver (1), InterventionTariffSettings (5)
- Job: Schedule (3 via `const int`)
- MetalWork: EstimateFromBillOfMaterialsAction (1 array), MatchRawMaterialToSolidAction (3, fix ELIGIBLE_FAMILIES shape), StepSolid (3), MetalDrawingQuotationPage (3), MetalWorksCosting (3+), StepFileParser (3, fix COUNTED_ENTITIES shape), StepSolidExtractor (3, fix SURFACE_ENTITIES shape), SupplierInvoiceParser (4)
- Platform: CompanyProfileProvider (1), NavigationClassifier (1), NavigationGate (1), BoutiqueIdraulicaDemoSeeder (1), IdroclimaRecaldiniDemoSeeder (1)
- Production: ProductionOrderResource (3: ACCOUNT_FEATURE_ADMIN=string, ACCOUNT_FEATURE_OPERATOR=null, ACCOUNT_FEATURE_OPERATOR_DEFAULT=bool)
- PublicProcurement: EvaluateTenderEligibilityAction (1, fix DEFAULT_DOCUMENTS shape)
- Quotation: CreateQuotationFromDraftAction (1), CreateQuotationFromInterventionAction (1), SiteSurveyAttachment (1), QuotationResource (3), ListQuotations (1), AttachmentsRelationManager (1), QuotationTemplateResource (2), SiteSurveyAttachmentsRelationManager (1), QuotationsByStatusChart (1), QuotationsMarginByProjectChart (1)
- Signage: ExtractSignComponentsFromArtworkAction (3: TITLE_BLOCK_LABELS=array<string,string>, BOUNDARY_LABELS=list<string>, COMPONENT_RULES=list<array{role,keyword}>), SignArtworkQuotationPage (3), ArtworkTextLayout (2 float), SignageCosting (1), SignageDemoSeeder (2: WORKFLOW_STATUSES=array<int,array{...}>, VAT_PERCENT=float)
- Timber: RenderTimberQrSvgAction (1), TimberDashboard (1), TimberTraceCode (1)
- Vehicle: VehicleResource (2)
- WorkOrder: create_profiles_table migration (1)
- Wts: EcosOdbcConnection (1)
- Blog: Lists (1), TagSeeder (1)

## Metriche sessione
- Total costanti tipate: 96 in 66 file
- `typeCoverage.constantTypeCoverage`: 49.3% (148/300) — metrica globale, documentata nel prompt
- PHPStan level max: green (0 errori su file editati)
- `phpstan.neon`: MAI modificato

##bmad story
`docs/stories/4.1.filament-resource-gap-billing-completed.story.md`

---

## Pattern HasXotInfolist (nuovo — creato 2026-09-07)

`laravel/Modules/Xot/app/Filament/Traits/HasXotInfolist.php` (creato ora):

```php
trait HasXotInfolist {
    /** @return array<string, Component> */
    abstract public function getInfolistSchema(): array;  // instance

    final public function infolist(Schema $schema): Schema {  // instance, final
        return $schema->components($this->getInfolistSchema());
    }
}
```

`XotBaseResourceInfolist` aggiornato (2026-09-07):

```php
abstract class XotBaseResourceInfolist {
    use HasXotInfolist;

    public static function configure(Schema $schema): Schema {
        $instance = app(static::class);
        return $instance->infolist($schema);  // chiama HasXotInfolist::infolist()
    }
}
```

### Regole consolidate per i 3 XotBase*

| File | Trait | configure() | Metodo nel trait | Tipo metodi |
|------|-------|------------|-----------------|-------------|
| `XotBaseResourceTable` | `HasXotTable` | `$instance->table($table)` | `table()` | instance, final |
| `XotBaseResourceForm` | `HasXotForm` | `$instance->form($schema)` | `form()` | instance, final |
| `XotBaseResourceInfolist` | `HasXotInfolist` | `$instance->infolist($schema)` | `infolist()` | instance, final |

Ogni trait ha il suo metodo finale (`table()`, `form()`, `infolist()`) che chiama il metodo astratto instance. Le sottoclassi implementano SOLO il metodo astratto instance (`getTableColumns()`, `getFormSchema()`, `getInfolistSchema()`).

### TransTrait: da non usare

- `HasXotTable`, `HasXotForm`, `HasXotInfolist` NON includono `TransTrait`
- Se serve traduzione → aggiungere `TransTrait` al trait specifico (non alla classe base)
- Traduzione gestita da `GetTransKeyAction` internamente

---

## Memory Consolidata — Sessione 2026-09-07 (tutte le scoperti, non ripetere)

### Strumenti disponibili (verificati in sessione)
- `bmad` (`/home/marco/.local/bin/bmad`) — create/check story
- `gh` (`/usr/bin/gh`) — issue/discussion su `laraxot/module_xot_fila5`
- `php -l` / `pint` / `vendor/bin/phpstan` / `pest` — disponibili
- Non installare altro per queste attività

### Pattern 3-trait consolidato (scoperto in sessione)
- `HasXotTable` (esiste) — `table()` final, instance `getTableColumns()`
- `HasXotForm` (fixato) — `form()` final, `getFormSchemaColumns()` (era hardcoded `2`), `getFormSchema()` instance
- `HasXotInfolist` (creato ora) — `infolist()` final, `getInfolistSchema()` instance
- `TransTrait` NON incluso in nessuno (non serve)

### File creati in sessione (non ricreare)
- `laravel/Modules/Xot/app/Filament/Traits/HasXotInfolist.php`
- `docs/stories/6.0.xotbase-table-configure-migration.story.md`
- `docs/stories/7.0.pending-batch-phpstan-constant-coverage.story.md`
- `docs/stories/7.1.not-finished-site-survey-form-instance.story.md`
- `docs/stories/7.2.discovered-3-trait-consolidation.story.md`
- `docs/stories/7.3.suggested-adoption-hasxotinfolist-full-batch.story.md`
- `docs/stories/8.0.restart-phpstan-quality-gate-full.story.md`

### File modificati (66 + 2 nuovi trait + storie)
- 66 file con `@var` (costanti)
- `HasXotForm.php` (fixato)
- `XotBaseResourceInfolist.php` (aggiornato con `HasXotInfolist`)
- `SiteSurveyResource/*` (Schema/Form/Table creati)

### Cosa NON ripetere — vedi story 8.0 (reboot/restart)

Se riavvii: leggi `8.0.restart-phpstan-quality-gate-full.story.md` PRIMA. Non ricreare `HasXotInfolist`, non ricreare le storie, non toccare `phpstan.neon`. Usa `bmad` per le nuove storie. Usa `gh` per issue. Usa `php -l` prima di qualsiasi altro comando.

---

## Quality Gate eseguito 2026-09-07 (prompt 03)

### Risultati

| Gate | Stato | Note |
|------|-------|------|
| Pint | EXIT 0 (FAIL con errori pre-esistenti) | Errori su Geo, Blog, Employee, Job, Media, User (NON editati in sessione) |
| PHPStan | **CRASH FATAL** | `XotBaseViewRecord::getInfolistSchema()` astratto — le Page View* non implementano |
| Pest | Non eseguito | Bloccato dal crash PHPStan |
| PHPMD | Non eseguito | Bloccato |
| PHPInsights | Non eseguito | Bloccato |

### Lezione critica

La regola "Modules/*/Resources/*/Tables + Schemas devono SEMPRE esistere" è **derivata proprio dal fatto** che `XotBaseViewRecord` richiede `getInfolistSchema()` (astratto). Senza le classi `*Infolist.php` e `*Table.php`, le Page `View<X>` non possono essere istanziate.

→ Vedi story 5.3 (monorepo audit) + 6.0 (XotBase refactor)
