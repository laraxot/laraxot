<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Employee\Filament\Widgets\TimeClockWidget;
use Modules\Employee\Filament\Widgets\WorkHoursBoardWidget;
use Modules\Xot\Filament\Pages\XotBasePage;

class WorkHoursPage extends XotBasePage
{
    protected string $view = 'employee::filament.pages.work-hours';

    /**
     * Header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            TimeClockWidget::class,
        ];
    }

    /**
     * Footer widgets.
     */
    protected function getFooterWidgets(): array
    {
        return [
            WorkHoursBoardWidget::class,
        ];
    }
}
