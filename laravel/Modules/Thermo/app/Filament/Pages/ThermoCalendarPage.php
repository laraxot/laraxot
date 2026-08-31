<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoCalendarPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'UI-01 - Calendario e dispatch';

    protected static ?string $title = 'UI-01 - Calendario, urgenze e assegnazioni';

    protected static ?string $slug = 'calendar-dispatch';

    protected static ?int $navigationSort = 1;

    protected static ?string $thermoFeature = 'thermo.calendar';

    protected string $view = 'thermo::filament.pages.calendar-dispatch';
}
