<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Concerns;

use Illuminate\Support\Str;

use function Safe\parse_url;

trait ResolvesCalendarReturnUrl
{
    /**
     * @var array<int, string>
     */
    private const ALLOWED_CALENDAR_VIEWS = [
        'dayGridMonth',
        'timeGridWeek',
        'timeGridDay',
        'listWeek',
    ];

    protected function getRequestedReturnUrl(): ?string
    {
        $requestedReturnUrl = request()->query('return_to');

        return is_string($requestedReturnUrl)
            ? $this->sanitizeReturnUrl($requestedReturnUrl)
            : null;
    }

    protected function buildCalendarReturnUrl(?string $calendarView = null, ?string $calendarDate = null): string
    {
        $panelPrefix = match (filament()->getCurrentPanel()?->getId()) {
            'admin' => '/admin',
            'operator' => '/operator',
            default => request()->is('admin/*') ? '/admin' : '/operator',
        };

        $query = array_filter([
            'calendar_view' => $this->sanitizeCalendarView($calendarView),
            'calendar_date' => $this->sanitizeCalendarDate($calendarDate),
        ]);

        $targetUrl = url($panelPrefix.'/intervention-calendar');

        if ($query === []) {
            return $targetUrl;
        }

        return $targetUrl.'?'.http_build_query($query);
    }

    private function sanitizeReturnUrl(string $returnUrl): ?string
    {
        $path = parse_url($returnUrl, PHP_URL_PATH);
        $query = parse_url($returnUrl, PHP_URL_QUERY);

        if (! is_string($path) || ! in_array($path, ['/admin/intervention-calendar', '/operator/intervention-calendar'], true)) {
            return null;
        }

        $sanitizedQuery = [];
        if (is_string($query) && $query !== '') {
            parse_str($query, $queryParams);

            $calendarView = is_string($queryParams['calendar_view'] ?? null)
                ? $this->sanitizeCalendarView($queryParams['calendar_view'])
                : null;

            $calendarDate = is_string($queryParams['calendar_date'] ?? null)
                ? $this->sanitizeCalendarDate($queryParams['calendar_date'])
                : null;

            $sanitizedQuery = array_filter([
                'calendar_view' => $calendarView,
                'calendar_date' => $calendarDate,
            ]);
        }

        $safeUrl = url($path);

        return $sanitizedQuery === []
            ? $safeUrl
            : $safeUrl.'?'.http_build_query($sanitizedQuery);
    }

    private function sanitizeCalendarView(?string $calendarView): ?string
    {
        if ($calendarView === null || trim($calendarView) === '') {
            return null;
        }

        return in_array($calendarView, self::ALLOWED_CALENDAR_VIEWS, true)
            ? $calendarView
            : null;
    }

    private function sanitizeCalendarDate(?string $calendarDate): ?string
    {
        if ($calendarDate === null || trim($calendarDate) === '') {
            return null;
        }

        return Str::match('/^\d{4}-\d{2}-\d{2}$/', $calendarDate) !== ''
            ? $calendarDate
            : null;
    }
}
