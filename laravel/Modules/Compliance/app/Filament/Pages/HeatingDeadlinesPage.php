<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Queries\GetHeatingComplianceOverviewQuery;
use Modules\User\Models\User;

/**
 * Cross-customer overview of heating maintenance / RCEE deadlines, worst first,
 * so overdue and expiring controls are visible at a glance.
 */
final class HeatingDeadlinesPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $slug = 'heating-deadlines';

    protected string $view = 'compliance::filament.pages.heating-deadlines';

    public static function canAccess(): bool
    {
        return self::isEnabledForCurrentPanel();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isEnabledForCurrentPanel();
    }

    private static function isEnabledForCurrentPanel(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return match (filament()->getCurrentPanel()?->getId()) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.heating_maintenance', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.heating_maintenance', true),
            default => false,
        };
    }

    public static function getNavigationGroup(): ?string
    {
        return __('heating.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('heating.deadlines.title');
    }

    public function getTitle(): string
    {
        return __('heating.deadlines.title');
    }

    public function getHeading(): string
    {
        return __('heating.deadlines.title');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function rows(): array
    {
        return array_map(
            static fn ($row): array => $row->toArray(),
            app(GetHeatingComplianceOverviewQuery::class)->execute(),
        );
    }
}
