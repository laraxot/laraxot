---
title: "Debito D1 — conflitto Document Media vs Spatie media"
type: how-to
tags: [technical-debt, document, media, spatie, migration, gestionale]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt document media conflict spatie rename document_media bridge playbook"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-vehicle-document-media.md"
  - "./debt-legacy-migrations-triage.md"
---

# Debito D1 — conflitto Document `Media` vs Spatie `media`

## Perché esiste (business)

Il gestionale SRC ha un allegato di dominio (`kind` = document|photo|signature|sketch, path su disk, morph `mediable`).  
Laraxot/Media/Spatie usa già la tabella **`media`** per la Media Library (`model_type` / `file_name` / conversions).

Due mondi, **stesso nome tabella**. Non è un “doppione da cancellare”: sono prodotti diversi.

## Sintomo / evidenza

| Fatto | Valore |
|---|---|
| Spatie `media` in DB | Presente; colonne `model_type`, `file_name`, `collection_name`, … |
| `document_media` | Assente |
| Model `Modules\Document\Models\Media` | `protected $table = 'media'` |
| Migrazione Document `2026_07_23_180010_create_media_table` | **Pending** |
| `modules_statuses.Document` | **true** (enabled) — landmine attiva |
| Comportamento se si migra Document ora | `XotBaseMigration::tableCreate` **salta** create (table exists) → `tableUpdate` può **alterare la tabella Spatie** aggiungendo colonne dominio / timestamps errati |

## Impatto

- Eloquent Document legge/scrive colonne che **non esistono** su Spatie → query error o dati spazzatura.
- VehicleDocument punta a `Document\Models\Media` → [D2](./debt-vehicle-document-media.md).
- Signature/sketch SRC potevano vivere su Document; oggi Signature ha tabelle proprie (mitigazione parziale).

## Opzioni di risoluzione (sceglierne **una**)

### Opzione A — Rename tabella dominio (consigliata, KISS)

**Idea:** Document possiede `document_media` (o `document_medias`). Spatie resta `media`.

#### Passi (ordine vincolante)

1. **Story BMAD** dedicata + Issue comment; lock su:
   - `Modules/Document/app/Models/Media.php`
   - `Modules/Document/database/migrations/2026_07_23_180010_create_media_table.php`
   - eventuali factory/seeder/resource
2. **Decisione nome tabella** (canon suggerito: `document_media`).
3. **Edit migrazione** (ancora Pending):
   - Impostare esplicitamente `$table_name = 'document_media'` **oppure** rinominare file in `create_document_media_table` + `model_class` allineato alle regole XotBase (nome file → modello).
   - Religion: una create_* per model; bump timestamp se serve ordine.
   - **Non** usare `timestamps()` in `tableCreate`; solo `updateTimestamps` in `tableUpdate`.
4. **Model**: `$table = 'document_media'`.
5. **Grep** `->constrained('media')` / `protected $table = 'media'` nel modulo Document e consumer (Vehicle, WorkOrder export, …).
6. **Migrate path**:
   ```bash
   cd laravel
   php artisan migrate --path=Modules/Document/database/migrations --no-interaction
   ```
7. **Verifica**:
   ```bash
   php artisan tinker --execute="echo implode(',', Schema::getColumnListing('document_media'));"
   # Spatie media intatto
   php artisan tinker --execute="echo Schema::hasColumn('media','file_name') ? 'spatie-ok' : 'broken';"
   ```
8. **PHPStan** scoped Document + Vehicle.
9. Pest: factory Media Document crea riga su `document_media`.
10. Aggiornare `Modules/Document/docs/import-status.md` + inventario [gestionale-technical-debt.md](./gestionale-technical-debt.md).

#### Pro / contro

| Pro | Contro |
|---|---|
| Separazione chiara | Rename file migrazione Pending = ok; se qualcuno avesse già Ran con nome sbagliato = recovery |
| Nessuna perdita Spatie | Touch di tutti i consumer del model |
| Allineato one-migration-per-model | — |

### Opzione B — Bridge: Document usa Spatie (ridisegno)

**Idea:** Abbandonare lo schema SRC; `Document\Models\Media` diventa thin wrapper / Action su Spatie `Media`.

#### Passi

1. Gap analysis: mappare `kind`, `path`, `meta` → `collection_name` + `custom_properties`.
2. Migrare codice Actions/Resources a Spatie API (`addMedia`, collections).
3. **Eliminare** o neutralizzare migrazione Document `create_media_table` (non deve toccare Spatie).
4. Aggiornare VehicleDocument a Spatie Media (o morph).
5. Test regressione upload/firma.

#### Pro / contro

| Pro | Contro |
|---|---|
| Una sola tabella media | Perdita fedeltà SRC; riscrittura ampia |
| Meno tabelle | Non è “porting”: è redesign |

Usare B solo con **OK prodotto esplicito**.

### Opzione C — Disable Document finché A/B non è fatta (contenimento)

**Idea:** `modules_statuses.Document = false` fino a rename.

#### Passi

1. Conferma utente (enable era già true).
2. `Document: false` in `modules_statuses.json` (chiave **presente**).
3. Non path-migrare Document.
4. VehicleDocument resta rotto a livello semantico finché D2 non è chiuso (o disable anche feature documenti mezzo in UI).

C **non chiude** il debito: riduce blast radius.

## Checklist pre-implementazione (agente)

- [ ] Opzione A/B/C deliberata e scritta in Issue #14 / #7
- [ ] Backup mentale: `SHOW CREATE TABLE media`
- [ ] Conteggio righe Spatie `media` (se >0, Opzione A obbligatoria vs drop)
- [ ] Lock file multi-agente
- [ ] Nessun `migrate --force`
- [ ] Grep consumer Document Media

## Criteri di accettazione (DoD D1)

- [ ] Esiste tabella dominio Document **distinta** da Spatie **oppure** Document non usa più `$table='media'`
- [ ] Migrazione Document non è Pending in stato pericoloso (Ran correttamente su tabella nuova, o rimossa/neutralizzata)
- [ ] Spatie `media` colonne invariate (file_name presente, niente `kind` obbligatorio Spatie-breaking)
- [ ] PHPStan Document 0 errori reali
- [ ] D2 sbloccato o story collegata

## Riferimenti codice (path)

- `Modules/Document/app/Models/Media.php`
- `Modules/Document/database/migrations/2026_07_23_180010_create_media_table.php`
- `database/migrations/2026_07_16_155833_create_media_table.php` (Spatie root, già idempotente)
- `Modules/Media/database/migrations/*medias*`

## Non fare

- Droppare `media` Spatie.
- Lasciare Document enabled + migrate path “tanto tableExists”.
- Rinominare solo il model senza cambiare `$table`.
