<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Filament\Widgets\Widget;
use Modules\Employee\Filament\Resources\WorkHourResource\Pages;
use Modules\Employee\Filament\Resources\WorkHourResource\Schemas\WorkHourForm;
use Modules\Employee\Filament\Resources\WorkHourResource\Schemas\WorkHourInfolist;
use Modules\Employee\Models\WorkHour;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class WorkHourResource extends XotBaseResource
{
    protected static ?string $model = WorkHour::class;

    public static function getPages(): array
    {
        return array_merge(parent::getPages(), [
            'time-clock' => Pages\TimeClockPage::route('/time-clock'),
        ]);
    }

    #[Override]
    public static function getFormSchema(): array
    {
        return WorkHourForm::getFormSchema();
    }

    #[Override]
    public static function getInfolistSchema(): array
    {
        return WorkHourInfolist::getInfolistSchema();
    }

    /**
     * @return array<class-string<Widget>>
     */
    public static function getHeaderWidgets(): array
    {
        return [];
    }
}
