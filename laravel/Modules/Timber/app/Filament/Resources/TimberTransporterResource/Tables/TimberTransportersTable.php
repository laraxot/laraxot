<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberTransportersTable extends XotBaseResourceTable
{
    protected static string $resource = TimberTransporterResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'legal_name' => TextColumn::make('legal_name')->searchable()->sortable(),
            'vat_number' => TextColumn::make('vat_number')->searchable()->toggleable(),
            'phone' => TextColumn::make('phone')->toggleable(),
            'email' => TextColumn::make('email')->toggleable(),
            'vehicle_plate' => TextColumn::make('vehicle_plate')->toggleable(),
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
