<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

final class ThermoDashboard extends XotBaseDashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice Termoidraulica';

    protected static ?string $title = 'Mockup verticale termoidraulica';

    protected static ?int $navigationSort = 0;

    protected string $view = 'thermo::filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'thermo');
    }
}
