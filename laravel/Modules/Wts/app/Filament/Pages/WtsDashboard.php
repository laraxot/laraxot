<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Modules\Wts\Queries\GetWtsDashboardStatsQuery;
use Filament\Pages\Dashboard;
use Illuminate\Support\Facades\Auth;

final class WtsDashboard extends Dashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice WTS';

    protected static ?string $title = 'Indice WTS e riferimenti UI';

    protected static ?int $navigationSort = 0;

    protected string $view = 'wts::filament.pages.dashboard';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    /**
     * @return array{stats: array{ingresses_today:int, units_to_process:int, processings_today:int}}
     */
    protected function getViewData(): array
    {
        return [
            'stats' => app(GetWtsDashboardStatsQuery::class)->execute(),
        ];
    }
}
