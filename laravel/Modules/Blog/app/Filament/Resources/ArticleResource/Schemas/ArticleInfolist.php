<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class ArticleInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'title' => TextEntry::make('title'),
            'slug' => TextEntry::make('slug'),
            'body' => TextEntry::make('body')
                ->html()
                ->limit(200),
            'main_image_url' => ImageEntry::make('main_image_url')
                ->label('Image'),
            'author' => TextEntry::make('user.name')
                ->label('Author'),
            'category' => TextEntry::make('category.name')
                ->label('Category'),
            'viewCount' => TextEntry::make('viewCount'),
            'is_featured' => TextEntry::make('is_featured')
                ->badge()
                ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            'published_at' => TextEntry::make('published_at')->date(),
            'closed_at' => TextEntry::make('closed_at')->date(),
            'type' => TextEntry::make('type'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
