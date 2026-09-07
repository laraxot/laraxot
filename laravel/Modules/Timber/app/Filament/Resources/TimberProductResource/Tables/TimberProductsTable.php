<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberProductsTable extends XotBaseResourceTable
{
    protected static string $resource = TimberProductResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'description' => TextColumn::make('description')->searchable()->wrap()->sortable(),
            'category' => TextColumn::make('category')->searchable()->toggleable(),
            'unit_of_measure' => TextColumn::make('unit_of_measure'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
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
