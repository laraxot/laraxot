<?php

declare(strict_types=1);

namespace Modules\Dds\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

final class DdsDashboard extends Dashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice DDS';

    protected static ?string $title = 'Indice DDS e riferimenti UI';

    protected static ?int $navigationSort = 0;

    protected string $view = 'dds::filament.pages.dashboard';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return AccountFeatures::canAccessPanel($user, 'dds')
            && AccountFeatures::hasFeature($user, 'dds.dashboard', true);
    }
}
