<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources\CommentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Comment\Filament\Resources\CommentResource\Tables\CommentsTable;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListComments extends XotBaseListRecords
{
    protected static string $resource = CommentResource::class;

    /**
     * @return array<string, Column>
     */
    #[Override]
    public function getTableColumns(): array
    {
        return (new CommentsTable)->getTableColumns();
    }

    /**
     * @return array<string, BaseFilter>
     */
    #[Override]
    public function getTableFilters(): array
    {
        return (new CommentsTable)->getTableFilters();
    }

    /**
     * @return array<string, Action>
     */
    #[Override]
    public function getTableActions(): array
    {
        return (new CommentsTable)->getTableActions();
    }

    /**
     * @return array<string, BulkAction|BulkActionGroup>
     */
    #[Override]
    public function getTableBulkActions(): array
    {
        return (new CommentsTable)->getTableBulkActions();
    }
}
