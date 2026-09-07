<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Signage\Filament\Resources\SignPriceListResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SignPriceListsTable extends XotBaseResourceTable
{
    protected static string $resource = SignPriceListResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable(),
            'brand' => TextColumn::make('brand')->placeholder('—')->searchable(),
            'items_count' => TextColumn::make('items_count')
                ->counts('items'),
            'default_markup_percent' => TextColumn::make('default_markup_percent')
                ->placeholder('—')
                ->suffix('%'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, EditAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
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

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }
}
