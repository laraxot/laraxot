## time entries page – implementation plan

### objectives
- Recreate the “Time Entries” page with weekly view, timeline, and action controls.
- English-only code identifiers; all labels through translations.
- PHPStan level 10 compliance.

### components
- Service: `TimeEntriesService`
  - `buildSessionsForRange(int $userId, Carbon $start, Carbon $end): array{byDate: array<string, list<array{start: Carbon, end: ?Carbon, status: string}>>, summary: array{worked:int, added:int, reduced:int, contract:int}, contracts: array<string,int>}`
- Page: `TimeEntriesPage` (Filament page extends Xot base)
  - Header: live clock widget + primary action button.
  - Filters: week selector with prev/next and date picker.
  - Body: timeline grid component rendering days and sessions.
- View: `employee::filament.pages.time-entries`
  - Responsive timeline like screenshot; compact on mobile.

### translations
- File `lang/en/time_entries.php` and `lang/it/time_entries.php` with expanded structure: header, buttons, filters, timeline labels, empty states, export.

### testing
- Unit tests for `TimeEntriesService` pairing logic and weekly aggregates.
- Feature tests for page rendering and filters.

### tasks
1. Create service with strict typing and docblocks.
2. Add page class and blade view (no hardcoded strings).
3. Add translations (en/it).
4. Run PHPStan level 10 and fix issues.
5. Update docs links from README to this plan and analysis.