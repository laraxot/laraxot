<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class Search
{
    public static function make(
        string $name = 'search',
        string $context = 'form',
    ): Block {
        return Block::make($name)
            ->schema([
                TextInput::make('limit'),
                Select::make('layout')
                    ->label('layout')
                    ->options([
                        'v1' => 'layout 1 (Bootstrap)',
                    ])
                    ->default('v1')
                    ->required(),
            ])
            ->label('Pagina Search')
            ->columns('form' === $context ? 3 : 1);
    }
}
