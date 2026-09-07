# BMAD Story 25 — Regola architetturale: XotBaseResource vs XotBaseResourceForm

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Modulo:** `Xot` (regola valida per tutti i moduli)
**Trigger:** `XotBaseResource` NON deve avere `getFormSchema()`; `XotBaseResourceForm` (via `HasXotForm`) SÌ (astratto).

## Regola
- `Modules\Xot\Filament\Resources\XotBaseResource` → NO `getFormSchema()` (è una Resource, non un Form)
- `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm` → `abstract public function getFormSchema(): array` (SÌ, via `HasXotForm`)
- I file che estendono `XotBaseResource` NON devono dichiarare `getFormSchema()` (violazione architetturale)
- I file Schema (`*Form.php` che estendono `XotBaseResourceForm`) DEVONO implementare `getFormSchema()`

## Violazioni trovate (batch)
- `SnapshotResource.php`, `StoredEventResource.php` (Activity)
- `ArticleResource.php`, `BannerResource.php`, `CategoryResource.php` (Blog)
- `BomResource.php` (Bom)
- `Catalog*` (Catalog)
- `MenuResource.php` (Cms) — nota: ha anche `getFormSchemaOld()`
- Altri moduli (da verificare con `grep -rln`)

## Correzione
Per ogni file violazione: rimuovere `getFormSchema()` dalla Resource, mantenere nello Schema (`*Form.php`) tramite `XotBaseResourceForm`.
