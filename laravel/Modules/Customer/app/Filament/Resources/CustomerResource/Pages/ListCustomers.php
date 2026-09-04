<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListCustomers extends XotBaseListRecords
{
    protected static string $resource = CustomerResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'legal_name' => Tables\Columns\TextColumn::make('legal_name')
                ->label(__('customers.fields.legal_name'))
                ->weight(FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'email' => Tables\Columns\TextColumn::make('email')
                ->label(__('customers.fields.email'))
                ->icon('heroicon-m-envelope')
                ->copyable()
                ->toggleable()
                ->searchable(),
            'phone' => Tables\Columns\TextColumn::make('phone')
                ->label(__('customers.fields.phone'))
                ->icon('heroicon-m-phone')
                ->toggleable(),
            'city' => Tables\Columns\TextColumn::make('city')
                ->label(__('customers.fields.city'))
                ->toggleable()
                ->sortable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label(__('customers.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => Tables\Filters\TernaryFilter::make('is_active')
                ->label(__('customers.fields.is_active')),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
            'restoreBulk' => RestoreBulkAction::make(),
            'forceDeleteBulk' => ForceDeleteBulkAction::make(),
        ];
    }
}
