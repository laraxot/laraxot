---
title: "corpi metodo duplicati — Xot"
type: analysis
module: Xot
tags: [dry, duplication, census, refactoring, xot]
created: 2026-07-22
updated: 2026-07-22
qmd: "duplicate method bodies Xot identical hash DRY"

related:
  - ../../../../../../docs/wiki/duplicate-method-bodies-census.md
  - ./method-name-homonyms.md
---

# Corpi metodo duplicati — Xot

> **129** gruppi con corpo identico coinvolgono Xot (su 790 totali progetto).
> Omonimo con corpo **diverso** = configurazione, e' nel [censimento omonimi](./method-name-homonyms.md); qui solo corpi **identici**.

## Riepilogo (solo Xot)

| Categoria | Gruppi | ~Righe duplicate |
|-----------|--------|------------------|
| `A_config_identical` | 16 | 828 |
| `B_business_duplicate` | 78 | 982 |
| `C_cross_name` | 7 | 156 |
| `M_database_layer` | 1 | 3 |
| `S_trivial_stub` | 27 | 20385 |

## Dettaglio

### B — Business logic con corpo identico (consolidare: 1 owner)

#### `getModels` — 2 classi · 52 righe · ~52 righe duplicate

- `Xot` · `ModuleAction::getModels` · `Modules/Xot/app/Actions/ModuleAction.php:52`
- `Xot` · `ModuleService::getModels` · `Modules/Xot/app/Services/ModuleService.php:68`

#### `exe` — 2 classi · 42 righe · ~42 righe duplicate

- `Xot` · `ArtisanAction::exe` · `Modules/Xot/app/Actions/ArtisanAction.php:217`
- `Xot` · `ArtisanService::exe` · `Modules/Xot/app/Services/ArtisanService.php:264`

#### `errorShow` — 2 classi · 38 righe · ~38 righe duplicate

- `Xot` · `ArtisanAction::errorShow` · `Modules/Xot/app/Actions/ArtisanAction.php:114`
- `Xot` · `ArtisanService::errorShow` · `Modules/Xot/app/Services/ArtisanService.php:133`

#### `showRouteList` — 2 classi · 37 righe · ~37 righe duplicate

- `Xot` · `ArtisanAction::showRouteList` · `Modules/Xot/app/Actions/ArtisanAction.php:154`
- `Xot` · `ArtisanService::showRouteList` · `Modules/Xot/app/Services/ArtisanService.php:173`

#### `tryOpenAiCompression` — 2 classi · 30 righe · ~30 righe duplicate

- `Xot` · `ContextCompressorAction::tryOpenAiCompression` · `Modules/Xot/app/Actions/ContextCompressor.php:41`
- `Xot` · `ContextCompressorAction::tryOpenAiCompression` · `Modules/Xot/app/Actions/ContextCompressorAction.php:41`
- `Xot` · `ContextCompressor::tryOpenAiCompression` · `Modules/Xot/app/Services/ContextCompressor.php:43`

#### `diff_assoc_recursive` — 2 classi · 25 righe · ~25 righe duplicate

- `Xot` · `ArrayAction::diff_assoc_recursive` · `Modules/Xot/app/Actions/ArrayAction.php:34`
- `Xot` · `ArrayService::diff_assoc_recursive` · `Modules/Xot/app/Services/ArrayService.php:30`
- `Xot` · `ArrayAction::diff_assoc_recursive` · `Modules/Xot/docs/root-uppercase-folders/services/array-service.php:34`

#### `config` — 1 classe · 12 righe (Support legacy rimosso 2026-07-22)

- `Xot` · `PanelModuleAdapter::config` · `Modules/Xot/app/Adapters/Filament/PanelModuleAdapter.php:44`
- ~~`PanelModuleResolver` / `PanelModuleSupport` in `app/Support/`~~ — cancellati; vedi [no-app-support-queueable-actions](no-app-support-queueable-actions.md)

#### `extractiveFallback` — 2 classi · 24 righe · ~24 righe duplicate

- `Xot` · `ContextCompressorAction::extractiveFallback` · `Modules/Xot/app/Actions/ContextCompressor.php:97`
- `Xot` · `ContextCompressorAction::extractiveFallback` · `Modules/Xot/app/Actions/ContextCompressorAction.php:97`
- `Xot` · `ContextCompressor::extractiveFallback` · `Modules/Xot/app/Services/ContextCompressor.php:99`

#### `getAct` — 2 classi · 24 righe · ~24 righe duplicate

- `Xot` · `RouteDynAction::getAct` · `Modules/Xot/app/Actions/RouteDynAction.php:114`
- `Xot` · `RouteDynService::getAct` · `Modules/Xot/app/Services/RouteDynService.php:115`

#### `trans` — 2 classi · 23 righe · ~23 righe duplicate

- `Xot` · `XotBaseResource::trans` · `Modules/Xot/app/Filament/Resources/XotBaseResource.php:46`
- `Xot` · `trait:TransTrait::trans` · `Modules/Xot/app/Filament/Traits/TransTrait.php:26`

_… +68 gruppi in questa categoria (vedi JSON)_

### C — Corpo identico, nomi diversi (copy-paste con rename)

#### `getHeaderActions` / `getTableActions` — 9 classi · 6 righe · ~48 righe duplicate

- `Xot` · `ListLogs::getTableActions` · `Modules/Xot/app/Filament/Resources/LogResource/Pages/ListLogs.php:74`
- `Incentivi` · `EditEmployee::getHeaderActions` · `Modules/Incentivi/app/Filament/Resources/EmployeeResource/Pages/EditEmployee.php:16`
- `IndennitaCondizioniLavoro` · `EditUpload::getHeaderActions` · `Modules/IndennitaCondizioniLavoro/app/Filament/Resources/UploadResource/Pages/EditUpload.php:16`
- `Job` · `ListJobs::getTableActions` · `Modules/Job/app/Filament/Resources/JobResource/Pages/ListJobs.php:70`
- `Job` · `JobsTable::getTableActions` · `Modules/Job/app/Filament/Resources/JobResource/Tables/JobsTable.php:43`
- `User` · `EditSocialProvider::getHeaderActions` · `Modules/User/app/Filament/Clusters/Socialite/Resources/SocialProviderResource/Pages/EditSocialProvider.php:16`
- … +4 occorrenze

#### `execute` / `toPdf` — 2 classi · 34 righe · ~34 righe duplicate

- `Xot` · `HtmlToPdfAction::execute` · `Modules/Xot/app/Actions/Html/HtmlToPdfAction.php:26`
- `Xot` · `HtmlAction::toPdf` · `Modules/Xot/app/Actions/HtmlAction.php:20`

#### `execute` / `rangeIntersect` — 4 classi · 10 righe · ~30 righe duplicate

- `Xot` · `ArrayAction::rangeIntersect` · `Modules/Xot/app/Actions/ArrayAction.php:16`
- `Xot` · `RangeIntersectAction::execute` · `Modules/Xot/app/Actions/Utilities/RangeIntersectAction.php:18`
- `Xot` · `ArrayService::rangeIntersect` · `Modules/Xot/app/Services/ArrayService.php:12`
- `Xot` · `ArrayAction::rangeIntersect` · `Modules/Xot/docs/root-uppercase-folders/services/array-service.php:16`
- `Sigma` · `trait:FunctionExtra::rangeIntersect` · `Modules/Sigma/app/Models/Traits/Extras/FunctionExtra.php:40`

#### `execute` / `filamentPalette` — 2 classi · 10 righe · ~10 righe duplicate

- `Xot` · `GetPaFilamentPaletteAction::execute` · `Modules/Xot/app/Actions/Design/GetPaFilamentPaletteAction.php:28`
- `Xot` · `PaDesignColorsAction::filamentPalette` · `Modules/Xot/app/Actions/PaDesignColorsAction.php:47`
- ~~`PaDesignColors` in `app/Support/`~~ — cancellato 2026-07-22

#### `getLabel` / `getNavigationGroup` / `getNavigationIcon` / `getNavigationLabel` / `getPluralLabel` / `getTitle` — 6 classi · 3 righe · ~15 righe duplicate

- `Xot` · `XotBasePage::getNavigationGroup` · `Modules/Xot/app/Filament/Pages/XotBasePage.php:103`
- `Xot` · `XotBaseEditRecord::getNavigationLabel` · `Modules/Xot/app/Filament/Resources/Pages/XotBaseEditRecord.php:21`
- `Xot` · `XotBaseEditRecord::getNavigationIcon` · `Modules/Xot/app/Filament/Resources/Pages/XotBaseEditRecord.php:26`
- `Xot` · `XotBaseManageRelatedRecords::getNavigationLabel` · `Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php:100`
- `Xot` · `XotBasePage::getNavigationLabel` · `Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php:96`
- `Xot` · `XotBasePage::getTitle` · `Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php:104`
- … +7 occorrenze

#### `checkValidUrl` / `execute` — 3 classi · 3 righe · ~6 righe duplicate

- `Xot` · `IsValidUrlAction::execute` · `Modules/Xot/app/Actions/Url/IsValidUrlAction.php:16`
- `Xot` · `UrlAction::checkValidUrl` · `Modules/Xot/app/Actions/UrlAction.php:36`
- `Xot` · `UrlService::checkValidUrl` · `Modules/Xot/app/Services/UrlService.php:41`

#### `execute` / `fixPath` — 2 classi · 3 righe · ~3 righe duplicate

- `Xot` · `FileAction::fixPath` · `Modules/Xot/app/Actions/File/FileAction.php:753`
- `Xot` · `FixPathAction::execute` · `Modules/Xot/app/Actions/File/FixPathAction.php:17`

### A — Hook framework con corpo identico (override ridondante / candidato default XotBase)

#### `getTableColumns` — 20 classi · 10 righe · ~190 righe duplicate

- `Xot` · `LogsTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/LogResource/Tables/LogsTable.php:16`
- `Xot` · `ModulesTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/ModuleResource/Tables/ModulesTable.php:16`
- `Job` · `ExportsTable::getTableColumns` · `Modules/Job/app/Filament/Resources/ExportResource/Tables/ExportsTable.php:16`
- `Job` · `ImportsTable::getTableColumns` · `Modules/Job/app/Filament/Resources/ImportResource/Tables/ImportsTable.php:18`
- `Job` · `JobBatchsTable::getTableColumns` · `Modules/Job/app/Filament/Resources/JobBatchResource/Tables/JobBatchsTable.php:16`
- `Job` · `JobManagersTable::getTableColumns` · `Modules/Job/app/Filament/Resources/JobManagerResource/Tables/JobManagersTable.php:17`
- … +14 occorrenze

#### `getTableBulkActions` — 31 classi · 5 righe · ~150 righe duplicate

- `Xot` · `ListExtras::getTableBulkActions` · `Modules/Xot/app/Filament/Resources/ExtraResource/Pages/ListExtras.php:59`
- `Xot` · `ListLogs::getTableBulkActions` · `Modules/Xot/app/Filament/Resources/LogResource/Pages/ListLogs.php:86`
- `Xot` · `ListModules::getTableBulkActions` · `Modules/Xot/app/Filament/Resources/ModuleResource/Pages/ListModules.php:87`
- `Incentivi` · `ManageProjectSettlements::getTableBulkActions` · `Modules/Incentivi/app/Filament/Resources/ProjectResource/Pages/ManageProjectSettlements.php:107`
- `IndennitaResponsabilita` · `ListRatingMorphs::getTableBulkActions` · `Modules/IndennitaResponsabilita/app/Filament/Resources/RatingMorphResource/Pages/ListRatingMorphs.php:81`
- `IndennitaResponsabilita` · `RatingMorphsTable::getTableBulkActions` · `Modules/IndennitaResponsabilita/app/Filament/Resources/RatingMorphResource/Tables/RatingMorphsTable.php:73`
- … +25 occorrenze

#### `getFormSchema` — 19 classi · 7 righe · ~126 righe duplicate

- `Xot` · `LogForm::getFormSchema` · `Modules/Xot/app/Filament/Resources/LogResource/Schemas/LogForm.php:17`
- `Xot` · `ModuleForm::getFormSchema` · `Modules/Xot/app/Filament/Resources/ModuleResource/Schemas/ModuleForm.php:17`
- `Job` · `ExportForm::getFormSchema` · `Modules/Job/app/Filament/Resources/ExportResource/Schemas/ExportForm.php:17`
- `Job` · `ImportForm::getFormSchema` · `Modules/Job/app/Filament/Resources/ImportResource/Schemas/ImportForm.php:17`
- `Job` · `JobBatchForm::getFormSchema` · `Modules/Job/app/Filament/Resources/JobBatchResource/Schemas/JobBatchForm.php:17`
- `Job` · `JobManagerForm::getFormSchema` · `Modules/Job/app/Filament/Resources/JobManagerResource/Schemas/JobManagerForm.php:17`
- … +13 occorrenze

#### `casts` — 7 classi · 18 righe · ~108 righe duplicate

- `Xot` · `BaseMorphPivot::casts` · `Modules/Xot/app/Models/BaseMorphPivot.php:55`
- `Xot` · `XotBaseMorphPivot::casts` · `Modules/Xot/app/Models/XotBaseMorphPivot.php:117`
- `Xot` · `XotBasePivot::casts` · `Modules/Xot/app/Models/XotBasePivot.php:96`
- `Job` · `BaseMorphPivot::casts` · `Modules/Job/app/Models/BaseMorphPivot.php:49`
- `Notify` · `BaseMorphPivot::casts` · `Modules/Notify/app/Models/BaseMorphPivot.php:49`
- `Notify` · `BasePivot::casts` · `Modules/Notify/app/Models/BasePivot.php:45`
- … +1 occorrenze

#### `getTableColumns` — 8 classi · 10 righe · ~70 righe duplicate

- `Xot` · `CacheLocksTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/CacheLockResource/Tables/CacheLocksTable.php:12`
- `Xot` · `CachesTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/CacheResource/Tables/CachesTable.php:12`
- `Xot` · `ExtrasTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/ExtraResource/Tables/ExtrasTable.php:12`
- `Xot` · `SessionsTable::getTableColumns` · `Modules/Xot/app/Filament/Resources/SessionResource/Tables/SessionsTable.php:12`
- `Job` · `FailedImportRowsTable::getTableColumns` · `Modules/Job/app/Filament/Resources/FailedImportRowResource/Tables/FailedImportRowsTable.php:15`
- `Job` · `SchedulesTable::getTableColumns` · `Modules/Job/app/Filament/Resources/ScheduleResource/Tables/SchedulesTable.php:22`
- … +2 occorrenze

#### `getTableActions` — 11 classi · 6 righe · ~60 righe duplicate

- `Xot` · `ListExtras::getTableActions` · `Modules/Xot/app/Filament/Resources/ExtraResource/Pages/ListExtras.php:48`
- `Incentivi` · `ListProjects::getTableActions` · `Modules/Incentivi/app/Filament/Resources/ProjectResource/Pages/ListProjects.php:90`
- `Incentivi` · `ProjectsTable::getTableActions` · `Modules/Incentivi/app/Filament/Resources/ProjectResource/Tables/ProjectsTable.php:81`
- `Job` · `ListImports::getTableActions` · `Modules/Job/app/Filament/Resources/ImportResource/Pages/ListImports.php:65`
- `Job` · `ImportsTable::getTableActions` · `Modules/Job/app/Filament/Resources/ImportResource/Tables/ImportsTable.php:27`
- `Performance` · `CriteriMaggiorazioneResource::getTableActions` · `Modules/Performance/app/Filament/Resources/CriteriMaggiorazioneResource.php:121`
- … +5 occorrenze

#### `casts` — 4 classi · 12 righe · ~36 righe duplicate

- `Xot` · `XotBaseUuidModel::casts` · `Modules/Xot/app/Models/XotBaseUuidModel.php:32`
- `Incentivi` · `BasePivot::casts` · `Modules/Incentivi/app/Models/BasePivot.php:42`
- `User` · `BaseMorphPivot::casts` · `Modules/User/app/Models/BaseMorphPivot.php:27`
- `User` · `BasePivot::casts` · `Modules/User/app/Models/BasePivot.php:42`

#### `getInfolistSchema` — 2 classi · 20 righe · ~20 righe duplicate

- `Xot` · `LogResource::getInfolistSchema` · `Modules/Xot/app/Filament/Resources/LogResource.php:37`
- `Xot` · `LogInfolist::getInfolistSchema` · `Modules/Xot/app/Filament/Resources/LogResource/Schemas/LogInfolist.php:17`

#### `casts` — 2 classi · 14 righe · ~14 righe duplicate

- `Xot` · `XotBaseModel::casts` · `Modules/Xot/app/Models/XotBaseModel.php:50`
- `Tenant` · `BaseModel::casts` · `Modules/Tenant/app/Models/BaseModel.php:21`

#### `getFormSchema` — 2 classi · 12 righe · ~12 righe duplicate

- `Xot` · `ExtraResource::getFormSchema` · `Modules/Xot/app/Filament/Resources/ExtraResource.php:25`
- `Xot` · `ExtraForm::getFormSchema` · `Modules/Xot/app/Filament/Resources/ExtraResource/Schemas/ExtraForm.php:17`

_… +6 gruppi in questa categoria (vedi JSON)_

### M — Layer database (migrations/factories/seeders)

#### `run` — 2 classi · 3 righe · ~3 righe duplicate

- `Xot` · `ExtraSeeder::run` · `Modules/Xot/database/seeders/ExtraSeeder.php:12`
- `User` · `ExtraSeeder::run` · `Modules/User/database/seeders/ExtraSeeder.php:12`

### S — Stub banali (≤30 char) — rumore, non debito

27 gruppi — elenco omesso.


## Rigenerazione

```bash
python3 bashscripts/tools/census-duplicate-method-bodies.py
```
