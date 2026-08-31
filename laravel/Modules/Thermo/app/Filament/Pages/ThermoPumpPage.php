<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoPumpPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected static ?string $navigationLabel = 'UI-07 - Pompa Bluetooth';

    protected static ?string $title = 'UI-07 - Pompa Bluetooth e cicli anticalcare';

    protected static ?string $slug = 'bluetooth-pump';

    protected static ?int $navigationSort = 7;

    protected static ?string $thermoFeature = 'thermo.bluetooth_pump';

    protected string $view = 'thermo::filament.pages.bluetooth-pump';
}
