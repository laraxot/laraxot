<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

abstract class EnergyBrokerPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static ?string $energyBrokerFeature = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! AccountFeatures::canAccessPanel($user, 'energy-broker')) {
            return false;
        }

        $feature = static::$energyBrokerFeature;

        return $feature === null
            ? true
            : AccountFeatures::hasFeature($user, $feature, false);
    }
}
