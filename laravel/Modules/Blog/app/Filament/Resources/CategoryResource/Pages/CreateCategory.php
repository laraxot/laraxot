<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Pages;

// use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use Modules\Blog\Filament\Resources\CategoryResource;
use Modules\Lang\Filament\Resources\Pages\LangBaseCreateRecord;

class CreateCategory extends LangBaseCreateRecord
{
    public static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // LocaleSwitcher::make(), // Temporarily disabled until lara-zeus package is working
            // ...
        ];
    }
}
