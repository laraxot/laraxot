<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Str;
use Modules\Blog\Filament\Resources\CategoryResource;
use Modules\Blog\Models\Category;
use Webmozart\Assert\Assert;

final class ArticleFormMainGridSchema
{
    public static function build(): Grid
    {
        return Grid::make()->columns(2)->schema([
            TextInput::make('title')
                ->columnSpan(1)
                ->required()
                ->lazy()
                ->afterStateUpdated(static function ($set, $get, $state): void {
                    Assert::isCallable($set, 'set must be callable');
                    Assert::isCallable($get, 'get must be callable');

                    if ($get('slug')) {
                        return;
                    }
                    $set('slug', Str::slug((string) $state));
                }),

            TextInput::make('slug')
                ->columnSpan(1)
                ->required(),
            DateTimePicker::make('closed_at')
                ->columnSpan(1)
                ->helperText('Determina fino a quando è possibile visualizzare l\'articolo')
                ->required(),
            DateTimePicker::make('published_at')
                ->columnSpan(1)
                ->nullable(),
            Select::make('category_id')
                ->required()
                ->options(static fn (): array => Category::getTreeCategoryOptions())
                ->createOptionForm(static fn (): array => CategoryResource::getFormFields())
                ->createOptionUsing(static function (array $data): mixed {
                    $categoryData = [];
                    foreach ($data as $key => $value) {
                        $categoryData[(string) $key] = $value;
                    }

                    return Category::create($categoryData)->getKey();
                }),
            Toggle::make('is_featured')
                ->columnSpanFull(),
        ]);
    }
}
