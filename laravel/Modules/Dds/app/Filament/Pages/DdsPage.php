<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBasePage;

abstract class DdsPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static ?string $ddsFeature = null;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! AccountFeatures::canAccessPanel($user, 'dds')) {
            return false;
        }

        $feature = static::$ddsFeature;

        return $feature === null
            ? true
            : AccountFeatures::hasFeature($user, $feature, false);
    }
}
