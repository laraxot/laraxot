<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SubcontractorsTable extends XotBaseResourceTable
{
    protected static string $resource = SubcontractorResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'business_name' => TextColumn::make('business_name')->searchable()->sortable(),
            'vat_number' => TextColumn::make('vat_number')->toggleable(),
            'contact_name' => TextColumn::make('contact_name')->toggleable(),
            'email' => TextColumn::make('email')->toggleable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
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
