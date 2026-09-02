<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;

class Chart
{
    public static function make(
        // string $name = 'ratings_statistics_graph',
        string $name = 'chart', // nome e' lo snake della classe
        string $context = 'form',
    ): Block {
        $view = 'blog::components.blocks.chart.v1';
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        return Block::make($name)
            ->schema([
                // TextInput::make('text')
                //     ->required(),

                Select::make('chart_type')
                    // ->options([
                    //     'line' => 'Line Chart',
                    //     // 'h3' => 'h3',
                    //     // 'h4' => 'h4',
                    // ])
                    ->options($views)
                    ->columnSpanFull(),
                // ->afterStateHydrated(static fn ($state, $set) => $state || $set('level', 'h2')),
            ])
            ->columns('form' === $context ? 2 : 1);
    }
}
