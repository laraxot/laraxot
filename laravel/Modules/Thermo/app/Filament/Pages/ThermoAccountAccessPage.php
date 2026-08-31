<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

final class ThermoAccountAccessPage extends ThermoPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'UI-03 - Accessi account';

    protected static ?string $title = 'UI-03 - Accessi account, registrazione e MFA';

    protected static ?string $slug = 'account-access';

    protected static ?int $navigationSort = 3;

    protected static ?string $thermoFeature = 'thermo.account_access';

    protected string $view = 'thermo::filament.pages.account-access';
}
