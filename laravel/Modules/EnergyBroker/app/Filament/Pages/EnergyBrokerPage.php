<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBasePage;

abstract class EnergyBrokerPage extends XotBasePage
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
