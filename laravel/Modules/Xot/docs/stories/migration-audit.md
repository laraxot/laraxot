---
id: migration-audit
slug: migration-audit
scope: [project:base_workorder_fila5]
status: Pending
priority: High
created: 2026-09-06
---

## Problema
Non verificato se tutte le migration sono applied e allineate.

## Solution

```bash
# 1. Check migration status
php artisan migrate:status

# 2. Check per modulo
php artisan migrate:status --path=Modules/[Module]/database/migrations

# 3. Rollback if needed
php artisan migrate:rollback --path=Modules/[Module]/database/migrations
```

## Acceptance Criteria
- [ ] Tutte le migration applied
- [ ] Nessun conflitto di migration
