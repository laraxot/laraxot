<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberDocumentsTable extends XotBaseResourceTable
{
    protected static string $resource = TimberDocumentResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'document_date' => TextColumn::make('document_date')->date()->sortable(),
            'number' => TextColumn::make('number')->searchable()->sortable(),
            'document_type_code' => TextColumn::make('document_type_code')->badge(),
            'customer.business_name' => TextColumn::make('customer.business_name')->searchable()->toggleable(),
            'status_code' => TextColumn::make('status_code')->badge(),
            'stock_posted_at' => TextColumn::make('stock_posted_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
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
