<?php

declare(strict_types=1);

namespace Modules\UI\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
<<<<<<< HEAD
<<<<<<< .merge_file_1FfZg4
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget as BaseWidget;
=======
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;
>>>>>>> .merge_file_ArqXWU
=======
>>>>>>> f5ffd8b4 (.)

class HeroWidget extends BaseWidget
{
    protected ?string $heading = 'Hero Widget';

    protected string $title = '';

    protected string $icon = '';

    public function getColumns(): int
    {
        return 8;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('', $this->title)->icon($this->icon),
        ];
    }
}
