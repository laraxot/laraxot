<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberCustomers extends XotBaseListRecords
{
    protected static string $resource = TimberCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'code' => Tables\Columns\TextColumn::make('code')
                ->searchable()
                ->sortable()
                ->toggleable(),
            'business_name' => Tables\Columns\TextColumn::make('business_name')
                ->searchable()
                ->sortable(),
            'vat_number' => Tables\Columns\TextColumn::make('vat_number')
                ->searchable()
                ->toggleable(),
            'city' => Tables\Columns\TextColumn::make('city')
                ->toggleable(),
            'is_customer' => Tables\Columns\IconColumn::make('is_customer')
                ->boolean()
                ->toggleable(),
            'is_supplier' => Tables\Columns\IconColumn::make('is_supplier')
                ->boolean()
                ->toggleable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->boolean(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'is_customer' => Tables\Filters\TernaryFilter::make('is_customer'),
            'is_supplier' => Tables\Filters\TernaryFilter::make('is_supplier'),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'printsheet' => TimberCustomerResource::printSheetAction(),
            'printlabel' => TimberCustomerResource::printLabelAction(),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
