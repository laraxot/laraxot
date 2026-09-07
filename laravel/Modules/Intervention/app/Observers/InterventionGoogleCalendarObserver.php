<?php

declare(strict_types=1);

namespace Modules\Intervention\Observers;

use Modules\Intervention\GoogleCalendar\Actions\PushInterventionToGoogleAction;
use Modules\Intervention\Models\Intervention;

/**
 * Single seam that keeps Google Calendar in sync with interventions: pushes on
 * create/relevant-update and mirrors deletions. No-ops entirely when Google
 * Calendar is not configured, so the rest of the app is unaffected.
 */
final class InterventionGoogleCalendarObserver
{
    /** Fields whose change warrants re-pushing the Google event. */
    /** @var list<string> */
    private const SYNC_FIELDS = ['scheduled_at', 'ended_at', 'notes', 'performed_by_user_id', 'work_order_id', 'customer_id'];

    public function saved(Intervention $intervention): void
    {
        if (! $this->enabled()) {
            return;
        }

        // Skip pushes triggered by unrelated updates once the event already exists.
        $needsPush = $intervention->wasRecentlyCreated
            || $intervention->google_event_id === null
            || $intervention->wasChanged(self::SYNC_FIELDS);

        if (! $needsPush) {
            return;
        }

        app(PushInterventionToGoogleAction::class)->execute($intervention);
    }

    public function deleted(Intervention $intervention): void
    {
        if (! $this->enabled()) {
            return;
        }

        app(PushInterventionToGoogleAction::class)->deleteFromGoogle($intervention);
    }

    private function enabled(): bool
    {
        $clientId = config('services.google.calendar.client_id');

        return is_string($clientId) && trim($clientId) !== '';
    }
}
