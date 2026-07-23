<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ArticlesTable extends XotBaseResourceTable
{
    public function getTableColumns(): array
    {
        return [
            'title' => TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->limit(50),
            'slug' => TextColumn::make('slug')
                ->limit(30),
            'main_image_url' => TextColumn::make('main_image_url')
                ->label('Image'),
            'author' => TextColumn::make('user.name')
                ->label('Author')
                ->searchable(),
            'category' => TextColumn::make('category.name')
                ->label('Category'),
            'view_count' => TextColumn::make('viewCount')
                ->sortable(),
            'is_featured' => TextColumn::make('is_featured')
                ->label('Featured')
                ->badge()
                ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            'published_at' => TextColumn::make('published_at')
                ->sortable()
                ->date(),
            'created_at' => TextColumn::make('created_at')
                ->sortable()
                ->dateTime(),
        ];
    }

    /**
     * @return array<int|string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'category' => SelectFilter::make('category')
                ->relationship('category', 'name'),
            'user' => SelectFilter::make('user')
                ->relationship('user', 'name'),
        ];
    }
}
