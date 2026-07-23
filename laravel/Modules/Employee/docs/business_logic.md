## business logic – employee module

This document describes the core business logic of the Employee module: time tracking, presence, scheduling, data model, and UI widgets integration. It follows Laraxot rules (strict typing, no hardcoded labels, docs in lowercase, bidirectional links).

### scope
- Time tracking (WorkHour): clock-in/out, daily sessions, status.
- Presence widgets (today/team): aggregates based on WorkHour of current day.
- Scheduling (upcoming shifts/appointments) when available from domain.
- Supporting enums, factories, and migration constraints.

### data model
- `WorkHour` (entity):
  - attributes: `employee_id:int`, `type: WorkHourTypeEnum`, `timestamp: datetime`, `status: WorkHourStatusEnum`, `notes:string|null`.
  - business rules:
    - entries are ordered by `timestamp` asc for daily computations.
    - session is a pair of `(CLOCK_IN, CLOCK_OUT)`; unmatched IN ⇒ active session, unmatched OUT ⇒ closed one-off (data import/repair).
  - invariants:
    - on insert, `type` must be one of enum values.
    - `timestamp` uses app timezone; display formatting handled at UI layer.
  - recommended indices: `(employee_id, timestamp)`.

- `Employee` (aggregate root placeholder): in this module we operate by `Auth::id()` for widgets; relation-based queries should target the concrete model once available.

### enums
- `WorkHourTypeEnum`: `CLOCK_IN`, `CLOCK_OUT`.
- `WorkHourStatusEnum`: `PENDING`, `APPROVED`, `REJECTED` (extend as needed). All persistence must use enum casting.

### time tracking flow
1. User presses “Timbra Entrata” → `TimeClockWidget::clockIn()`:
   - guard: if already `isClockedIn === true` notify warning.
   - create `WorkHour` with `type=CLOCK_IN`, `status=PENDING`, `timestamp=now()`.
   - refresh widget state (`updateData()`) and notify success.

2. User presses “Timbra Uscita” → `TimeClockWidget::clockOut()`:
   - guard: require `isClockedIn === true`, otherwise warn.
   - create `WorkHour` with `type=CLOCK_OUT`.
   - refresh and notify.

3. `updateData()` computes:
   - `currentTime` (HH:mm) and localized `todayDate`.
   - loads today entries for the current user and builds `sessions`:
     - each `CLOCK_IN` starts an active session.
     - the next `CLOCK_OUT` closes the last active session.
     - unmatched `CLOCK_OUT` becomes a completed session with empty `in`.
   - sets `isClockedIn` and `sessionStatus` from the last entry.

### ui widgets
- `TimeClockWidget` (Filament, 3 columns responsive):
  - left: live clock + date.
  - center: compact list of sessions (active pulses green; completed gray) showing `IN → OUT`.
  - right: primary action button toggling between Entrata/Uscita.
  - polling every 1s for consistent freshness while keeping logic light.

### validation and feedback
- All actions notify user via Filament Notifications (`success|warning|danger`).
- No client labels hardcoded in PHP: UI strings must come from translation files in the hosting module/theme.

### constraints & guards
- One active session per user/day (enforced logically by guard; server-side reconciliation job can be added for imports).
- Clock-out requires an active session.
- All writes use `Auth::id()`.

### presence and team widgets (summary)
- TodayPresence/TeamPresence widgets compute current-day aggregates using `WorkHour` with `whereDate(timestamp, today())` and last entry semantics (IN ⇒ present; OUT ⇒ not present). All queries must be fully typed and return Eloquent collections.

### scheduling (future work)
- UpcomingSchedule reads future `WorkHour` (or a dedicated entity `Schedule`) between a time window and maps them to UI events; use eager loading and typed helpers.

### factories & seeders
- Factories must use real Faker methods and enum values; avoid magic constants. Ensure return types match PHPStan level 10 requirements (`array<string, mixed>`), and models are correctly referenced under `Modules\Employee\Models`.

### migration rules
- Use `XotBaseMigration` anonymous classes (no `down()`), guard `hasTable/hasColumn`, and add indexes. Foreign keys should not call methods missing in Laravel versions (e.g., `comment()` on FK definitions is not allowed).

### quality gates
- PHPStan level 10 (no ignores): keep all public properties and methods typed; add docblocks for shaped arrays like `array<int, array{time:string, type:string}>` and `sessions`.
- Follow Laraxot rules for Filament (no hardcoded labels, `setUp()` when creating actions, strict types).

### backlinks
- See `timeclock-widget-layout-fix.md` and `timeclock-widget-refactoring-final.md` for UI details.
- Root docs on PHPStan and Laraxot rules apply globally.


