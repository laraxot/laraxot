# Activity Module: Event Sourcing & Audit Trail

> **Immutable Event Store** — Every action becomes an event. Replay history, reconstruct state, comply with GDPR.

---

## Zen

**"Events are source of truth. Snapshots are derived."**

Never delete events. Append-only log = auditable, compliant, debuggable.

---

## Quick Architecture

### Models (5)
- **StoredEvent** — Immutable event (aggregate_id, event_type, payload, created_at)
- **Snapshot** — Read cache (aggregate snapshot, version)
- **Activity** — User action log (model, action, user_id, changes)
- **TestModel** — Fixture for testing

### Pattern: CQRS
```
Domain Event (user created)
  ↓
StoredEvent (append-only)
  ↓
Snapshot (cache for read)
  ↓
Projection (Activity log for UI)
```

### Traits
- **HasEvents** — Emit domain events
- **HasSnapshots** — Cache aggregates

### Actions (11)
- `LogActivityAction` — Record user action
- `ActivityLoggerAction` — Batch audit
- `ActivityMaintenanceAction` — Cleanup old snapshots (not events!)

---

## Integration

**Who uses**:
- User (track logins, role changes)
- Notify (log all sends)
- Employee (track absence requests)
- All modules (via `HasEvents` trait)

---

## Best Practices

✓ **Immutable events** (never update StoredEvent)
✓ **Snapshots for perf** (query snapshots, not raw events)
✓ **Idempotent replay** (re-running event sequence = same result)
✓ **Tenant-scoped events** (event.tenant_id ensures isolation)

---

## Bad Practices

❌ Delete old events (breaks compliance)
❌ Query raw events without snapshots (N+1)
❌ Non-idempotent event handlers (side effects)

---

## Roadmap

- Multi-store events (DB + EventStoreDB for distributed)
- Real-time event stream (WebSocket subscriptions)
- Event replay UI (time-travel debugging)
- Analytics on event patterns

---

## Summary

```
┌───────────────────────────────┐
│ Activity (Event Sourcing)     │
├───────────────────────────────┤
│ Purpose: Audit trail, events  │
│ Models: 5                     │
│ Migrations: 8                 │
│ Status: Stable                │
│ Dependencies: Xot             │
│ Reverse: All modules          │
└───────────────────────────────┘
```

---

- **Generated**: 2026-09-06
- **Author**: Claude (eccentrico mode)

