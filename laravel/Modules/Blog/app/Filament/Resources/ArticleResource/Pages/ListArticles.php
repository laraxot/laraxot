<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Modules\Blog\Filament\Resources\ArticleResource\Pages\Actions\ArticleImportActionFactory;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListArticles extends XotBaseListRecords
{
    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
            'import' => ArticleImportActionFactory::make(),
        ];
    }
}
