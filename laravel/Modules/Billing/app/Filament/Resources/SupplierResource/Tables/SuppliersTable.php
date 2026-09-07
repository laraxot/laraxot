<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SuppliersTable extends XotBaseResourceTable
{
    /**
     * SupplierResource non definisce canView()/canEdit(): l'hook di default di
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
            'legal_name' => TextColumn::make('legal_name')
                ->weight(\Filament\Support\Enums\FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'vat_number' => TextColumn::make('vat_number')
                ->searchable()
                ->toggleable(),
            'email' => TextColumn::make('email')
                ->searchable()
                ->toggleable(),
            'phone' => TextColumn::make('phone')
                ->toggleable(),
            'delivery_notes_count' => TextColumn::make('delivery_notes_count')
                ->counts('deliveryNotes')
                ->badge(),
            'invoices_count' => TextColumn::make('invoices_count')
                ->counts('invoices')
                ->badge(),
            'is_active' => IconColumn::make('is_active')
                ->boolean()
                ->sortable(),
        ];
    }

    /**
     * @return array<int|string, TernaryFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => TernaryFilter::make('is_active'),
        ];
    }

    public function getTableRecordTitleAttribute(): string
    {
        return 'legal_name';
    }
}
