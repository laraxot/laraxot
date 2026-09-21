<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\Actions;

use Carbon\CarbonImmutable;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\GoogleCalendar\DTO\GoogleEventInput;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\UserGoogleCalendarSetting;
use Modules\User\Models\User;
use Throwable;

/**
 * Pushes one intervention to its owner's Google Calendar (create/update), and
 * mirrors deletions. All sync-field writes use saveQuietly() so they never
 * re-trigger the observer. API failures are swallowed and recorded as a pending
 * status so a scheduled retry / manual re-sync can pick them up.
 */
final class PushInterventionToGoogleAction
{
    public function __construct(
        private readonly GoogleCalendarClient $client,
    ) {}

    public function execute(Intervention $intervention): void
    {
        [$owner, $setting] = $this->ownerAndSetting($intervention);

        if ($owner === null) {
            $this->markStatus($intervention, 'missing_owner');

            return;
        }

        if ($setting === null || ! $setting->isConnected()) {
            $this->markStatus($intervention, 'disabled');

            return;
        }

        $rawScheduled = $intervention->scheduled_at;

        // No schedule: if an event was previously created, remove it; else nothing.
        if ($rawScheduled === null) {
            $this->deleteFromGoogle($intervention);

            return;
        }

        $scheduledAt = CarbonImmutable::parse($rawScheduled);
        $rawEnds = $intervention->ended_at;
        $endsAt = $rawEnds !== null ? CarbonImmutable::parse($rawEnds) : $scheduledAt->addHour();
        if ($endsAt->lessThanOrEqualTo($scheduledAt)) {
            $endsAt = $scheduledAt->addHour();
        }

        $calendarId = (string) $setting->calendar_id;

        $input = new GoogleEventInput(
            summary: $this->summary($intervention),
            description: is_string($intervention->notes) && $intervention->notes !== '' ? $intervention->notes : null,
            location: $this->location($intervention),
            startsAt: $scheduledAt,
            endsAt: $endsAt,
            timeZone: $this->timeZone(),
        );

        try {
            $eventId = $this->client->upsertEvent(
                $owner,
                $calendarId,
                is_string($intervention->google_event_id) && $intervention->google_event_id !== '' ? $intervention->google_event_id : null,
                $input,
            );
        } catch (Throwable) {
            $this->markStatus($intervention, 'pending');
            $setting->forceFill(['last_sync_error_at' => now()])->save();

            return;
        }

        $intervention->forceFill([
            'google_event_id' => $eventId,
            'google_calendar_id' => $calendarId,
            'google_sync_status' => 'synced',
            'google_synced_at' => now(),
        ])->saveQuietly();

        $setting->forceFill(['last_synced_at' => now(), 'last_sync_error_at' => null])->save();
    }

    public function deleteFromGoogle(Intervention $intervention): void
    {
        $eventId = $intervention->google_event_id;
        if (! is_string($eventId) || $eventId === '') {
            return;
        }

        [$owner, $setting] = $this->ownerAndSetting($intervention);
        if ($owner === null || $setting === null || ! $setting->isConnected()) {
            return;
        }

        try {
            $this->client->deleteEvent($owner, (string) ($intervention->google_calendar_id ?: $setting->calendar_id), $eventId);
        } catch (Throwable) {
            return;
        }

        // Only clear the link when the row still exists (create-with-no-schedule path).
        if ($intervention->exists) {
            $intervention->forceFill([
                'google_event_id' => null,
                'google_sync_status' => null,
                'google_synced_at' => null,
            ])->saveQuietly();
        }
    }

    /**
     * @return array{0: User|null, 1: UserGoogleCalendarSetting|null}
     */
    private function ownerAndSetting(Intervention $intervention): array
    {
        $ownerId = $intervention->performed_by_user_id;
        if ($ownerId === null) {
            return [null, null];
        }

        /** @var User|null $owner */
        $owner = User::query()->find($ownerId);
        if ($owner === null) {
            return [null, null];
        }

        /** @var UserGoogleCalendarSetting|null $setting */
        $setting = UserGoogleCalendarSetting::query()->where('user_id', $owner->id)->first();

        return [$owner, $setting];
    }

    private function markStatus(Intervention $intervention, string $status): void
    {
        if ($intervention->google_sync_status === $status) {
            return;
        }

        $intervention->forceFill(['google_sync_status' => $status])->saveQuietly();
    }

    private function summary(Intervention $intervention): string
    {
        $customerName = $intervention->workOrder?->customer?->legal_name;
        if (! is_string($customerName) || $customerName === '') {
            $customerName = $intervention->customer_id !== null
                ? Customer::query()->whereKey($intervention->customer_id)->value('legal_name')
                : null;
        }

        $parts = array_filter([
            $intervention->workOrder?->title,
            is_string($customerName) ? $customerName : null,
        ]);

        return $parts !== [] ? implode(' · ', $parts) : ('Intervento #'.$intervention->id);
    }

    private function location(Intervention $intervention): ?string
    {
        $site = $intervention->workOrder?->getRelationValue('site');
        if (! $site instanceof CustomerSite) {
            return null;
        }

        $siteName = $site->getAttribute('name');
        $addressLine1 = $site->getAttribute('address_line1');
        $city = $site->getAttribute('city');
        $parts = array_values(array_filter([
            is_string($siteName) ? $siteName : null,
            is_string($addressLine1) ? $addressLine1 : null,
            is_string($city) ? $city : null,
        ], static fn (?string $part): bool => $part !== null && $part !== ''));

        return $parts !== [] ? implode(', ', $parts) : null;
    }

    private function timeZone(): string
    {
        $tz = config('app.timezone');

        return is_string($tz) && $tz !== '' && $tz !== 'UTC' ? $tz : 'Europe/Rome';
    }
}
