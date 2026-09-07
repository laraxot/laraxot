<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberSupplierResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberSuppliersTable extends XotBaseResourceTable
{
    protected static string $resource = TimberSupplierResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'legal_name' => TextColumn::make('legal_name')->searchable()->sortable(),
            'vat_number' => TextColumn::make('vat_number')->searchable()->toggleable(),
            'city' => TextColumn::make('city')->searchable()->toggleable(),
            'province' => TextColumn::make('province')->toggleable(),
            'certifications' => TextColumn::make('certifications')->toggleable(),
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
