<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

abstract class GivebackPlatformPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static ?string $givebackFeature = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! AccountFeatures::canAccessPanel($user, 'giveback-platform')) {
            return false;
        }

        $feature = static::$givebackFeature;

        return $feature === null
            ? true
            : AccountFeatures::hasFeature($user, $feature, false);
    }
}
