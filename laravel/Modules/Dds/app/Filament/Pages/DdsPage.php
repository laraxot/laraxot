<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

abstract class DdsPage extends XotBasePage
{
    use GatesPageNavigation;

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
