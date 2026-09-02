<?php

declare(strict_types=1);

namespace Modules\AsdSoci\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

final class AsdSociDashboard extends XotBaseDashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice ASD Soci';

    protected static ?string $title = 'Indice mockup ASD Soci Ramdivision';

    protected static ?int $navigationSort = 0;

    protected string $view = 'asdsoci::filament.pages.dashboard';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return AccountFeatures::canAccessPanel($user, 'asd-soci')
            && AccountFeatures::hasFeature($user, 'asd.dashboard', true);
    }
}
