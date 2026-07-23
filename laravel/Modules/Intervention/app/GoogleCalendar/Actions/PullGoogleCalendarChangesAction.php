<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\Actions;

use App\Models\User;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\GoogleCalendar\DTO\GoogleCalendarEvent;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\UserGoogleCalendarSetting;
use Throwable;

/**
 * Reconciles interventions already linked to a Google event: pulls back schedule
 * (and notes) changes made on Google, and marks events deleted on Google. Writes
 * use saveQuietly() so they never bounce back as a push. Only applies changes
 * strictly newer than our last sync, so a just-pushed event is not re-imported.
 */
final class PullGoogleCalendarChangesAction
{
    public function __construct(
        private readonly GoogleCalendarClient $client,
    ) {}

    public function execute(): int
    {
        $updated = 0;

        $settings = UserGoogleCalendarSetting::query()->where('enabled', true)->get();
        foreach ($settings as $setting) {
            /** @var UserGoogleCalendarSetting $setting */
            if (! $setting->isConnected()) {
                continue;
            }

            /** @var User|null $owner */
            $owner = User::query()->find($setting->user_id);
            if ($owner === null) {
                continue;
            }

            try {
                $updated += $this->reconcileForUser($owner, $setting);
            } catch (Throwable) {
                $setting->forceFill(['last_sync_error_at' => now()])->save();

                continue;
            }

            $setting->forceFill(['last_synced_at' => now()])->save();
        }

        return $updated;
    }

    private function reconcileForUser(User $owner, UserGoogleCalendarSetting $setting): int
    {
        $calendarId = (string) $setting->calendar_id;
        $updated = 0;

        $interventions = Intervention::query()
            ->where('performed_by_user_id', $owner->id)
            ->whereNotNull('google_event_id')
            ->where(function ($query): void {
                $query->whereNull('google_sync_status')->orWhere('google_sync_status', '!=', 'deleted_external');
            })
            ->get();

        foreach ($interventions as $intervention) {
            /** @var Intervention $intervention */
            $event = $this->client->getEvent($owner, $calendarId, (string) $intervention->google_event_id);

            if ($event === null) {
                $intervention->forceFill(['google_sync_status' => 'deleted_external'])->saveQuietly();
                $updated++;

                continue;
            }

            if (! $this->isNewer($event, $intervention)) {
                continue;
            }

            $changes = ['google_sync_status' => 'synced', 'google_synced_at' => now()];
            if ($event->startsAt !== null) {
                $changes['scheduled_at'] = $event->startsAt;
            }
            if ($event->endsAt !== null) {
                $changes['ended_at'] = $event->endsAt;
            }
            if ($event->description !== null) {
                $changes['notes'] = $event->description;
            }

            $intervention->forceFill($changes)->saveQuietly();
            $updated++;
        }

        return $updated;
    }

    private function isNewer(GoogleCalendarEvent $event, Intervention $intervention): bool
    {
        if ($event->updatedAt === null) {
            return true;
        }

        $lastSynced = $intervention->google_synced_at;

        return $lastSynced === null || $event->updatedAt->greaterThan($lastSynced);
    }
}
