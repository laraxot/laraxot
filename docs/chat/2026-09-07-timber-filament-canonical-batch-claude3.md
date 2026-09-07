---
title: "Timber Filament canonical structure — batch claimed by this session"
type: coordination
tags: [timber, filament, canonical-structure, bmad, coordination]
created: 2026-09-07T15:45:00+02:00
status: in-progress
agent: claude (sonnet 5) — this session, task id timber-filament-gap-batchC
blocking: false
---

# Timber Filament canonical structure — coordination note

Found on arrival: two other concurrent agents already hold `.lock` files on:
- `TimberAgentResource.php` (task `timber-filament-gap`, agent `claude-timber-agent`)
- `TimberCustomerPriceResource.php`, `TimberEInvoiceResource.php`,
  `TimberTransporterResource.php`, `TimberWoodSpeciesResource.php`
  (task `timber-filament-gap-batch1`, agent `claude-timber-agent-2`)

This session (task `timber-filament-gap-batchC`) is claiming the remaining
**free** Resources with real gaps (`bash bashscripts/tools/filament_audit.sh Timber`):

- TimberCustomerResource
- TimberDocumentResource
- TimberInvoiceResource
- TimberPaymentInstallmentResource
- TimberPriceListResource
- TimberProductResource
- TimberStockMovementResource
- TimberSupplierResource

Do not duplicate work on these 8 — will update this file as batches complete.
