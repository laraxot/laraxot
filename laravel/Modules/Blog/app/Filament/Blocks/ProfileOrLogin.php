<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;

class ProfileOrLogin
{
    public static function make(
        string $name = 'profile_or_login',
        string $context = 'form',
    ): Block {
        $view = 'blog::components.blocks.profile_or_login.v1';
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        return Block::make($name)
            ->schema([
                // TextInput::make('title')
                // ->label('Titolo')
                // ->helperText('Inserisci un titolo del blocco articoli')
                // ->required(),
                // TextInput::make('sub_title')
                //     ->label('Sotto Titolo')
                //     ->helperText('Inserisci un sotto titolo del blocco articoli'),
                // TextInput::make('method')
                //         ->label('$_theme->{$method}')
                //         ->hint('Inserisci il nome del metodo da richiamare nel tema')
                //         ->required(),
                Select::make('_tpl')
                    ->label('layout')
                    ->options($views)
                    ->default('v1')
                    ->required(),
            ])
            ->label('Profilo/Login')
            ->columns('form' === $context ? 3 : 1);
    }
}
