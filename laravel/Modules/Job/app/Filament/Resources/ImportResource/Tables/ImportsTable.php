<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Resources\ImportResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Job\Models\Import;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ImportsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Import>
     */
    protected static string $model = Import::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'file_name' => TextColumn::make('file_name')->searchable()->sortable()->wrap(),
            'processed_rows' => TextColumn::make('processed_rows')->numeric()->sortable(),
            'total_rows' => TextColumn::make('total_rows')->numeric()->sortable(),
            'successful_rows' => TextColumn::make('successful_rows')->numeric()->sortable(),
            'completed_at' => TextColumn::make('completed_at')->dateTime()->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
        ];
    }

    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
