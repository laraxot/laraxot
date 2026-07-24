<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Google\Client;
use Google\Service\Calendar\Calendar as GoogleCalendarService;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Exception as GoogleServiceException;
use Google\Service\Oauth2;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\GoogleCalendar\DTO\GoogleCalendarEvent;
use Modules\Intervention\GoogleCalendar\DTO\GoogleEventInput;
use Modules\Intervention\Models\UserGoogleCalendarSetting;
use Modules\User\Models\User;
use RuntimeException;

/**
 * Live Google Calendar client backed by google/apiclient. Kept behind the
 * {@see GoogleCalendarClient} seam so the rest of the app (and its tests) never
 * touch the SDK. Excluded from PHPStan/coverage — it is exercised end-to-end
 * against Google, not in unit tests.
 */
final class GoogleApiCalendarClient implements GoogleCalendarClient
{
    public function authorizationUrl(User $user): string
    {
        $client = $this->baseClient();
        $client->setState((string) $user->id);

        return $client->createAuthUrl();
    }

    public function handleAuthorizationCode(User $user, string $code): UserGoogleCalendarSetting
    {
        $client = $this->baseClient();
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new RuntimeException('Google OAuth error: '.(string) $token['error']);
        }

        $client->setAccessToken($token);

        $calendarId = 'primary';
        try {
            $items = (new GoogleCalendarService($client))->calendarList->listCalendarList()->getItems();
            $first = $items[0] ?? null;
            if ($first !== null && is_string($first->getId())) {
                $calendarId = $first->getId();
            }
        } catch (\Throwable) {
            // keep 'primary'
        }

        $googleEmail = null;
        try {
            $info = (new Oauth2($client))->userinfo->get();
            $googleEmail = is_string($info->getEmail()) ? $info->getEmail() : null;
        } catch (\Throwable) {
            $googleEmail = $user->email;
        }

        /** @var UserGoogleCalendarSetting $record */
        $record = UserGoogleCalendarSetting::firstOrNew(['user_id' => $user->id]);
        $record->enabled = true;
        $record->google_email = $googleEmail;
        $record->calendar_id = $calendarId;
        $record->access_token = (string) ($token['access_token'] ?? '');
        $refresh = (string) ($token['refresh_token'] ?? '');
        if ($refresh !== '') {
            $record->refresh_token = $refresh;
        }
        $record->token_expires_at = isset($token['expires_in']) ? now()->addSeconds((int) $token['expires_in']) : null;
        $record->last_synced_at = now();
        $record->last_sync_error_at = null;
        $record->save();

        return $record;
    }

    public function disconnect(User $user): void
    {
        /** @var UserGoogleCalendarSetting|null $record */
        $record = UserGoogleCalendarSetting::query()->where('user_id', $user->id)->first();
        if ($record === null) {
            return;
        }

        try {
            $client = $this->baseClient();
            $refresh = (string) $record->refresh_token;
            if ($refresh !== '') {
                $client->revokeToken($refresh);
            }
        } catch (\Throwable) {
            // best effort
        }

        $record->enabled = false;
        $record->access_token = null;
        $record->refresh_token = null;
        $record->token_expires_at = null;
        $record->save();
    }

    public function upsertEvent(User $user, string $calendarId, ?string $eventId, GoogleEventInput $input): string
    {
        $calendar = new GoogleCalendarService($this->authorizedClient($user));

        $event = new Event();
        $event->setSummary($input->summary);
        if ($input->description !== null) {
            $event->setDescription($input->description);
        }
        if ($input->location !== null) {
            $event->setLocation($input->location);
        }
        $event->setStart($this->eventDateTime($input->startsAt, $input->timeZone));
        $event->setEnd($this->eventDateTime($input->endsAt, $input->timeZone));

        $saved = $eventId !== null && $eventId !== ''
            ? $calendar->events->update($calendarId, $eventId, $event)
            : $calendar->events->insert($calendarId, $event);

        return (string) $saved->getId();
    }

    public function deleteEvent(User $user, string $calendarId, string $eventId): void
    {
        $calendar = new GoogleCalendarService($this->authorizedClient($user));

        try {
            $calendar->events->delete($calendarId, $eventId);
        } catch (GoogleServiceException $e) {
            if ($e->getCode() !== 404 && $e->getCode() !== 410) {
                throw $e;
            }
        }
    }

    public function getEvent(User $user, string $calendarId, string $eventId): ?GoogleCalendarEvent
    {
        $calendar = new GoogleCalendarService($this->authorizedClient($user));

        try {
            $event = $calendar->events->get($calendarId, $eventId);
        } catch (GoogleServiceException $e) {
            if ($e->getCode() === 404 || $e->getCode() === 410) {
                return null;
            }
            throw $e;
        }

        return $this->mapEvent($event);
    }

    /**
     * @return list<GoogleCalendarEvent>
     */
    public function listEvents(User $user, CarbonInterface $startsAt, CarbonInterface $endsAt): array
    {
        $calendar = new GoogleCalendarService($this->authorizedClient($user));
        /** @var UserGoogleCalendarSetting $setting */
        $setting = UserGoogleCalendarSetting::query()->where('user_id', $user->id)->firstOrFail();

        $items = $calendar->events->listEvents((string) $setting->calendar_id, [
            'singleEvents' => true,
            'orderBy' => 'startTime',
            'timeMin' => $startsAt->toIso8601String(),
            'timeMax' => $endsAt->toIso8601String(),
            'maxResults' => 250,
        ])->getItems();

        $out = [];
        foreach ($items as $item) {
            $out[] = $this->mapEvent($item);
        }

        return $out;
    }

    private function mapEvent(Event $event): GoogleCalendarEvent
    {
        return new GoogleCalendarEvent(
            id: (string) $event->getId(),
            summary: (string) ($event->getSummary() ?? '(senza titolo)'),
            description: $event->getDescription(),
            location: $event->getLocation(),
            startsAt: $this->parseEventDate($event->getStart()),
            endsAt: $this->parseEventDate($event->getEnd()),
            updatedAt: $event->getUpdated() ? CarbonImmutable::parse($event->getUpdated()) : null,
        );
    }

    private function parseEventDate(?EventDateTime $dateTime): ?CarbonImmutable
    {
        if ($dateTime === null) {
            return null;
        }

        $value = $dateTime->getDateTime() ?: $dateTime->getDate();

        return $value ? CarbonImmutable::parse($value) : null;
    }

    private function eventDateTime(CarbonInterface $moment, string $timeZone): EventDateTime
    {
        $dt = new EventDateTime();
        $dt->setDateTime($moment->format('Y-m-d\TH:i:s'));
        $dt->setTimeZone($timeZone);

        return $dt;
    }

    private function baseClient(): Client
    {
        $clientId = (string) config('services.google.calendar.client_id');
        $clientSecret = (string) config('services.google.calendar.client_secret');

        if ($clientId === '' || $clientSecret === '') {
            throw new RuntimeException('Google Calendar non configurato: imposta GOOGLE_CALENDAR_CLIENT_ID e GOOGLE_CALENDAR_CLIENT_SECRET.');
        }

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(route('interventions.google-calendar.callback'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setIncludeGrantedScopes(true);
        $client->setScopes([GoogleCalendarService::CALENDAR, Oauth2::USERINFO_EMAIL]);

        return $client;
    }

    private function authorizedClient(User $user): Client
    {
        /** @var UserGoogleCalendarSetting $record */
        $record = UserGoogleCalendarSetting::query()->where('user_id', $user->id)->firstOrFail();

        $client = $this->baseClient();
        $client->setAccessToken([
            'access_token' => (string) $record->access_token,
            'refresh_token' => (string) $record->refresh_token,
            'expires_in' => max(1, (int) now()->diffInSeconds($record->token_expires_at ?? now()->subSecond(), false)),
            'created' => now()->timestamp,
        ]);

        if ($client->isAccessTokenExpired()) {
            $refresh = trim((string) $record->refresh_token);
            if ($refresh === '') {
                throw new RuntimeException('Connessione Google Calendar scaduta: ricollega l\'account.');
            }

            $fresh = $client->fetchAccessTokenWithRefreshToken($refresh);
            if (isset($fresh['error'])) {
                $record->forceFill(['last_sync_error_at' => now()])->save();
                throw new RuntimeException('Refresh token Google non valido: '.(string) $fresh['error']);
            }

            $record->access_token = (string) ($fresh['access_token'] ?? '');
            if (! empty($fresh['refresh_token'])) {
                $record->refresh_token = (string) $fresh['refresh_token'];
            }
            $record->token_expires_at = isset($fresh['expires_in']) ? now()->addSeconds((int) $fresh['expires_in']) : now()->addHour();
            $record->save();

            $client->setAccessToken([
                'access_token' => (string) $record->access_token,
                'refresh_token' => (string) $record->refresh_token,
                'expires_in' => isset($fresh['expires_in']) ? (int) $fresh['expires_in'] : 3600,
                'created' => now()->timestamp,
            ]);
        }

        return $client;
    }
}
