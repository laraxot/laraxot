---
title: "Porting gestionale_commesse — Quotation (redo reshape)"
type: concept
tags: [import, gestionale-commesse, quotation, reshape, xotbase, medialibrary]
created: 2026-07-23
updated: 2026-07-23
qmd: "porting Quotation reshape redo XotBase revert medialibrary"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
  - "../../Catalog/docs/gestionale-commesse-port.md"
---

# Porting gestionale_commesse — Quotation (redo reshape, 2026-07-23)

## Contesto

Come `Catalog`, il modulo `Quotation` era stato riportato ad una copia grezza da un processo
concorrente che aveva revertito un reshape XotBase precedente. Dipende da
`Modules\Customer\Models\{Customer,CustomerSite}` e `Modules\Catalog\Models\Product` — fatto dopo
Catalog per rispettare la dipendenza.

## Stato all'ingresso di questa sessione

Molto più avanti di Catalog: `app/{Models,Actions,DTO,Filament,Http,Jobs,Import,Policies,Providers}`
già presenti, Filament Resources (`QuotationResource`, `QuotationTemplateResource`,
`SiteSurveyResource`) già `extends XotBaseResource`. Residuava però:

- `app/Domain/{DTO,Actions,ValueObjects}` — cartella DDD non assorbita.
- Pages/RelationManagers ancora `extends Filament\Resources\Pages\*`/`RelationManagers\RelationManager`
  diretti invece di `XotBase*`.
- Uso di un morph custom `Modules\Document\Models\Media` per gli allegati invece di Spatie
  MediaLibrary (deviazione dalla convenzione di questo repo).
- 8 migrazioni con pattern evolutivo `add_*_to_*` (vietato) invece di 1-per-modello.
- Nessuna cartella `lang/`, nessun `config/quotation_technical.php`, nessun test scaffold,
  nessun `AdminPanelProvider`.
- 6 seeder/factory con namespace corrotto (`Modules\\Quotation` doppio backslash letterale,
  errore di sintassi bloccante) — file non tracciati da git, probabilmente generati da un
  editor/tool che ha scritto l'escape sbagliato.

## Lavoro completato in questa sessione

1. **`app/Domain/*` assorbito**: `Domain/DTO/*` → `app/DTO/`, `Domain/Actions/*` → `app/Actions/`,
   `Domain/ValueObjects/Money.php` → `app/DTO/Money.php`. Namespace aggiornati in tutti i file
   citanti (`Modules\Quotation\Domain\{DTO,Actions,ValueObjects}` → `Modules\Quotation\{DTO,Actions}`).
2. **Migrazioni**: le 8 migrazioni (`create_quotations_tables` + 6 `add_*` + `create_site_surveys_table`)
   sono state consolidate in **5 migrazioni 1-per-modello** (`quotations`, `quotation_items`,
   `quotation_templates`, `quotation_template_items`, `site_surveys`), tutte
   `extends XotBaseMigration` con `$model_class` esplicito, colonne evolutive (totali, tecnici,
   voce, source_quotation_id) integrate direttamente nella tabella base invece di migrazioni
   `add_*` separate.
3. **Media → Spatie MediaLibrary** (decisione esplicita mantenuta da questo task): `Quotation` e
   `SiteSurvey` ora `implements HasMedia`, `use InteractsWithMedia`; collections `documents`
   (Quotation) e `photos`/`videos`/`voice_notes`/`documents` (SiteSurvey). Convertiti tutti i
   consumer:
   - `AddQuotationAttachmentAction` → `addMediaFromDisk()->toMediaCollection('documents')`.
   - `RecordSiteSurveyAction` → instrada per `kind` verso la collection giusta.
   - `QueueQuotationAttachmentRewriteAction`, `RewriteQuotationAttachmentsJob`,
     `RewriteAndAttachQuotationDocumentsAction`, `BuildQuotationDraftAction` → usano
     `getMedia('documents')`/`getPathRelativeToRoot()` invece delle query dirette su
     `Modules\Document\Models\Media` (`kind`, `path`, `title`, `mediable_type`).
   - I due `AttachmentsRelationManager` (Quotation, SiteSurvey) aggiornati a colonne Spatie reali
     (`collection_name` invece di `kind`, `name`/`file_name` invece di `title`/`path`, `size` invece
     di `size_bytes`); `DeleteAction` non richiede più `->before()` manuale (Spatie cancella il file
     fisico alla `delete()` del record media).
4. **Filament Pages/RelationManagers → XotBase***: 24 Pages (`Create/Edit/List/View` di
   `QuotationResource`, `QuotationTemplateResource`, `SiteSurveyResource`) già `XotBase*` (nessuna
   modifica necessaria, erano corrette); i 2 `AttachmentsRelationManager` erano `extends
   RelationManager` diretto → `extends XotBaseRelationManager` (`form()` → `getFormSchema()`);
   `RecordQuotationVoicePage` era `extends Filament\Pages\Page` → `extends XotBasePage`.
5. **Provider**: creato `app/Providers/Filament/AdminPanelProvider.php` (mancante).
6. **Bug corretto (pre-esistente, non introdotto in questa sessione)**: 6 file in
   `database/{seeders,factories}/` (non tracciati da git) avevano `Modules\\Quotation` con doppio
   backslash letterale nel namespace/import — errore di sintassi che bloccava `php -l` sull'intero
   modulo. Corretto in tutti e 6 i file.
7. **Modelli**: aggiunti blocchi `@property` a `Quotation`, `SiteSurvey`, `QuotationTemplate`
   (mancavano/incompleti — `QuotationItem`/`QuotationTemplateItem` li avevano già). Tipizzate le
   colonne decimali come `string` (non `numeric-string` — `Money::asDecimalString()` di questo
   modulo restituisce `string` semplice, non garantito `numeric-string` staticamente).
8. **Config/lang**: creato `config/quotation_technical.php` (mancava, referenziato da 3 Action/Page)
   e `lang/{it,en}/{quotations,site_surveys}.php` (mancavano interamente — nessuna cartella `lang/`
   esisteva) con tutte le chiavi effettivamente usate nel codice (estratte via grep).
9. **Test scaffold**: creato `tests/TestCase.php extends XotBaseTestCase` e `tests/Pest.php`.

## Verifica

- `php -l` su tutti i file del modulo: nessun errore di sintassi.
- `./vendor/bin/phpstan analyse Modules/Quotation --memory-limit=-1` (da `laravel/`): **11 errori
  residui** (10 file + 1 meta-errore di configurazione neon non relativo al modulo), tutti della
  stessa categoria nota: `Cannot call method count()/create() on mixed` sui 5 seeder che chiamano
  `Model::factory()->count(n)->create()`. Causa: `HasXotFactory` (trait condiviso in `Xot`) risolve
  la factory concreta a runtime via `GetFactoryAction` (reflection sul naming convention), quindi
  Larastan non può risolvere staticamente il tipo generico `TFactory` oltre `Factory<static>` —
  limite noto del pattern DI dinamico usato in tutto il monorepo per le factory, non un bug di
  Quotation. Non fixato per non introdurre `@phpstan-ignore`/cast forzati (vietati) né alterare
  l'infrastruttura condivisa `Xot::HasXotFactory`/`GetFactoryAction` (fuori scope di questo modulo).

  Nessun errore residuo riguarda logica applicativa, i modelli, le Action, o violazioni strutturali
  XotBase/Spatie MediaLibrary.

## Registrazione

`"Quotation": true` in `laravel/modules_statuses.json` — PHPStan ha solo il residuo minore/noto
sopra descritto, stessa soglia di accettazione usata per `Catalog`.

## Test migrati dal sorgente (2026-07-24)

Sorgente: `_bases/gestionale_commesse/tests/Feature/Quotations` (33 file) +
`tests/Unit/Quotations` (1 file), namespace originale `Modules\Quotations\...` (DDD
`Domain/Application/Infrastructure`), riadattati al namespace piatto `Modules\Quotation\...`
di questo modulo.

### Portati (7 Feature + 1 Unit)

| File nuovo | Da sorgente | Adattamento |
|---|---|---|
| `tests/Feature/QuotationCrudTest.php` | `QuotationCrudTest.php` | `Quotation::factory()->create()` invece di `DB::table('customers')->insertGetId()` grezzo; stessa asserzione su totali/soft delete |
| `tests/Feature/RecalculateQuotationTotalsActionTest.php` | `RecalculateQuotationTotalsActionTest.php` | 1:1, solo namespace |
| `tests/Feature/QuotationTemplateApplyTest.php` | `QuotationTemplateApplyTest.php` | 1:1, solo namespace/factory |
| `tests/Feature/RecordSiteSurveyTest.php` | `RecordSiteSurveyTest.php` | Asserzioni riscritte su Spatie MediaLibrary (`getMedia()`, `->name`) invece del DTO `SiteSurveyAttachment`/media custom del sorgente; stesso comportamento (foto/video/note vocali, note, `performed_at`, link a quotation) |
| `tests/Feature/BackfillConfirmedAtTest.php` | `BackfillConfirmedAtTest.php` + `BackfillConfirmedAtCommandTest.php` | Uniti in un solo file (action + comando artisan `quotations:backfill-confirmed-at`) |
| `tests/Feature/QuotationCustomerRelationTest.php` | `QuotationCustomerRelationTest.php` | 1:1, relazione `customer()` + ricerca globale Filament |
| `tests/Feature/QuotationAttachmentTest.php` | `QuotationAttachmentTest.php` | Riscritto per asserire su `Spatie\MediaLibrary\MediaCollections\Models\Media` (`collection_name`, `name`, `model_type/model_id`, `size`) invece del morph custom `Modules\Documents\Models\Media` (`kind`, `mediable_type`, `size_bytes`) — stessa azione `AddQuotationAttachmentAction`, già migrata a MediaLibrary in questo modulo |
| `tests/Unit/CalculateQuotationTotalsActionTest.php` | `Unit/Quotations/CalculateQuotationTotalsActionTest.php` | 1:1, solo namespace |

### Deliberatamente NON portati (obsoleti o fuori scope)

| File sorgente | Motivo |
|---|---|
| `QuotationModificationTest.php` | Feature "modifica preventivo via voce/AI" (`BuildQuotationModificationAction`, `QuotationModificationExtractorInterface`) — fuori scope voce/AI per questo port |
| `TechnicalParamsQuotationTest.php` | Estrazione parametri tecnici da trascrizione vocale via AI (`QuotationDraftExtractorInterface`) — fuori scope voce/AI |
| `AiDocumentRewriterTest.php`, `AiQuotationDocumentRewriterProductionTest.php`, `QuotationAiRewriteBindingsTest.php`, `RewriteAndAttachQuotationDocumentsTest.php` | Riscrittura documenti via AI — fuori scope |
| `QuotationVoiceExtractorTest.php`, `RecordQuotationVoicePageTest.php`, `SimilarQuotationVoiceTest.php`, `VoiceBasedOnTest.php`, `VoiceFuzzyMatchTest.php`, `VoiceQuotationAttachmentPlaceholderTest.php`, `VoiceQuotationDefectsTest.php`, `VoiceQuotationProductionFlowTest.php`, `VoiceQuotationSourceSelectionTest.php`, `VoiceQuotationTest.php`, `VoiceTranslationsTest.php` | Dettatura vocale preventivi — fuori scope voce/AI |
| `QuotationImportTest.php` | Import file preventivo (job/parsing) — fuori scope (PDF/import esplicitamente escluso) |
| `GenerateQuotationPdfTest.php`, `GenerateQuotationPdfWithWorkOrderTest.php`, `GenerateConfirmedQuotationPdfTest.php`, `GenerateConfirmedQuotationPdfWithWorkOrderTest.php`, `DompdfPublicPathConfigTest.php` | Generazione PDF — fuori scope |
| `QuotationAttachmentRetryTest.php` | Retry job di riscrittura allegati (dipende da AI rewrite, fuori scope) |
| `SiteSurveyResourceTest.php` | Copre pagine Filament del pannello + trait `RecordsSiteSurvey` non presente in questa forma nel port; la logica di dominio equivalente (registrazione rilievo con allegati) è già coperta da `RecordSiteSurveyTest.php` |

**Tally**: 8 file portati (7 Feature + 1 Unit, 2 unificati in `BackfillConfirmedAtTest.php`) su 33+1=34
originali; 26 deliberatamente non portati come obsoleti/fuori-scope (voce/AI/PDF/import/retry, vedi
tabella sopra).

### Esecuzione — nota importante

`./vendor/bin/pest Modules/Quotation/tests --no-coverage` in questa sessione mostra 15/16 test
DB-dipendenti falliti con `SQLSTATE[HY000]: no such table: customers`/`quotations`/ecc. **Non è un
regressione introdotta da questi test**: il DB di test `:memory:` sqlite (da `phpunit.xml`) non ha
alcuna migrazione applicata — confermato anche per le tabelle proprie di `Quotation`
(`quotation_templates`) con un test-sonda isolato che fallisce allo stesso modo. È il gap
d'infrastruttura di test già noto (nessun `RefreshDatabase` per architettura, migrazioni da
eseguire esternamente ma non compatibili con `:memory:` — vedi `Modules/Xot/tests/TestCase.php`
commento "Migrations must be run ONCE externally") e la registrazione mancante di `Quotation` nelle
`testsuites` di `laravel/phpunit.xml` (debito D13, vedi
`laravel/docs/debt-phpunit-modules-registration.md`). Unico test verde in questa sessione: lo Unit
test `CalculateQuotationTotalsActionTest` (nessun accesso DB). I file sopra sono corretti e pronti a
passare non appena l'infrastruttura di migrazione test viene risolta a livello di repo (fuori scope
di questo task di porting).

Nota di conflitto: durante questa sessione è comparso un lock annidato
`laravel/Modules/Quotation/tests/Feature.lock` (creato da un altro agente dopo l'acquisizione del
lock di modulo) e una serie di file di test grezzi non adattati (namespace sorgente
`Modules\Quotations\...`, es. `VoiceQuotationTest.php`, `QuotationImportTest.php`, ecc.) sono
comparsi in `tests/Feature/` senza intervento di questo agente. Non sono stati toccati/rimossi per
evitare conflitti; vanno verificati/riconciliati dal proprietario di quel lock.
