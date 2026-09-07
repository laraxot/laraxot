# SWARM — PHPStan 272: parallel task distribution

**Swarm ID:** `swarm-phpstan-272`
**Coordinator:** this index
**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Status:** 272 errors → distributing to subagents

## Task Map

| Agent | Moduli | Errori | Story | Status |
|---|---|---|---|---|
| agent-a | Xot | 10 | 01-05 ✅ | DONE |
| agent-b | HR | 1 | 07 ✅ | DONE |
| agent-b | Signage | 0 | - | DONE |
| agent-c | Billing | 13 | 08 🔄 | IN PROGRESS |
| agent-d | Intervention | 21 | 09 | TODO |
| agent-e | Inventory | 20 | 10 | TODO |
| agent-f | Compliance | 18 | 11 | TODO |
| agent-g | Notify (test) | 14 | 12 | TODO |
| agent-h | Quotation | 13 | 13 | TODO |
| agent-i | User (test) | 12 | 14 | TODO |
| agent-j | Catalog | 12 | 15 | TODO |
| agent-k | Activity | 11 | 16 | TODO |
| agent-l | Media | 10 | 17 | TODO |
| agent-m | AiAssistant | 10 | 18 | TODO |
| agent-n | Customer | 8 | 19 | TODO |
| agent-o | Bom | 7 | 20 | TODO |
| agent-p | Cms (test) | 7 | 21 | TODO |
| agent-q | WorkOrder | 6 | 22 | TODO |
| agent-r | UI (test) | 6 | 23 | TODO |
| agent-s | Geo | 5 | 24 | TODO |
| agent-t | altro | ~75 | 25+ | TODO |

## Quality Gate (03-quality-gates.md)
Ogni agent esegue sul proprio modulo:
```bash
cd laravel/Modules/<Modulo>
../../vendor/bin/phpstan analyse . --no-progress --memory-limit=-1
../../vendor/bin/pest tests/<Modulo> --no-coverage
php -l app/.../<file>.php
```

## Coordination Rules
- Git solo avanti, no force, no revert
- Ogni modulo = 1 story BMAD → 1 commit
- Nessun `@phpstan-ignore`, nessun cast per zittire
- `mixed` solo dopo union→generics→contract→template, motivato
- Report ogni modulo chiuso: err_antes → err_dopo

## File modificati in questa sessione (da coordinare)
- `Xot`: TransTrait, HasXotForm, HasXotInfolist, XotBaseResource*
- `HR`: AbsenceRequestsTable docblock
- `Billing`: InvoicesTable imports + types
- `Timber`: TimberEInvoiceForm getFormSchema
