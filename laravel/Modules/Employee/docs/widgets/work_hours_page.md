## WorkHoursPage – design and implementation guide

This guide documents how to implement the WorkHoursPage for the Employee module, replicating the target UI while adhering to Laraxot and PHPStan L10.

### goals
- Weekly “work hours” view with timeline per day.
- Header with current time/date and primary action (clock in/out).
- Week navigation (prev/next/calendar – to be added next iterations).
- Translations only for user-facing strings (English in code identifiers).
- Business logic in Spatie Queueable Actions (no services).

### architecture
- Page class: `Modules\Employee\Filament\Pages\WorkHoursPage` extends `XotBasePage`.
- View: `employee::filament.pages.work-hours`.
- Action: `Modules\Employee\Actions\BuildTimeEntriesForRangeAction` (queueable) builds sessions and weekly summary.

### action contract
`BuildTimeEntriesForRangeAction::execute(int $userId, Carbon $start, Carbon $end): array` returns
```
{
  byDate: { [date: string]: Array<{ start: string, end: string|null, status: string }> },
  summary: { workedMinutes: int, addedMinutes: int, reducedMinutes: int, contractMinutes: int },
  contracts: { [date: string]: int }
}
```

### rendering logic
- Group sessions by date and render compact rows; active session shows a green pulsing dot, completed is gray.
- Mobile → stacked; Desktop → 3 columns like the reference.

### translations
- Files: `lang/en/time_entries.php`, `lang/it/time_entries.php` (expanded structure as in module rules).
- Example keys used in the view:
  - `employee::time_entries.header.clock_in`
  - `employee::time_entries.timeline.in_progress`
  - `employee::time_entries.timeline.no_entries`

### phpstan compliance
- All arrays returned by the Action are shaped via phpdoc.
- Page computes `userId` via `Auth::id()` cast to int.

### future improvements
- Week selector with date picker and prev/next.
- Contract hours integration and color-coded bars by type/status.
- Export button and summary counters (worked/added/reduced/contract).
- Queue the Action when generating heavy ranges; call `.onQueue()->execute(...)`.

### references
- Spatie Queueable Actions: https://github.com/spatie/laravel-queueable-action