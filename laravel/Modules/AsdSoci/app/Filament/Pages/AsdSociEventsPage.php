<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

final class AsdSociEventsPage extends AsdSociPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Eventi';

    protected static ?string $title = 'Calendario eventi e adesioni';

    protected static ?string $slug = 'events';

    protected static ?int $navigationSort = 4;

    protected static ?string $asdFeature = 'asd.events';

    protected string $view = 'asdsoci::filament.pages.events';
}
