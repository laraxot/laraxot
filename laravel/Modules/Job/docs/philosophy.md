# Job Module: Async Work & Scheduling

> **Background Task Orchestration** — Deferred work (exports, bulk operations, cron jobs), failure tracking, retry logic.

---

## Zen

**"Async first. Queue everything that can wait."**

Job module turns synchronous pain into async progress. No user waits for a 10-minute export; it queues, they get notified.

---

## Architecture (Quick)

### Models (17)
- **Export** — Async export job (queue + status)
- **Schedule** — Cron job definition (command, args, next run)
- **Task** — Unit of work (atomic, queueable)
- **FailedJob** — Audit trail (failed tasks, stack trace, retry count)
- **FailedImportRow** — Import error log (row number, error, suggestion)
- **ScheduleArgument**, **ScheduleOptions** — Job parameters

### Pattern
```
User Request
  ↓
Action (immediate work)
  ↓
QueueableAction::dispatch() → Queue
  ↓
Worker (async)
  ↓
FailedJob (if error)
  ↓
Retry (exponential backoff) or DLQ
```

### Traits
- **FormatSeconds** — Duration formatting (for UI display)

### Actions (6)
- `ExecuteTaskAction` — Run queued task
- `ClearScheduleCacheAction` — Invalidate cron cache
- `GetTaskCommandsAction` — List artisan commands
- `GetActiveSchedulesAction` — Current schedule state

---

## Integration

**Who queues**:
- Notify (batch email dispatch)
- Employee (report generation)
- Media (FFmpeg conversion)
- Cms (bulk publish)

**Reverse**: All modules depend on Job for async work.

---

## Best Practices

1. **Exponential backoff** — Failed jobs retry 3× before DLQ
   ```php
   SomeAction::dispatch($data)->delay(60); // 1 min, then 5, 15 exponential
   ```

2. **Immutable FailedJob** — Never delete, audit all failures
3. **Schedule cache invalidation** — Manual trigger via action (or auto-invalidate on schedule change)
4. **Tenant scoping** — Job runs within tenant context (via middleware)

---

## Bad Practices

- ❌ Fire-and-forget (no error tracking)
- ❌ Synchronous long-running operations
- ❌ Queue job with no retry logic
- ❌ Storing sensitive data in job payload (serialize carefully)

---

## Roadmap

- Distributed queue (SQS, RabbitMQ)
- Real-time progress tracking (WebSocket updates)
- Job prioritization (critical vs normal vs low)
- Scheduled job UI (create/edit/pause cron jobs)

---

## Summary

```
┌─────────────────────────────┐
│ Job (Async Orchestration)   │
├─────────────────────────────┤
│ Purpose: Queue + scheduling │
│ Models: 17                  │
│ Migrations: 14              │
│ Status: Stable              │
│ Dependency: Xot, Notify     │
│ Reverse: All modules        │
└─────────────────────────────┘
```

---

- **Generated**: 2026-09-06
- **Author**: Claude (eccentrico mode)

