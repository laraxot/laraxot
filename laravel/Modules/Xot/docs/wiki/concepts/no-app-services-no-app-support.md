---
title: "Xot senza app/Services e app/Support — Actions only"
type: concept
module: Xot
tags: [actions, services, support, queueable-action, architecture, zen]
created: 2026-07-22
updated: 2026-07-22
qmd: "Xot no app Services Support eliminated QueueableAction actions only migration record"
related:
  - ../../actions-over-services.md
  - ../../../../../docs/wiki/rules/no-services-rule.md
  - ../../../../../docs/wiki/concepts/no-app-support-monorepo-migration.md
  - ../../../../../docs/wiki/duplicate-method-bodies-census.md
---

# Xot: `app/Services` e `app/Support` non esistono più

## Religione

> La business logic vive **solo** in `app/Actions/*` come Spatie **QueueableAction**.
> Un solo owner per concetto: la coppia Action/Service era duplicazione pura
> (censimento corpi: `getModels`, `exe`, `errorShow`, 15 metodi `RouteDyn*`, …).

Canon globale: [no-services-rule](../../../../../docs/wiki/rules/no-services-rule.md) ·
[actions-over-services](../../actions-over-services.md) ·
[no-app-support](../../../../../docs/wiki/concepts/no-app-support-monorepo-migration.md).

## Record eliminazione (2026-07-22)

| Ex classe | Stato | Owner attuale |
|-----------|-------|---------------|
| `ModuleService` | eliminata (0 usi) | `Actions\ModuleAction` |
| `ArtisanService` + `Artisan/*` handlers | eliminata (usava solo il proprio test) | `Actions\ArtisanAction` (`act`, `exe`, `errorShow`, …) |
| `RouteDynService` | eliminata (0 usi) | `Actions\RouteDynAction` |
| `ArrayService` | eliminata (usi solo commentati) | `Actions\ArrayAction`, `Actions\Utilities\RangeIntersectAction` |
| `ConfigService` / `UrlService` | eliminate | `Actions\ConfigAction` / `Actions\UrlAction` |
| `ContextCompressor` (Service + dup PSR-4 in Actions) | eliminate | `Actions\ContextCompressorAction` |
| `HtmlService` | eliminata | `Actions\Html\HtmlToPdfAction` (test Notify aggiornato) |
| `RouteService::inAdmin` | eliminata | helper globale `inAdmin()` (`helpers/Helper.php`); semantica main-panel inlinata in `Tenant\MorphMapConfigResolver` |
| `ThemeService`, `XotService`, `Translators/*`, `Trend/*` | eliminate (0 usi) | — |
| `Support/*` (`MorphToOneRelationSupport`, `PanelModule*`, `PaDesignColors`, `PdfBuilderAdapter`) | eliminate | `Actions\Model\CreateMorphToOneRelatedModelAction`, `Adapters\Filament\PanelModuleAdapter`, `Actions\Design\GetPaFilamentPaletteAction` |

Migrazioni collaterali: `tests/Unit/Actions/ArtisanActionTest.php` sostituisce
`tests/Unit/Services/ArtisanServiceTest.php`.

## Nota semantica inAdmin

`RouteService::inAdmin` controllava `segment(1)==='admin'` (main panel `/admin`);
l'helper `inAdmin()` controlla `segment(2)` (module panel `/{modulo}/admin`).
Dove serviva la semantica main-panel è stata inlinata con commento — non fondere le due.

## Guardia futura

```bash
ls laravel/Modules/*/app/Services laravel/Modules/*/app/Support 2>/dev/null  # deve restare vuoto (Pdnd/Incentivi legacy esclusi)
```
