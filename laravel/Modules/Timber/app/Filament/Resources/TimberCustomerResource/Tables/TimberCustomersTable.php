<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberCustomersTable extends XotBaseResourceTable
{
    protected static string $resource = TimberCustomerResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'business_name' => TextColumn::make('business_name')->searchable()->sortable(),
            'city' => TextColumn::make('city')->searchable()->toggleable(),
            'province' => TextColumn::make('province')->toggleable(),
            'contact_person' => TextColumn::make('contact_person')->toggleable(),
            'phone' => TextColumn::make('phone')->toggleable(),
            'email' => TextColumn::make('email')->searchable()->toggleable(),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => SelectFilter::make('is_active')->options(['1' => 'Attivo', '0' => 'Inattivo']),
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
