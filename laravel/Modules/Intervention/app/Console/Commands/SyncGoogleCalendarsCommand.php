<?php

declare(strict_types=1);

namespace Modules\Intervention\Console\Commands;

use Illuminate\Console\Command;
use Modules\Intervention\GoogleCalendar\Actions\PullGoogleCalendarChangesAction;

/**
 * Pulls schedule/notes changes made on Google Calendar back into the linked
 * interventions, and marks events deleted on Google. Push (app → Google) happens
 * live via the intervention observer; this closes the loop the other way.
 */
final class SyncGoogleCalendarsCommand extends Command
{
    protected $signature = 'interventions:sync-google-calendars';

    protected $description = 'Sync interventions with Google Calendar (pull remote changes)';

    public function handle(PullGoogleCalendarChangesAction $action): int
    {
        $updated = $action->execute();

        $this->info("Interventi aggiornati da Google Calendar: {$updated}");

        return self::SUCCESS;
    }
}
