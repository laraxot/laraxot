## WorkHoursBoardWidget – analysis and design

Target: replicate the weekly timetable widget from the reference screenshot within a Filament widget.

### responsibilities
- Display current week range with prev/next controls.
- Show per‑day sessions (IN→OUT) with status indicator.
- Provide compact summaries (future iteration).

### data
Built via `BuildTimeEntriesForRangeAction` to compute `byDate` and `summary`. The widget keeps `startDate/endDate` state and calls the Action synchronously (queueable later if needed).

### ui / ux
- Header with navigation arrows and date span.
- Grid of 7 day cards, each listing sessions. Active sessions pulse green.
- Mobile-friendly; no hardcoded labels.

### performance
- One query per week through the Action; state updates (prev/next) recalc the payload.
- Ready to `.onQueue()` for expensive ranges when needed.

### integration
- Use inside `WorkHoursPage` (page acts as container). The page should only orchestrate and pass top-level context; the widget owns the timetable rendering.

