# BMAD Story — PHPStan Comprehensive Fix Campaign

## Understand
- **Problema**: ~1000+ errori PHPStan dopo aggiornamento larastan v3.11.0
- **Causa**: Codice con tipi mancanti/imprecisi nei moduli
- **Regola**: `phpstan.neon` non si modifica; `mixed` -> tipi concreti

## Status Checkpoint (2026-09-06)

### Completed Modules
| Modulo | Errori Iniziali | Errori Attuali | Status |
|--------|-----------------|----------------|--------|
| AI | 1 | 0 | ✅ DONE |
| AsdSoci | 2 | 0 | ✅ DONE |
| Billing/app | ~162 | 4 | 🔄 IN PROGRESS |

### In Progress
- **Billing**: 4 errori rimasti in `InvoiceResource.php` (cast mixed)

### Pending (per errore count)
- **Blog**: 413 errori
- **Activity**: 240 errori
- **AiAssistant**: 195 errori
- **Xot**: Internal error (Larastan reflection)

## Plan
1. ~~Fix Billing (4 errori)~~ - da completare
2. Fix Blog, Activity, AiAssistant
3. Fix Activity module
4. Rieseguire PHPStan full
5. Git sync per ogni modulo

## Implement
### Billing - 4 errori rimasti
File: `Modules/Billing/app/Filament/Resources/InvoiceResource.php`
- Linea 133: `Cannot cast mixed to string`
- Linea 163: `Cannot cast mixed to string`
- Linea 206: `Cannot cast mixed to int`

### Blog - 413 errori
Tipologia stimata: factory, mixed cast, return type

### Activity - 240 errori
Tipologia stimata: mixed cast, method calls

### AiAssistant - 195 errori
Tipologia stimata: mixed cast, return type

## Verify
- [ ] `./vendor/bin/phpstan analyse Modules/Billing` < 1 error
- [ ] `./vendor/bin/phpstan analyse Modules/Blog`
- [ ] `./tools/phpmd.sh`
- [ ] `./vendor/bin/pest`

## Document
- Aggiornare tracking in questa story
- Creare story per modulo quando > 50 errori

## Next Steps (2026-09-07)
1. Completare Billing (InvoiceResource.php)
2. Analizzare Blog
3. Git sync tutti i moduli
