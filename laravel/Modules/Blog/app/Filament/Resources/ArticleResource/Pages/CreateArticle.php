<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages;

// use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Modules\Blog\Filament\Resources\ArticleResource;
use Modules\Lang\Filament\Resources\Pages\LangBaseCreateRecord;

class CreateArticle extends LangBaseCreateRecord
{
    public static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // LocaleSwitcher::make(), // Temporarily disabled until lara-zeus package is working
            // ...
        ];
    }
}
