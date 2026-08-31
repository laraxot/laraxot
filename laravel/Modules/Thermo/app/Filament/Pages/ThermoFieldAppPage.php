<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoFieldAppPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $navigationLabel = 'UI-05 - App tecnico';

    protected static ?string $title = 'UI-05 - App tecnico, SAL e rapportino';

    protected static ?string $slug = 'field-app';

    protected static ?int $navigationSort = 5;

    protected static ?string $thermoFeature = 'thermo.field_app';

    protected string $view = 'thermo::filament.pages.field-app';
}
