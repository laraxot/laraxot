<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;

class Setting
{
    public static function make(
        string $name = 'setting',
        string $context = 'form',
    ): Block {
        return Block::make($name)
            ->schema([
                Select::make('version')
                    ->label('version')
                    ->options([
                        'v1' => 'versione 1 (Bootstrap)',
                        // 'v2' => 'versione 2 (Bootstrap)',
                        // 'v3' => 'versione 3',
                        // 'v4' => 'versione 4',
                        // 'v5' => 'versione 5',
                    ])
                    ->required(),
            ])
            ->label('setting')
            ->columns('form' === $context ? 3 : 1);
    }
}
