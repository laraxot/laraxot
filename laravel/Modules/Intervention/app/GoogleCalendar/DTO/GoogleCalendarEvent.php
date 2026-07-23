<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\DTO;

use Carbon\CarbonInterface;

/**
 * A Google Calendar event as read back from the API. Immutable.
 */
final readonly class GoogleCalendarEvent
{
    public function __construct(
        public string $id,
        public string $summary,
        public ?string $description,
        public ?string $location,
        public ?CarbonInterface $startsAt,
        public ?CarbonInterface $endsAt,
        public ?CarbonInterface $updatedAt,
    ) {}
}
