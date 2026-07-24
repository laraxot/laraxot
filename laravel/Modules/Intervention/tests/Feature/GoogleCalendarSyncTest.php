<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Application\GoogleCalendar\Actions\BuildGoogleOverlayEventsAction;
use Modules\Intervention\Application\GoogleCalendar\Actions\PullGoogleCalendarChangesAction;
use Modules\Intervention\Application\GoogleCalendar\Actions\PushInterventionToGoogleAction;
use Modules\Intervention\Application\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\Application\GoogleCalendar\DTO\GoogleCalendarEvent;
use Modules\Intervention\Application\GoogleCalendar\DTO\GoogleEventInput;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\UserGoogleCalendarSetting;

/**
 * In-memory double so no test touches the Google SDK.
 */
final class FakeGoogleCalendarClient implements GoogleCalendarClient
{
    /** @var list<array{calendarId: string, eventId: string|null}> */
    public array $upserts = [];

    /** @var list<string> */
    public array $deletes = [];

    public bool $getEventNull = false;

    public ?GoogleCalendarEvent $getEventReturn = null;

    /** @var list<GoogleCalendarEvent> */
    public array $listReturn = [];

    public string $nextEventId = 'evt-new';

    public function authorizationUrl(User $user): string
    {
        return 'https://accounts.google.test/auth';
    }

    public function handleAuthorizationCode(User $user, string $code): UserGoogleCalendarSetting
    {
        return new UserGoogleCalendarSetting;
    }

    public function disconnect(User $user): void {}

    public function upsertEvent(User $user, string $calendarId, ?string $eventId, GoogleEventInput $input): string
    {
        $this->upserts[] = ['calendarId' => $calendarId, 'eventId' => $eventId];

        return $eventId ?? $this->nextEventId;
    }

    public function deleteEvent(User $user, string $calendarId, string $eventId): void
    {
        $this->deletes[] = $eventId;
    }

    public function getEvent(User $user, string $calendarId, string $eventId): ?GoogleCalendarEvent
    {
        return $this->getEventNull ? null : $this->getEventReturn;
    }

    public function listEvents(User $user, CarbonInterface $startsAt, CarbonInterface $endsAt): array
    {
        return $this->listReturn;
    }
}

function connectGoogle(User $user, string $calendarId = 'primary'): UserGoogleCalendarSetting
{
    /** @var UserGoogleCalendarSetting $setting */
    $setting = UserGoogleCalendarSetting::query()->create([
        'user_id' => $user->id,
        'enabled' => true,
        'calendar_id' => $calendarId,
        'access_token' => 'access-tok',
        'refresh_token' => 'refresh-tok',
    ]);

    return $setting;
}

function makeSyncIntervention(User $owner, array $overrides = []): Intervention
{
    /** @var Intervention $intervention */
    $intervention = Intervention::query()->create(array_merge([
        'work_order_id' => null,
        'performed_by_user_id' => $owner->id,
        'scheduled_at' => CarbonImmutable::parse('2026-08-01 09:00:00'),
        'started_at' => null,
        'work_minutes' => 0,
        'materials_total' => 0,
    ], $overrides));

    return $intervention;
}

function useFakeGoogle(): FakeGoogleCalendarClient
{
    $fake = new FakeGoogleCalendarClient;
    app()->instance(GoogleCalendarClient::class, $fake);

    return $fake;
}

it('encrypts the stored google tokens', function (): void {
    $user = User::factory()->create();
    $setting = connectGoogle($user);

    $raw = DB::table('user_google_calendar_settings')->where('id', $setting->id)->value('access_token');
    expect($raw)->not->toBe('access-tok');           // stored encrypted
    expect($setting->fresh()->access_token)->toBe('access-tok'); // decrypted on read
    expect($setting->isConnected())->toBeTrue();
});

it('pushes a new intervention to google and stores the event id', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);
    $intervention = makeSyncIntervention($owner);

    app(PushInterventionToGoogleAction::class)->execute($intervention);

    expect($fake->upserts)->toHaveCount(1);
    expect($fake->upserts[0]['eventId'])->toBeNull();
    $intervention->refresh();
    expect($intervention->google_event_id)->toBe('evt-new');
    expect($intervention->google_sync_status)->toBe('synced');
});

it('updates the existing google event when already linked', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);
    $intervention = makeSyncIntervention($owner, ['google_event_id' => 'evt-9', 'google_sync_status' => 'synced']);

    app(PushInterventionToGoogleAction::class)->execute($intervention);

    expect($fake->upserts[0]['eventId'])->toBe('evt-9');
    expect($intervention->refresh()->google_event_id)->toBe('evt-9');
});

it('marks the intervention disabled when the owner has no connection', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create(); // no setting
    $intervention = makeSyncIntervention($owner);

    app(PushInterventionToGoogleAction::class)->execute($intervention);

    expect($fake->upserts)->toBe([]);
    expect($intervention->refresh()->google_sync_status)->toBe('disabled');
});

it('deletes the google event when mirroring an intervention deletion', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);
    $intervention = makeSyncIntervention($owner, ['google_event_id' => 'evt-del', 'google_calendar_id' => 'primary']);

    app(PushInterventionToGoogleAction::class)->deleteFromGoogle($intervention);

    expect($fake->deletes)->toBe(['evt-del']);
    expect($intervention->refresh()->google_event_id)->toBeNull();
});

it('pulls a newer google event back into the intervention schedule', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);
    $intervention = makeSyncIntervention($owner, [
        'google_event_id' => 'evt-9',
        'google_sync_status' => 'synced',
        'google_synced_at' => CarbonImmutable::parse('2026-07-01 00:00:00'),
    ]);

    $fake->getEventReturn = new GoogleCalendarEvent(
        id: 'evt-9',
        summary: 'Spostato',
        description: 'Note da Google',
        location: null,
        startsAt: CarbonImmutable::parse('2026-08-05 14:00:00'),
        endsAt: CarbonImmutable::parse('2026-08-05 15:30:00'),
        updatedAt: CarbonImmutable::parse('2026-07-20 12:00:00'),
    );

    $count = app(PullGoogleCalendarChangesAction::class)->execute();

    expect($count)->toBe(1);
    $intervention->refresh();
    expect($intervention->scheduled_at?->format('Y-m-d H:i'))->toBe('2026-08-05 14:00');
    expect($intervention->notes)->toBe('Note da Google');
});

it('marks an intervention when its google event was deleted remotely', function (): void {
    $fake = useFakeGoogle();
    $fake->getEventNull = true;
    $owner = User::factory()->create();
    connectGoogle($owner);
    $intervention = makeSyncIntervention($owner, ['google_event_id' => 'evt-gone', 'google_sync_status' => 'synced']);

    $count = app(PullGoogleCalendarChangesAction::class)->execute();

    expect($count)->toBe(1);
    expect($intervention->refresh()->google_sync_status)->toBe('deleted_external');
});

it('overlays google events on the calendar, excluding app-linked ones', function (): void {
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);
    makeSyncIntervention($owner, ['google_event_id' => 'evt-linked']);

    $fake->listReturn = [
        new GoogleCalendarEvent('gext-1', 'Riunione fornitore', null, 'Ufficio', CarbonImmutable::parse('2026-08-02 10:00'), CarbonImmutable::parse('2026-08-02 11:00'), null),
        new GoogleCalendarEvent('evt-linked', 'Intervento già sincronizzato', null, null, CarbonImmutable::parse('2026-08-01 09:00'), CarbonImmutable::parse('2026-08-01 10:00'), null),
    ];

    $overlay = app(BuildGoogleOverlayEventsAction::class)->execute(
        $owner,
        CarbonImmutable::parse('2026-08-01'),
        CarbonImmutable::parse('2026-08-31'),
    );

    expect($overlay)->toHaveCount(1);
    expect($overlay[0]['id'])->toBe('google-gext-1');
    expect($overlay[0]['title'])->toBe('[Google] Riunione fornitore');
    expect($overlay[0]['extendedProps']['source'])->toBe('google');
});

it('auto-pushes via the observer when google calendar is configured', function (): void {
    config()->set('services.google.calendar.client_id', 'test-client-id');
    $fake = useFakeGoogle();
    $owner = User::factory()->create();
    connectGoogle($owner);

    $intervention = makeSyncIntervention($owner); // create -> observer -> push

    expect($fake->upserts)->toHaveCount(1);
    expect($intervention->refresh()->google_event_id)->toBe('evt-new');
});
