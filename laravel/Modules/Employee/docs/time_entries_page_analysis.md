## time entries page – analysis and design notes

This document analyses the provided screenshot and derives a faithful, optimized implementation plan for the Employee module. All names in code will be English; UI strings will be provided through translations.

### key goals (from screenshot)
- Header card with current time and current date; primary action button "Clock in" (dynamic to "Clock out").
- Week selector with current week range, previous/next arrows, and a calendar picker.
- Employee row with badges (role/team), weekly summary numbers (worked/added/reduced/contract hours).
- Daily timeline grid (06:00 → 20:00) showing intervals:
  - Green/orange bars representing work blocks and their statuses (in progress, completed, imported).
  - On-going block indicator "in progress".
  - Text hints like contract hours per day (e.g., 8h, 4h).

### data model assumptions
- Existing entity `WorkHour` describes atomic entries. For timelines we need merged intervals (sessions) with start/end.
- Weekly context: for a selected week [start, end], we fetch:
  - sessions: array of `{ start: Carbon, end: Carbon|null, status: WorkHourStatusEnum, type: WorkHourTypeEnum }` grouped by day.
  - daily contract target (hours) to show per-day targets.
  - weekly totals: worked, added, reduced, contract.

### derived domain objects (view-DTO)
- `TimeEntrySessionView` (DTO):
  - `start: Carbon`, `end: Carbon|null`, `isActive: bool`, `status: string` (enum value), `notes?: string`.
- `WeekSummaryView` (DTO):
  - `workedMinutes:int`, `addedMinutes:int`, `reducedMinutes:int`, `contractMinutes:int`.
- `DayContractView` (DTO): `date: Carbon`, `targetMinutes:int`.

### ux and a11y
- Keyboard navigable week controls; accessible labels for timeline cells.
- Color + shape for state (not color-only). Add dash pattern for reduced/imported segments.
- Compact mobile: stack layout; desktop: 2-column layout (left filters, right timeline) or single full-width grid as in screenshot.

### api and performance
- Eager-load all week entries in one query. Cache weekly summaries per user for a short TTL (e.g., 60s) to keep polling responsive.
- All computations happen server-side; client receives normalized arrays for day columns and segments.

### mapping from current module capabilities
- `TimeClockWidget` already builds day sessions for TODAY. We can generalize to a service:
  - `TimeEntriesService::buildSessionsForRange(int $userId, Carbon $start, Carbon $end): array{byDate: array<string, list<TimeEntrySessionView>>, summary: WeekSummaryView, contracts: list<DayContractView>}`.
  - Reuse the same pairing logic (IN → OUT) applied to an entire range.

### implementation outline
1. Create `app/Services/TimeEntriesService.php` (strict types, full PHPStan typing).
2. Create Filament page `TimeEntriesPage` with:
   - header: current time/date + primary action button.
   - filters: week picker (previous/next + date picker).
   - body: timeline grid component rendering days and sessions.
3. Add translations under `Modules/Employee/lang/{locale}/time_entries.php` with expanded structure.
4. Tests: service unit tests for session pairing and weekly summary edges (unmatched OUT, overlapping entries, midnight boundaries).

### optimizations
- Use minute-based grid (e.g., 15-min slots) but render only required bars, not individual cells.
- Derive summaries by SQL where convenient (SUM duration per type) to reduce PHP loops.
- Guard data-quality: flag overlapping or inverted entries; expose non-blocking notice.

### open questions / future work
- Contract hours source per weekday: configuration table vs. HR integration.
- Added/Reduced logic: confirm semantics (overtime vs. manual adjustments).