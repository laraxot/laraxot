<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBasePage;

abstract class GivebackPlatformPage extends XotBasePage
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
