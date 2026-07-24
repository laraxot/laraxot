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
