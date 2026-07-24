---
title: "Import status — Document (replica, media risk)"
type: concept
tags: [document, replica, import, media-conflict, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "Document import status enabled media spat ie conflict risk"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Document

> Replica Wave 3. **Non** merge in Media. **Non** cancellare.

## Stato runtime

- [x] Reshape `app/` + XotBase SP / Resources
- [x] `modules_statuses=true` (JSON 2026-07-24)
- [ ] **Debito critico:** `Modules\Document\Models\Media` usa ancora `protected $table = 'media'` → conflitto Spatie/Media
- [ ] Rename tabella dominio (`document_media`) o bridge esplicito **prima** di migrate/write massivi

## Conflitto `media`

| Owner | Tabella | Schema |
|---|---|---|
| Spatie / Media module | `media` (già in DB) | `model_type`, `file_name`, … |
| Document (SRC) | vuole `media` | `mediable_*`, `kind`, `path`, … |

Enable JSON ≠ schema safe. Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Test migrati dal sorgente

Da `gestionale_commesse/tests/Feature/Documents/` → `tests/Feature/MediaActionsTest.php`:

- `AttachInterventionDocumentTest.php` → "attaches a stored document to an intervention"
- `AttachInterventionPhotosTest.php` → "attaches multiple uploaded photos to an intervention"
- `MediaMetadataTest.php` → "fills mime type and size for stored media files"

Adattamenti: `Modules\Documents\Application\Actions` → `Modules\Document\Actions`; `Modules\Interventions\Infrastructure\Models\Intervention` → `Modules\Intervention\Models\Intervention` (usa `Intervention::factory()->create()` al posto degli insert manuali `DB::table('customers'/'work_orders')` del sorgente).

**Non portato:** `UploadInterventionPhotosPageTest.php` — la pagina chiama `Gate::authorize('update', $intervention)` in `mount()`; l'autorizzazione dipende da ruoli Shield + `App\Support\AccountFeatures` (shim che ritorna sempre il default), rendendo il test fragile senza una fixture di autenticazione dedicata. La action sottostante (`AttachPhotosToInterventionAction`) resta coperta.

**Esito esecuzione (2026-07-24):** i tre test sopra falliscono nell'ambiente di test corrente con `SQLSTATE[HY000]: no such table` — le migrazioni non risultano applicate al DB sqlite `:memory:` usato da Pest (debito tecnico pre-esistente, vedi `laravel/docs/gestionale-commesse-debito-tecnico.md`), non un difetto di adattamento del test.
