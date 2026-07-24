<?php

declare(strict_types=1);

/**
 * PHPStan-only stubs for google/apiclient (optional runtime dependency).
 */

namespace Google;

class Client
{
    public function setState(string $state): void {}

    public function createAuthUrl(): string
    {
        return '';
    }

    /** @return array<string, mixed> */
    public function fetchAccessTokenWithAuthCode(string $code): array
    {
        return [];
    }

    /** @param array<string, mixed> $token */
    public function setAccessToken(array $token): void {}

    public function revokeToken(string $token): void {}

    public function isAccessTokenExpired(): bool
    {
        return false;
    }

    /** @return array<string, mixed> */
    public function fetchAccessTokenWithRefreshToken(string $refreshToken): array
    {
        return [];
    }

    public function setClientId(string $clientId): void {}

    public function setClientSecret(string $clientSecret): void {}

    public function setRedirectUri(string $uri): void {}

    public function setAccessType(string $type): void {}

    public function setPrompt(string $prompt): void {}

    public function setIncludeGrantedScopes(bool $include): void {}

    /** @param list<string> $scopes */
    public function setScopes(array $scopes): void {}
}

namespace Google\Service;

use Google\Client;

class Exception extends \Exception {}

class Oauth2
{
    public const string USERINFO_EMAIL = 'email';

    public Oauth2UserinfoResource $userinfo;

    public function __construct(Client $client)
    {
        unset($client);
        $this->userinfo = new Oauth2UserinfoResource();
    }
}

class Oauth2UserinfoResource
{
    public function get(): Oauth2Userinfo
    {
        return new Oauth2Userinfo();
    }
}

class Oauth2Userinfo
{
    public function getEmail(): ?string
    {
        return null;
    }
}

namespace Google\Service\Calendar;

use Google\Client;

class Calendar
{
    public const string CALENDAR = 'https://www.googleapis.com/auth/calendar';

    public CalendarCalendarListResource $calendarList;

    public CalendarEventsResource $events;

    public function __construct(Client $client)
    {
        unset($client);
        $this->calendarList = new CalendarCalendarListResource();
        $this->events = new CalendarEventsResource();
    }
}

class CalendarCalendarListResource
{
    public function listCalendarList(): CalendarCalendarListResponse
    {
        return new CalendarCalendarListResponse();
    }
}

class CalendarCalendarListResponse
{
    /** @return list<CalendarCalendarListEntry> */
    public function getItems(): array
    {
        return [];
    }
}

class CalendarCalendarListEntry
{
    public function getId(): ?string
    {
        return null;
    }
}

class CalendarEventsResource
{
    public function update(string $calendarId, string $eventId, Event $event): Event
    {
        return $event;
    }

    public function insert(string $calendarId, Event $event): Event
    {
        return $event;
    }

    public function delete(string $calendarId, string $eventId): void {}

    public function get(string $calendarId, string $eventId): Event
    {
        return new Event();
    }

    /**
     * @param  array<string, mixed>  $optParams
     */
    public function listEvents(string $calendarId, array $optParams = []): EventsListResponse
    {
        return new EventsListResponse();
    }
}

class EventsListResponse
{
    /** @return list<Event> */
    public function getItems(): array
    {
        return [];
    }
}

class Event
{
    public function setSummary(string $summary): void {}

    public function setDescription(?string $description): void {}

    public function setLocation(?string $location): void {}

    public function setStart(EventDateTime $start): void {}

    public function setEnd(EventDateTime $end): void {}

    public function getId(): ?string
    {
        return null;
    }

    public function getSummary(): ?string
    {
        return null;
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getLocation(): ?string
    {
        return null;
    }

    public function getStart(): ?EventDateTime
    {
        return null;
    }

    public function getEnd(): ?EventDateTime
    {
        return null;
    }

    public function getUpdated(): ?string
    {
        return null;
    }
}

class EventDateTime
{
    public function setDateTime(string $dateTime): void {}

    public function setTimeZone(string $timeZone): void {}

    public function getDateTime(): ?string
    {
        return null;
    }

    public function getDate(): ?string
    {
        return null;
    }
}
