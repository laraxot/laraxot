---
id: factory-seeder-plan
slug: factory-seeder-plan
scope: [project:base_workorder_fila5]
status: Pending
priority: High
created: 2026-09-06
---

## Problema
Timber ha 8 seeder con factory() mancanti. Probabilmente altri moduli.

## Seeders con Errori

| Seeder | Errore |
|--------|--------|
| TimberLotSeeder | factory() not found |
| TimberMovementSeeder | factory() not found |
| TimberPackagedUnitSeeder | factory() not found |
| TimberPaymentInstallmentSeeder | factory() not found |
| TimberPriceListItemSeeder | factory() not found |
| TimberPriceListSeeder | factory() not found |
| TimberPriceListVersionSeeder | factory() not found |

## Solution

```bash
# 1. Genera factories mancanti
php artisan make:factory ModelFactory --model=ModelName

# 2. Oppure disabilita seeder
# Commentare in DatabaseSeeder.php
```

## Acceptance Criteria
- [ ] Tutti i seeder funzionano
- [ ] Factory generate dove necessario
