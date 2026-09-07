<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class CustomersTable extends XotBaseResourceTable
{
    protected static string $resource = CustomerResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'legal_name' => TextColumn::make('legal_name')
                ->label(__('customers.fields.legal_name'))
                ->weight(FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'email' => TextColumn::make('email')
                ->label(__('customers.fields.email'))
                ->icon('heroicon-m-envelope')
                ->copyable()
                ->toggleable()
                ->searchable(),
            'phone' => TextColumn::make('phone')
                ->label(__('customers.fields.phone'))
                ->icon('heroicon-m-phone')
                ->toggleable(),
            'city' => TextColumn::make('city')
                ->label(__('customers.fields.city'))
                ->toggleable()
                ->sortable(),
            'is_active' => IconColumn::make('is_active')
                ->label(__('customers.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, TernaryFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => TernaryFilter::make('is_active')
                ->label(__('customers.fields.is_active')),
        ];
    }

    /**
     * Il `table()` inline originale non aveva `->recordActions()` esplicito:
     * View/Edit riga restano affidate al default di
     * `HasXotTable::getTableActions()`, che li popola perché
     * `CustomerResource` definisce `canView()`/`canEdit()`. Questo override
     * preserva 1:1 il contenuto del `->toolbarActions([...])` originale
     * (Vedi/Modifica/Elimina/Ripristina/Elimina definitivamente).
     *
     * @return array<string, ViewAction|EditAction|DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'delete' => DeleteBulkAction::make(),
            'restore' => RestoreBulkAction::make(),
            'forceDelete' => ForceDeleteBulkAction::make(),
        ];
    }
}
