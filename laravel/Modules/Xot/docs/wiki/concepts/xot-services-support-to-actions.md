# Xot Services/Support → Actions migration

Deleted dead `app/Services/` and `app/Support/` files that had zero callers or were already replaced by Actions/Adapters.

## Deleted

| Legacy file | Reason |
|-------------|--------|
| `Services/ArtisanService.php` | Untracked duplicate of `Actions/ArtisanAction` |
| `Services/Artisan/CommandRegistry.php` | Untracked, zero callers |
| `Services/Artisan/Handlers/*` (9 files) | Untracked, zero callers, logic in `ArtisanAction::act()` |
| `Services/ArrayService.php` | Untracked, zero callers |
| `Services/ConfigService.php` | Tracked, zero callers |
| `Services/ContextCompressor.php` | Untracked, zero callers |
| `Services/ModuleService.php` | Tracked, zero callers |
| `Services/RouteDynService.php` | Untracked, zero callers |
| `Services/RouteService.php` | Tracked, only caller (`MorphMapConfigResolver`) switched to global `inAdmin()` helper |
| `Services/ThemeService.php` | Tracked, already replaced by `Actions/Theme/*` |
| `Services/UrlService.php` | Tracked, already replaced by `Actions/Url/IsValidUrlAction` |
| `Services/XotService.php` | Tracked, zero callers |
| `Services/Translators/*` (6 files) | Tracked, empty stubs, zero callers |
| `Services/Trend/Adapters/*` (4 files) | Tracked, zero callers |
| `Services/trend.test` / `Trend.test` | Untracked/tracked orphaned fixtures, not real tests |
| `Support/MorphToOneRelationSupport.php` | Tracked, already replaced by `Actions/Model/CreateMorphToOneRelatedModelAction` |
| `Support/PaDesignColors.php` | Tracked, already replaced by `Actions/Design/GetPaFilamentPaletteAction` |
| `Support/PanelModuleResolver.php` | Tracked, already replaced by `Adapters/Filament/PanelModuleAdapter` |
| `Support/PanelModuleSupport.php` | Tracked, dead duplicate |
| `Support/PdfBuilderAdapter.php` | Tracked, already replaced by `Adapters/PdfBuilderAdapter` |

## Updated callers

| File | Change |
|------|--------|
| `Modules/Tenant/app/Services/Config/Resolvers/MorphMapConfigResolver.php` | `RouteService::inAdmin()` → `inAdmin()` |
| `Modules/Xot/tests/Unit/Services/ArtisanServiceTest.php` | `ArtisanService::act` → `ArtisanAction::act` |
| `Modules/Notify/tests/Unit/Actions/NotifyTheme/Attachment/PdfTest.php` | assertion updated for `HtmlAction` import |

## Ri-verifica 2026-07-16

`Services/RouteService.php` era ricomparso (probabile merge/sync multi-repo, vedi [[project_xot_tenant_bootstrap_break]]) nonostante fosse già stato marcato deleted qui. Nessun chiamante nel repo (`rg` su namespace e su ogni metodo pubblico: `inAdmin`, `urlAct`, `getRoutenameN`, `urlLang`, `getAct`, `getModuleName`, `getControllerName`, `getView`); `getView`/`getRoutenameN` risultano già coperti da `Actions/GetViewAction` e `Actions/Route/BuildNestedRouteNameAction`. Il riferimento a `Modules\Tenant\...\MorphMapConfigResolver` in questa tabella non esiste più: il modulo Tenant non ha più `app/Services/Config/Resolvers/`. Rinominato in `.bak` (forward-only, mai `git rm`); `app/Services/` in Xot non contiene più codice PHP vivo.

## Quality gates

- **phpstan**: pre-existing 58 errors, none introduced by this change
- **pest**: pre-existing failures in unrelated tests (Array, Cast, Model, Query, etc.)
- **phpmd**: phar crashes on codebase (pre-existing)
- **phpinsights**: CLI broken (pre-existing)
