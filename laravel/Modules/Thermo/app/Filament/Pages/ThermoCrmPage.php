<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoCrmPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'UI-02 - CRM e impianti';

    protected static ?string $title = 'UI-02 - CRM, clienti, impianti e documenti';

    protected static ?string $slug = 'crm-systems';

    protected static ?int $navigationSort = 2;

    protected static ?string $thermoFeature = 'thermo.crm';

    protected string $view = 'thermo::filament.pages.crm-systems';
}
