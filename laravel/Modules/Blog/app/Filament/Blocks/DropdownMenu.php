<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DropdownMenu
{
    public static function make(
        string $name = 'dropdown_menu',
    ): Block {
        return Block::make($name)
            ->schema([
                Repeater::make('menu')
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        Select::make('type')
                            ->label('Cosa vuoi visualizzare')
                            ->options([
                                'categories' => 'Categorie',
                            ])
                            ->required(),
                        Select::make('layout')
                            ->label('layout')
                            ->options([
                                'v1' => 'layout 1 (Bootstrap)',
                            ])
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }
}
