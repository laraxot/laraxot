<?php

declare(strict_types=1);

namespace Modules\Intervention\GoogleCalendar\DTO;

use Carbon\CarbonInterface;

/**
 * The payload pushed to Google Calendar for one intervention. Immutable.
 */
final readonly class GoogleEventInput
{
    public function __construct(
        public string $summary,
        public ?string $description,
        public ?string $location,
        public CarbonInterface $startsAt,
        public CarbonInterface $endsAt,
        public string $timeZone,
    ) {}
}
