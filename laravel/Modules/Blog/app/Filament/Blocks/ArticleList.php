<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Xot\Actions\View\GetViewsSiblingsAndSelfAction;

class ArticleList
{
    public static function make(
        string $name = 'article_list',
        string $context = 'form',
    ): Block {
        $view = 'blog::components.blocks.article_list.v1';
        $views = app(GetViewsSiblingsAndSelfAction::class)->execute($view);

        return Block::make($name)
            ->schema([
                // Select::make('article_id')
                //    ->label('Article')
                //    ->options(Article::published()->orderBy('title')->pluck('title', 'id'))
                //    ->required(),
                // TextInput::make('text')
                //    ->label('Link text (optional)'),
                TextInput::make('title')
                    ->label('Titolo')
                    ->helperText('Inserisci un titolo del blocco articoli')
                    ->required(),
                TextInput::make('sub_title')
                    ->label('Sotto Titolo')
                    ->helperText('Inserisci un sotto titolo del blocco articoli'),
                TextInput::make('method')
                    ->label('$_theme->{$method}')
                    ->hint('Inserisci il nome del metodo da richiamare nel tema')
                    ->required(),
                /*
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'latest' => 'latest',
                        'featured' => 'featured',
                    ])
                    ->required(),
                */
                TextInput::make('limit'),
                Select::make('_tpl')
                    ->label('layout')
                    ->options($views)
                    ->default('v1')
                    ->required(),
            ])
            ->label('Lista Articoli')
            ->columns('form' === $context ? 3 : 1);
    }
}
