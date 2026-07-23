<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages\Support;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Modules\Blog\Models\Category;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;

final class ArticleListTableConfig
{
    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public static function columns(): array
    {
        return [
            'id' => TextColumn::make('id'),
            'title' => TextColumn::make('title')
                ->wrap()
                ->sortable()
                ->searchable(),
            'category_title' => TextColumn::make('category.title')
                ->sortable()
                ->searchable(),
            'published_at' => TextColumn::make('published_at')
                ->dateTime()
                ->sortable(),
            'closed_at' => TextColumn::make('closed_at')
                ->dateTime()
                ->sortable(),
            'rewarded_at' => TextColumn::make('rewarded_at')
                ->dateTime()
                ->sortable(),
            'is_featured' => IconColumn::make('is_featured')
                ->boolean()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, Filter|SelectFilter>
     */
    public static function filters(): array
    {
        /** @var array<array<string>|string> $categoryOptions */
        $categoryOptions = SafeArrayCastAction::cast(Category::getTreeCategoryOptions());

        return [
            'is_featured' => Filter::make('is_featured')->toggle(),
            'category' => SelectFilter::make('Categoria')
                ->options($categoryOptions)
                ->attribute('category_id'),
        ];
    }
}
