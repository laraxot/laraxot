<?php

declare(strict_types=1);

namespace Modules\Media\Filament\Resources\TemporaryUploadResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Media\Filament\Resources\TemporaryUploadResource;
use Modules\Media\Models\TemporaryUpload;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListTemporaryUploads extends XotBaseListRecords
{
    protected static string $resource = TemporaryUploadResource::class;

    /**
     * @return array<string, TextColumn>
     */
    

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'folder' => SelectFilter::make('folder')->options(TemporaryUpload::distinct()->pluck(
                'folder',
                'folder',
            )->toArray(...)),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
