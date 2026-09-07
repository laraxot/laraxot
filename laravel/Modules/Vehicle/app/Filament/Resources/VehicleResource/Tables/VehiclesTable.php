<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class VehiclesTable extends XotBaseResourceTable
{
    protected static string $resource = VehicleResource::class;

    /**
     * VehicleResource non definisce canView()/canEdit(): l'hook di default di
     * HasXotTable li richiede per popolare le azioni riga, quindi senza questo
     * override la tabella resta senza Vedi/Modifica.
     *
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
     * @return array<string, DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
            'restore' => RestoreBulkAction::make(),
            'forceDelete' => ForceDeleteBulkAction::make(),
        ];
    }

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'plate_number' => TextColumn::make('plate_number')
                ->searchable()
                ->sortable(),
            'make' => TextColumn::make('make')
                ->toggleable(),
            'model' => TextColumn::make('model')
                ->toggleable(),
            'odometer_km' => TextColumn::make('odometer_km')
                ->numeric()
                ->toggleable(),
            'insurance_expires_on' => TextColumn::make('insurance_expires_on')
                ->date()
                ->toggleable(),
            'is_active' => IconColumn::make('is_active')
                ->boolean()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
