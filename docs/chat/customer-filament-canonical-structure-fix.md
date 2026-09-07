---
name: customer-filament-canonical-structure-fix
description: "Task assignment to migrate Customer legacy Resources (CustomerResource, ResellerResource) to canonical Schemas/Tables structure — found already completed by concurrent Epic 4 swarm (opencode) before any edit was made"
metadata:
  story: 2.15.filament-swarm-migrate / 2.10.filament-audit-gap
  epic: 4 (filament-canonical-structure-14-modules)
  date: 2026-09-07
  outcome: no-op, gap already resolved by another live agent
---

# Customer Filament canonical structure — standdown, no edit made

## Task received

Migrate `CustomerResource` and `ResellerResource` in `Modules/Customer` to the canonical
`Schemas/<Name>Form.php` + `Tables/<Plural>Table.php` structure, per
`bashscripts/docs/prompts/06-filament-audit.md` §5a, using story
`docs/stories/2.15.filament-swarm-migrate.story.md` / `docs/stories/2.10.filament-audit-gap.story.md`.
Explicit warning in the brief: a previous session had found `CustomerResource.php` with
non-committed WIP ("just import reordering"), possibly since resolved or still pending.

## Pre-flight findings (before any edit)

`git -C laravel/Modules/Customer status` showed, for **both** Resources (not just
`CustomerResource`):

- `app/Filament/Resources/CustomerResource.php` — modified, inline `getFormSchema()`/`table()`
  already removed
- `app/Filament/Resources/ResellerResource.php` — modified, inline `getFormSchema()`/`table()`
  already removed
- `app/Filament/Resources/CustomerResource/Schemas/CustomerForm.php` — new, full content
  extracted, `extends XotBaseResourceForm`, correct `use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;`
- `app/Filament/Resources/CustomerResource/Tables/CustomersTable.php` — new, full content
  extracted, `extends XotBaseResourceTable`, correct namespace
- `app/Filament/Resources/ResellerResource/Schemas/ResellerForm.php` — same pattern
- `app/Filament/Resources/ResellerResource/Tables/ResellersTable.php` — same pattern
- `.lock` files present on both `CustomerResource.php` and `ResellerResource.php`:
  `LOCKED at 2026-09-07T15:39:15+02:00`, task `extract Schemas/Tables` — confirmed **live**
  (`bashscripts/lock/check.sh` → age 4m7s, "un lock di pochi minuti e vivo: non rubarlo")

`bash bashscripts/tools/filament_audit.sh Customer` confirmed:

```
✅ CustomerResource: Schemas=1 Tables=1 tableActions=DEFAULT
✅ ResellerResource: Schemas=1 Tables=1 tableActions=explicit
Customer: 0 Resource(s) con gap
```

This is not "just import reordering" — it's the exact recipe from §5a, fully applied, by the
active Epic 4 swarm (`filament-canonical-structure-14-modules`, agent `opencode`, per
`docs/chat/INDEX.md` — Customer is one of the 14 listed modules; Billing (4.1) already
completed the same day with the identical pattern).

## Decision

Per the brief's own stop condition ("altro agente gia' attivo sul modulo... FERMATI") and the
audit prompt's own rule (§ "Quando fermarsi e chiedere" → "Altro agente già attivo sullo stesso
modulo"): **no file was created, edited, or committed**. Both target Resources were locked and
already migrated before this session touched anything.

## Note for whoever picks this up next

- `getFormSchema()`/`getTableColumns()` extraction is done and matches the canonical shape.
- Residual quality-gate item **not yet fixed** by the swarm (out of scope here, flagging only):
  `CustomerForm.php`, `CustomersTable.php`, `ResellerForm.php`, `ResellersTable.php` still carry
  hardcoded `->label(__('customers.fields.*'))` / `->label(__('resellers.fields.*'))` calls,
  which §"Zero label hardcoded" of `06-filament-audit.md` forbids (LangServiceProvider should
  auto-resolve these). Not touched here — files are under another agent's live lock.
- No PHPStan/Pint/Pest/PHPMD run in this session (nothing changed, and running under another
  agent's live lock on the same files risks confusing state); defer to the swarm's own quality
  gate for Customer.

## Commit

None. No files changed by this session.
