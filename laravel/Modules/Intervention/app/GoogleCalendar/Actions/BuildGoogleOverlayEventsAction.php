<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\Actions;

use App\Models\User;
use Carbon\CarbonInterface;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\UserGoogleCalendarSetting;
use Throwable;

/**
 * Builds FullCalendar events for the Google Calendar entries of a user, so they
 * appear inside the app calendar. Events already linked to an intervention (i.e.
 * pushed by the app) are skipped to avoid showing them twice.
 */
final class BuildGoogleOverlayEventsAction
{
    public function __construct(
        private readonly GoogleCalendarClient $client,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function execute(User $user, CarbonInterface $startsAt, CarbonInterface $endsAt): array
    {
        /** @var UserGoogleCalendarSetting|null $setting */
        $setting = UserGoogleCalendarSetting::query()->where('user_id', $user->id)->first();
        if ($setting === null || ! $setting->isConnected()) {
            return [];
        }

        try {
            $events = $this->client->listEvents($user, $startsAt, $endsAt);
        } catch (Throwable) {
            return [];
        }

        $linked = [];
        foreach (
            Intervention::query()
                ->where('performed_by_user_id', $user->id)
                ->whereNotNull('google_event_id')
                ->pluck('google_event_id') as $id
        ) {
            if (is_scalar($id)) {
                $linked[(string) $id] = true;
            }
        }

        $out = [];
        foreach ($events as $event) {
            if ($event->startsAt === null || isset($linked[$event->id])) {
                continue;
            }

            $out[] = [
                'id' => 'google-'.$event->id,
                'title' => '[Google] '.$event->summary,
                'start' => $event->startsAt->toIso8601String(),
                'end' => $event->endsAt?->toIso8601String(),
                'backgroundColor' => '#6b7280',
                'borderColor' => '#4b5563',
                'textColor' => '#ffffff',
                'editable' => false,
                'extendedProps' => [
                    'source' => 'google',
                    'operator_id' => 0,
                    'customer' => $event->summary,
                    'site' => $event->location ?? '',
                    'work_order_title' => '',
                    'notes' => $event->description ?? '',
                ],
            ];
        }

        return $out;
    }
}
