<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\Section;
use Modules\Blog\Filament\Fields\ArticleContent;
use Modules\Blog\Filament\Fields\ArticleFooter;
use Modules\Blog\Filament\Fields\ArticleSidebar;

final class ArticleFormSectionsSchema
{
    /**
     * @return array<int, SchemaComponent>
     */
    public static function build(): array
    {
        return [
            Section::make('Article Content')->schema([
                Actions::make([])->columnSpanFull()->alignRight(),
                ArticleContent::make('content_blocks')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull(),
            ])->collapsible(),
            Section::make('Article Sidebar')->schema([
                Actions::make([])->columnSpanFull()->alignRight(),
                ArticleSidebar::make('sidebar_blocks')
                    ->label('Sidebar')
                    ->columnSpanFull(),
            ])->collapsible(),
            Section::make('Article Footer')->schema([
                Actions::make([])->columnSpanFull()->alignRight(),
                ArticleFooter::make('footer_blocks')
                    ->label('Footer')
                    ->columnSpanFull(),
            ])->collapsible(),
            TextInput::make('main_image_url')
                ->label('Main image URL')
                ->columnSpanFull(),
            SpatieMediaLibraryFileUpload::make('main_image_upload')
                ->openable()
                ->downloadable()
                ->columnSpanFull()
                ->disk('uploads')
                ->directory('photos')
                ->collection('main_image_upload'),
        ];
    }
}
