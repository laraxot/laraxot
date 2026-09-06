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
            $errorValue = $token['error'];
            throw new RuntimeException('Google OAuth error: '.(string) (is_string($errorValue) ? $errorValue : ''));
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
        $accessTokenValue = $token['access_token'] ?? '';
        $record->access_token = (string) (is_string($accessTokenValue) ? $accessTokenValue : '');
        $refreshTokenValue = $token['refresh_token'] ?? '';
        $refresh = (string) (is_string($refreshTokenValue) ? $refreshTokenValue : '');
        if ($refresh !== '') {
            $record->refresh_token = $refresh;
        }
        $expiresInValue = $token['expires_in'] ?? null;
        $record->token_expires_at = isset($expiresInValue) && is_numeric($expiresInValue) ? now()->addSeconds((int) $expiresInValue) : null;
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
        $clientIdValue = config('services.google.calendar.client_id');
        $clientId = (string) (is_string($clientIdValue) ? $clientIdValue : '');
        $clientSecretValue = config('services.google.calendar.client_secret');
        $clientSecret = (string) (is_string($clientSecretValue) ? $clientSecretValue : '');

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
        $accessTokenValue = $record->access_token;
        $refreshTokenValue = $record->refresh_token;
        $client->setAccessToken([
            'access_token' => (string) (is_string($accessTokenValue) ? $accessTokenValue : ''),
            'refresh_token' => (string) (is_string($refreshTokenValue) ? $refreshTokenValue : ''),
            'expires_in' => max(1, (int) now()->diffInSeconds($record->token_expires_at ?? now()->subSecond(), false)),
            'created' => now()->timestamp,
        ]);

        if ($client->isAccessTokenExpired()) {
            $refreshValue = $record->refresh_token;
            $refresh = trim((string) (is_string($refreshValue) ? $refreshValue : ''));
            if ($refresh === '') {
                throw new RuntimeException('Connessione Google Calendar scaduta: ricollega l\'account.');
            }

            $fresh = $client->fetchAccessTokenWithRefreshToken($refresh);
            if (isset($fresh['error'])) {
                $record->forceFill(['last_sync_error_at' => now()])->save();
                $errorValue = $fresh['error'];
                throw new RuntimeException('Refresh token Google non valido: '.(string) (is_string($errorValue) ? $errorValue : ''));
            }

            $freshAccessTokenValue = $fresh['access_token'] ?? '';
            $record->access_token = (string) (is_string($freshAccessTokenValue) ? $freshAccessTokenValue : '');
            if (! empty($fresh['refresh_token'])) {
                $freshRefreshTokenValue = $fresh['refresh_token'];
                $record->refresh_token = (string) (is_string($freshRefreshTokenValue) ? $freshRefreshTokenValue : '');
            }
            $freshExpiresInValue = $fresh['expires_in'] ?? null;
            $record->token_expires_at = isset($freshExpiresInValue) && is_numeric($freshExpiresInValue) ? now()->addSeconds((int) $freshExpiresInValue) : now()->addHour();
            $record->save();

            $client->setAccessToken([
                'access_token' => (string) (is_string($record->access_token) ? $record->access_token : ''),
                'refresh_token' => (string) (is_string($record->refresh_token) ? $record->refresh_token : ''),
                'expires_in' => isset($freshExpiresInValue) && is_numeric($freshExpiresInValue) ? (int) $freshExpiresInValue : 3600,
                'created' => now()->timestamp,
            ]);
        }

        return $client;
    }
}
