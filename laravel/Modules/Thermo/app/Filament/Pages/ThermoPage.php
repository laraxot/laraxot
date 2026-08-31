<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

abstract class ThermoPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static ?string $thermoFeature = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! AccountFeatures::canAccessPanel($user, 'thermo')) {
            return false;
        }

        $feature = static::$thermoFeature;

        return $feature === null
            ? true
            : AccountFeatures::hasFeature($user, $feature, false);
    }
}
