# Handoff — Xot Services → Actions conversion (2026-07-16)

## Summary

Module **Xot** (base module, `laravel/Modules/Xot`) `app/Services/` is now free of live PHP.
The only remaining Service — `app/Services/RouteService.php` — had **reappeared via multi-repo
sync** but had **zero live callers repo-wide**. Its 8 public methods were already converted to
Actions under `app/Actions/Route/` in a prior pass. This handoff finalizes the removal.

## Service converted

| Legacy method (`RouteService::`) | New Action (`app/Actions/Route/`) |
|----------------------------------|-----------------------------------|
| `inAdmin()`            | `IsAdminRouteAction` |
| `urlAct()`             | `BuildActionUrlAction` |
| `getRoutenameN()`      | `BuildNestedRouteNameAction` |
| `urlLang()`            | `BuildLanguageUrlAction` |
| `getAct()`             | `GetCurrentRouteActionNameAction` |
| `getModuleName()`      | `GetCurrentRouteModuleNameAction` |
| `getControllerName()`  | `GetCurrentRouteControllerNameAction` |
| `getView()`            | `GetCurrentRouteViewAction` |

All 8 Actions use `Spatie\QueueableAction\QueueableAction` + single public `execute(...)`.
Call convention: `app(FooAction::class)->execute(...)`.

Note: `inAdmin()` / `params2ContainerItem()` also exist as global helpers in
`Modules/Xot/helpers/Helper.php`; the old `RouteService::inAdmin` call site (formerly
`Modules/Tenant/.../MorphMapConfigResolver`) was already switched to the global helper and
that Tenant path no longer exists.

## Cross-module / other files touched due to Xot Service callers

**NONE this pass.** A full repo-wide search confirmed zero live callers:

```
rg -n "RouteService::" --type php      # (excluding RouteServiceProvider / filament-peek demo) → 0 hits
rg -n "Xot\\Services\\RouteService"    # only self-reference + commented-out imports
```

The prior pass (documented in `Modules/Xot/docs/wiki/concepts/xot-services-support-to-actions.md`)
had already updated the historical callers (Tenant `MorphMapConfigResolver`, Xot
`ArtisanServiceTest`, Notify `PdfTest`). No new cross-module edits were required.

## Files changed this pass

- `laravel/Modules/Xot/app/Services/RouteService.php` — removed (renamed `.bak` locally,
  `.bak` gitignored; committed as deletion, never force-removed history).
- `laravel/Modules/Xot/docs/wiki/concepts/xot-services-support-to-actions.md` — re-verification note.
- `laravel/Modules/Xot/.gitignore` — `tests/AuditCoverage` (pre-existing uncommitted).

## Quality gates

- **phpstan** (`Modules/Xot/app/Actions/Route`, `Actions/Url`): clean — only 2 unmatched
  ignore-pattern warnings from the shared baseline config, no code errors introduced.
- **audit-queueable-action-trait.sh**: all 8 Route Actions carry the trait (not in MISSING_TRAIT list).
  Pre-existing MISSING_TRAIT entries elsewhere (Cast/Debug/String/etc.) are out of scope.
- **check-no-app-support.sh**: Xot no longer flagged for `app/Services`.

## Push

Committed on `laraxot/dev` (module_xot_fila5), rebased over remote `b8ea4a9e "Check & fix styling"`
(which was only a re-format of the same dead file), conflict resolved by keeping the deletion.
Pushed: `b8ea4a9e..4a34f54a`.
