<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

abstract class AsdSociPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static bool $isDiscovered = false;

    protected static ?string $asdFeature = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! AccountFeatures::canAccessPanel($user, 'asd-soci')) {
            return false;
        }

        $feature = static::$asdFeature;

        return $feature === null
            ? true
            : AccountFeatures::hasFeature($user, $feature, false);
    }
}
