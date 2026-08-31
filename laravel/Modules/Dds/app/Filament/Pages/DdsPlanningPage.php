<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

final class DdsPlanningPage extends DdsPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'UI-03 - Planning interventi';

    protected static ?string $title = 'UI-03 - Planning interventi e calendario';

    protected static ?string $slug = 'planning';

    protected static ?int $navigationSort = 3;

    protected static ?string $ddsFeature = 'dds.planning';

    protected string $view = 'dds::filament.pages.planning';
}
