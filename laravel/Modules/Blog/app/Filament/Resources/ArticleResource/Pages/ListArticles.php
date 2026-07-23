<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ArticleResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\Column;
use Modules\Blog\Filament\Resources\ArticleResource\Pages\Support\ArticleImportActionFactory;
use Modules\Blog\Filament\Resources\ArticleResource\Pages\Support\ArticleListTableConfig;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListArticles extends XotBaseListRecords
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return ArticleListTableConfig::columns();
    }

    /**
     * @return array<string, mixed>
     */
    public function getTableFilters(): array
    {
        return ArticleListTableConfig::filters();
    }

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
