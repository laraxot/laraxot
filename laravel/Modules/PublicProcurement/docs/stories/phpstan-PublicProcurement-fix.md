---
id: phpstan-PublicProcurement-fix
slug: phpstan-PublicProcurement
scope: [module:PublicProcurement, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06

## Problema
PHPStan errors in Modules/PublicProcurement (57 errors: 15 `staticMethod.notFound` for
`::factory()` method on models, 42 `method.nonObject` in factories/seeders).

## Root Cause
Models use `HasXotFactory` trait but PHPStan doesn't recognize the `factory()` static
method without `@method static` annotation.

## Solution
Added `@method static XxxFactory factory($count = null, $state = [])` PHPDoc annotation
to all 15 models that use factories:
- PublicProcurementAiExtraction
- PublicProcurementContact
- PublicProcurementContract
- PublicProcurementDeadline
- PublicProcurementDocumentRequirement
- PublicProcurementEconomicFrame
- PublicProcurementGuarantee
- PublicProcurementMeasurement
- PublicProcurementOfficialRecord
- PublicProcurementPaymentCertificate
- PublicProcurementPriceItem
- PublicProcurementProgressReport
- PublicProcurementTenderEligibilityCheck
- PublicProcurementTenderOpportunity
- PublicProcurementTenderSource

## Verification
- PHPStan: 0 errors
- PHPMD: no warnings
- Pest: 1 pass (smoke test), 3 failures pre-existing (missing DB tables)

## Git
Commit: fix: phpstan zero errors - PublicProcurement factory methods
