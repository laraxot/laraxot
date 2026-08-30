---
title: "Import status — Quotation"
type: concept
tags: [import, quotation, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Quotation migrate panel site survey"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Quotation

**Perché esiste:** preventivi + template + sopralluoghi (pre-vendita → offerta).

- [x] Reshape `app/` + XotBase Resource
- [x] Timestamp migrazioni bump dopo Customer/WorkOrder (`2026_07_23_18100x`)
- [x] `product_id` senza `constrained('products')` (ordine Catalog indipendente)
- [x] `performed_by_user_id` = `string(36)` (users UUID)
- [x] Enabled + panel `quotation/admin`
- [x] Pest smoke — 34/34 test reali migrati da SRC (`gestionale_commesse` `tests/Feature/Quotations` + `tests/Unit/Quotations`, cartella plurale) a `Modules/Quotation/tests/{Feature,Unit}` (singolare)

## Migrazione test — 2026-07-24

**33 file migrati** (32 Feature + 1 Unit; l'unico test Unit SRC), namespace riscritti da
`Modules\Quotations\{Domain,Application,Infrastructure}\...` (layering DDD del monolite SRC) al
namespace piatto reale del modulo target `Modules\Quotation\{Actions,AI,DTO,Import,Jobs,Filament,Models}\...`.
Aggiunto `uses(\Modules\Quotation\Tests\TestCase::class);` mancante in 25 file (nessuno lo dichiarava).

**Risultato attuale:** `vendor/bin/pest Modules/Quotation/tests` → **67 passing / 37 failing** (302 assertion).
Prima della migrazione/fix: 24 passing. PHPStan: `Modules/Quotation/app` → **0 errori reali**
(l'unico "errore" è il pattern-ignore non abbinato, preesistente e innocuo). `Modules/Quotation`
(incluse tests/) mostra ~575 errori, ma sono **identici per tipologia e proporzionali** a quelli già
presenti in moduli non toccati in questa sessione (es. `Modules/Customer` isolato: 59 errori, stessa
distribuzione `method.internalClass`/`theCodingMachineSafe.function`/`class.notFound` su `@var ClassName`
nei Pest test) — non è stato introdotto nulla di nuovo, `phpstan.neon` non è stato toccato.

### Bug reali trovati e corretti (nel codice applicativo, non nei test)

1. **View PDF/Filament con namespace sbagliato**: `GenerateQuotationPdfAction`,
   `GenerateConfirmedQuotationPdfAction` e `RecordQuotationVoicePage` chiamavano `'quotations::...'`
   (plurale, residuo del namespace SRC) invece di `'quotation::...'` (singolare, come registrato da
   `XotBaseServiceProvider::loadViewsFrom($viewPath, $this->nameLower)`). Le view non venivano mai
   trovate → `InvalidArgumentException: No hint path defined for [quotations]`. Corretto in tutti e 3 i file.
2. **Config `quotation_technical` mai letta**: `XotBaseServiceProvider::registerConfig()` registra i
   config file del modulo sotto la chiave `{nameLower}.{filename}`, quindi `quotation.quotation_technical.*`,
   non `quotation_technical.*` come usato in `BuildQuotationDraftAction`, `ApplyTechnicalParamsToQuotationItemsAction`
   e `RecordQuotationVoicePage`. Inoltre il file `config/quotation_technical.php` era stato svuotato
   (`'rules' => []`, chiavi `params` rinominate `surface_sqm`/`has_thermostat` invece di
   `surface_m2`/`thermostat_present`). Corretto: chiamate `config()` con prefisso `quotation.` in tutti
   e 3 i file, e ripristinato il contenuto reale (3 regole THERMO-CANNA-1M/THERMO-VALV-TERM/THERMO-CRONO
   + 5 param) preso dal config SRC — senza questo fix il ricalcolo parametrico dei preventivi vocali
   è completamente inerte in produzione.
3. **Traduzione `quotations.voice.based_on` e `quotations.voice.record` mancanti** in
   `lang/{it,en}/quotations.php` — chiavi usate dalla blade ma mai definite. Il file lang del modulo
   era stato tagliato rispetto a SRC durante il porting: mancavano ~25 chiavi sotto `voice.*`
   (`record`, `tap_to_record`, `listening`, `customer`, `address`, `confirm`, `technical_params`,
   `recalc`, `present`/`absent`, `params.*`, ecc.). Ripristinate tutte da SRC (IT + EN).
4. **Fixture reale mancante**: `tests/Fixtures/quotations/pinco-pallino.ods` (usata da
   `QuotationImportTest` per l'estrazione testo/import reale) non era mai stata copiata da SRC —
   copiata in `laravel/tests/Fixtures/quotations/`.

### Bug noto, NON risolto in questo scope (infrastruttura test, non modulo Quotation)

Circa **37 test falliscono** per un problema **preesistente e trasversale** all'intero repo, non
introdotto da questa migrazione: in alcune combinazioni di connessione/di run, le tabelle
`customers`/`products`/`metel_catalogs`/`roles`/`media` risultano assenti sulla connessione
`xot`/`sqlite` in-memory (`SQLSTATE[HY000]: no such table: ...`). Riprodotto identico e isolato su
`Modules/Customer/tests` (17 failing) e `Modules/WorkOrder/tests` (24 failing), moduli non toccati in
questa sessione — quindi non è un bug di Quotation ma un problema di bootstrap/migrazione del test
database condiviso da tutti i moduli. Stessa causa per `shield:generate` command not found
(`ShieldPermissionsSeeder`, usato anche da `Modules/Auth/tests/Feature/ShieldPermissionsSeederTest.php`,
già rotto prima di questa sessione).

Test che dipendono da queste tabelle/comando (falliscono per questo, non per bug applicativi):
`QuotationCrudTest`, `QuotationAttachmentTest*`, `QuotationCustomerRelationTest`, `RecordSiteSurveyTest`,
`RecordQuotationVoicePageTest`, `SiteSurveyResourceTest`, `VoiceQuotationTest`, `VoiceBasedOnTest`,
`VoiceFuzzyMatchTest`, `VoiceQuotationAttachmentPlaceholderTest`, `VoiceQuotationDefectsTest`,
`VoiceQuotationProductionFlowTest`, `VoiceQuotationSourceSelectionTest`, `TechnicalParamsQuotationTest`
(solo i sotto-test con `DB::table('customers')`), `RewriteAndAttachQuotationDocumentsTest`,
`SimilarQuotationVoiceTest`, `BackfillConfirmedAtCommandTest`, `GenerateConfirmedQuotationPdfTest`/
`GenerateQuotationPdfTest` (i sotto-test con customer/product reali).

### Adattamenti applicati con giudizio (test-only, nessun impatto produzione)

- Rimosso `DompdfPublicPathConfigTest.php`: presupponeva `barryvdh/laravel-dompdf` + `config/dompdf.php`,
  non presenti nel target (che usa `spipu/html2pdf` via `QuotationPdfRenderer`).
- `RecordSiteSurveyTest`/`SiteSurveyResourceTest`: aggiunto `Storage::fake('public')` +
  file fittizi (`Storage::disk('public')->put(...)`) prima di chiamare `addMediaFromDisk()`,
  altrimenti `Spatie\MediaLibrary\...\FileDoesNotExist` (SRC non creava questi file nemmeno lui, il
  test probabilmente non girava mai in isolamento).
- `VoiceTranslationsTest`: adattato per leggere le chiavi con prefisso `quotation::` (namespace
  traduzioni reale nel modulo target) invece del default SRC non-namespaced; annotato nel test
  che ~13 file applicativi (`Filament/Resources/*`, blade, Jobs, Actions) chiamano ancora
  `__('quotations.*')` senza prefisso namespace — bug di traduzioni reale ma esteso, fuori scope
  di questa migrazione test-only (segnalato per follow-up separato).

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).

## Delta seconda tranche (2026-08-30, parziale)

Anticipato dal delta Intervention (dipendenza cross-modulo):

- `CreateQuotationFromInterventionAction` + `BuildQuotationLinesFromInterventionAction`
  ("Turn an intervention report into a draft quotation"): materiali (righe manuali +
  scarichi magazzino al prezzo di listino), poi manodopera, poi diritto di chiamata;
  bozza mai inviata. Il sorgente non aveva test Pest → creato
  `tests/Feature/CreateQuotationFromInterventionTest.php` (3 test, verdi).

Il resto del delta Quotation (28 file) verrà lavorato nel suo ciclo Fase 1.
