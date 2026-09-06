# BMAD Story — Billing PHPStan Fix

## Understand
- **Modulo**: Billing
- **Errori iniziali**: ~162
- **Errori attuali**: 4 (2026-09-06)
- **Regola**: `mixed` -> tipi concreti; cast sicuri

## Plan
1. Fix 4 errori in `InvoiceResource.php`
2. Verificare con phpstan
3. Git sync

## Implement
### Errori rimasti
File: `Modules/Billing/app/Filament/Resources/InvoiceResource.php`

- **Linea 133**: `Cannot cast mixed to string`
- **Linea 163**: `Cannot cast mixed to string`
- **Linea 206**: `Cannot cast mixed to int`

### Soluzione
Usare narrowing con `is_string()` / `is_numeric()` prima del cast

## Verify
- [ ] `./vendor/bin/phpstan analyse Modules/Billing/app` -> 0 errori
- [ ] `./tools/phpmd.sh Modules/Billing/app`
- [ ] `./vendor/bin/pest --filter="Billing"`

## Status
- [x] WorkOrdersRelationManager.php fixato (cast sicuri)
- [x] GetCashFlowProjectionQuery.php fixato
- [x] GetJobMarginsQuery.php fixato
- [x] GetReceivablesAgingQuery.php fixato
- [ ] InvoiceResource.php - 4 errori da fixare
