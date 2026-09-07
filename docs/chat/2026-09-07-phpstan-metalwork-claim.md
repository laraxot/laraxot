---
title: "Claim: PHPStan fix pass on Modules/MetalWork"
type: coordination
tags: [phpstan, metalwork, claim]
created: 2026-09-07T00:00:00Z
status: completed
blocking: false
agent: claude (sonnet 5) — this session
---

# Claim: Modules/MetalWork PHPStan fix

Working through the remaining PHPStan errors in `Modules/MetalWork` (cast.* narrowing,
Filament v4->v5 `actions()` -> `recordActions()` deprecations, seeder cast fixes).

Explicitly **excluded** from this pass (see
`2026-09-07-missing-smalot-pdfparser-dependency.md`):
`app/Actions/ImportSupplierInvoiceAction.php` — depends on uninstalled
`smalot/pdfparser`, needs user approval, not touched.

Updating existing story `Modules/MetalWork/docs/stories/phpstan-MetalWork-fix.md`
rather than creating a new one.

Scope: `laravel/Modules/MetalWork/app/Actions/EstimateFromBillOfMaterialsAction.php`,
`app/Actions/EstimateMetalPartCostAction.php`,
`app/Filament/Pages/MetalDrawingQuotationPage.php`,
`app/Filament/Resources/MetalDrawingResource.php`,
`app/Filament/Resources/MetalPartEstimateResource.php`,
`app/Filament/Resources/MetalRawMaterialResource.php`,
`app/Filament/Resources/MetalWorkCenterResource.php`,
`database/seeders/MetalWorkCenterSeeder.php`.

## Done (2026-09-07)

PHPStan 19 -> 6 errors (the 6 remaining are exactly the pre-approved skip in
`ImportSupplierInvoiceAction.php`). Filament v4->v5 `recordActions()` rename applied
to all 4 resources. PHPMD/PHPInsights/Pest gates run, results and a phpinsights
tooling bug writeup in `Modules/MetalWork/docs/coverage.md`. Story
`phpstan-MetalWork-fix.md` updated to Done. Standing down.
