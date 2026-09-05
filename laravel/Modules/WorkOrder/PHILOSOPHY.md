# WorkOrder Module Philosophy

Work orders are promises to customers. Every state change is audited, every cost is tracked, every failure is documented.

## First Principle

> **State you can't explain is state you don't have. Ask the event log, not the database.**

Work order status is immutable history. No direct updates. Only actions change state, and every action creates an event.

## The Rules

### 1. Immutable Status Events

Status changes via `ChangeWorkOrderStatusAction` only:
```php
class ChangeWorkOrderStatusAction {
    public function execute(WorkOrder $workOrder, WorkOrderStatus $status) {
        WorkOrderStatusEvent::create([
            'work_order_id' => $workOrder->id,
            'status_id' => $status->id,
            'changed_by' => auth()->id(),
            'changed_at' => now(),
        ]);
        $workOrder->update(['status_id' => $status->id]);
    }
}
```

**Never** update `status_id` directly. Always create an event first.

**Why:** Audit trail is proof. Who changed it, when, why? Ask the event log.

### 2. Cost Variance is Visible

Every intervention tracks actual cost vs budgeted cost:
```php
$intervention->work_minutes // actual hours logged
$intervention->materialsLines // actual materials used
// vs
$workOrder->estimated_hours
$workOrder->budget
```

**Never** hide cost discrepancies. Expose them.

**Why:** Margins disappear in silence. Visible variances drive improvement.

### 3. Status Code Actions for Mobile

Mobile app uses `ChangeWorkOrderStatusByCodeAction`:
```php
$workOrder->updateStatusByCode('completed') // code = QR code scanned
```

Status codes are printable, scannable, offline-safe.

**Why:** Field technician offline, scanning QR code, updating status. Code survives network loss.

### 4. Soft Delete + Cascade Restore

Deleted work orders can be restored:
```php
$workOrder->delete() // soft delete + cascade interventions, status events
$workOrder->restore() // restore entire tree
```

**Why:** Accidental deletes are reversible. Audit trails intact.

### 5. Interventions Are Details

Interventions are children of work orders, not independent:
```php
$workOrder->interventions()->create([...])
```

**Never** create an intervention without a parent work order.

**Why:** Interventions are execution details. Work order is the container.

### 6. Pre-Invoice From WorkOrder

Invoices are generated from work orders, not created manually:
```php
BuildWorkOrderPreInvoiceAction::execute($workOrder)
```

**Never** create a line item outside of pre-invoice workflow.

**Why:** Invoice accuracy = pre-invoice accuracy. Disconnect = fraud risk.

### 7. Contact Scope is Flexible

A work order can have multiple contacts (customer owner, site manager, invoice recipient):
```php
$workOrder->contacts()->attach($contact, ['role' => 'invoice_recipient'])
```

**Why:** One customer, multiple stakeholders. Flexibility in who gets notified.

## The Zen of WorkOrder

> **Complete a work order, invoice is half-done. Close an intervention, materials auto-deducted. Ask the event log, audit is automatic.**

- Status is event history, not a column
- Cost is variance tracked, not guessed
- Interventions are details, work order is the whole
- Restore one, restore all
- Invoices follow work, not reverse

## Breaking the Rules

If you think a rule is wrong:
1. **Document the exception** in your module's README
2. **Add event tracking** if bypassing the status workflow
3. **Expose variances** if hiding cost discrepancies
4. **Notify maintainers** if the status model needs refactoring
5. **Never directly update status** — always use actions

## See Also

- `ARCHITECTURE.md` — status state machine, intervention design
- `TESTING.md` — event-driven testing, cost variance testing
- `docs/status-events.md` — WorkOrderStatusEvent structure and querying
- `docs/cost-tracking.md` — variance calculation and reporting
- `docs/mobile-integration.md` — status code QR scanning workflow
- `docs/pre-invoice-workflow.md` — invoice generation from work orders
