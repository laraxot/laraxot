<?php

/**
 * @see https://medium.com/@laravelprotips/filament-streamline-multiple-widgets-with-one-dynamic-livewire-filter-ed05c978a97f
 */

declare(strict_types=1);

namespace Modules\User\Filament\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Modules\User\Filament\Widgets\RecentLoginsWidget;
use Modules\User\Filament\Widgets\UsersChartWidget;
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Filament\Widgets\UsersChartWidget;
use Modules\User\Filament\Widgets\RecentLoginsWidget;
use Override;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseBashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Modules\User\Filament\Widgets;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Modules\User\Filament\Widgets\RecentLoginsWidget;
use Modules\User\Filament\Widgets\UsersChartWidget;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Filament\Pages\XotBaseDashboard;

class Dashboard extends XotBaseDashboard
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

>>>>>>> f548be94 (.)
=======
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    // protected static string $routePath = 'finance';
    // protected static ?string $title = 'Finance dashboard';
    // protected static ?int $navigationSort = 15;

    // protected static string $view = 'user::filament.pages.dashboard';

    /**
     * @return array<class-string<Widget>|WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            UsersChartWidget::make(['chart_id' => 'bb']),
            // Widgets\UsersChartWidget::make(['chart_id' => 'aa']),
            RecentLoginsWidget::class,
        ];
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    #[Override]
    public function getFiltersFormSchema(): array
    {
        return [
            DatePicker::make('startDate')->native(false),
            // ->maxDate(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('endDate') ?: now()),
            DatePicker::make('endDate')->native(false),
            // ->minDate(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('startDate') ?: now())
            // ->maxDate(now()),
        ];
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
