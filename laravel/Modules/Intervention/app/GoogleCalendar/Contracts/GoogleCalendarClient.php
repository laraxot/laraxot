<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\Contracts;

use App\Models\User;
use Carbon\CarbonInterface;
use Modules\Intervention\GoogleCalendar\DTO\GoogleCalendarEvent;
use Modules\Intervention\GoogleCalendar\DTO\GoogleEventInput;
use Modules\Intervention\Models\UserGoogleCalendarSetting;

/**
 * The whole surface of the Google Calendar integration. The live implementation
 * wraps the google/apiclient SDK; tests bind a fake. This keeps all API-touching
 * code behind one seam.
 */
interface GoogleCalendarClient
{
    public function authorizationUrl(User $user): string;

    public function handleAuthorizationCode(User $user, string $code): UserGoogleCalendarSetting;

    public function disconnect(User $user): void;

    /**
     * Create or update the event for an intervention; returns the Google event id.
     */
    public function upsertEvent(User $user, string $calendarId, ?string $eventId, GoogleEventInput $input): string;

    public function deleteEvent(User $user, string $calendarId, string $eventId): void;

    public function getEvent(User $user, string $calendarId, string $eventId): ?GoogleCalendarEvent;

    /**
     * @return list<GoogleCalendarEvent>
     */
    public function listEvents(User $user, CarbonInterface $startsAt, CarbonInterface $endsAt): array;
}
