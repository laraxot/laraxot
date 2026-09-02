<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

final class EnergyBrokerDashboard extends XotBaseDashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice Energy Broker';

    protected static ?string $title = 'Mockup CRM/Gestionale broker luce gas';

    protected static ?int $navigationSort = 0;

    protected string $view = 'energybroker::filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'energy-broker')
            && AccountFeatures::hasFeature(Auth::user(), 'energy_broker.dashboard', true);
    }
}
