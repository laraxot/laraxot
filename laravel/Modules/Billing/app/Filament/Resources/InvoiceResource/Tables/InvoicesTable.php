<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class InvoicesTable extends XotBaseResourceTable
{
    /**
     * @return array<int|string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'number' => TextColumn::make('number')
                ->searchable()
                ->sortable(),
            'customer_id' => TextColumn::make('customer_id')
                ->formatStateUsing(function ($state): string {
                    if (is_numeric($state)) {
                        $name = DB::table('customers')->where('id', $state)->value('legal_name');

                        return is_string($name) ? $name : 'Cliente non disponibile';
                    }

                    return 'Cliente non disponibile';
                })
                ->toggleable()
                ->searchable(),
            'issued_on' => TextColumn::make('issued_on')
                ->date()
                ->toggleable()
                ->sortable(),
            'total' => TextColumn::make('total')
                ->money('EUR')
                ->sortable(),
            'electronic_invoice_sent_at' => TextColumn::make('electronic_invoice_sent_at')
                ->dateTime()
                ->toggleable()
                ->sortable(),
            'electronic_invoice_external_id' => TextColumn::make('electronic_invoice_external_id')
                ->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<int|string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * @return array<int|string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => InvoiceResource::canView($record)),
            'edit' => EditAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => InvoiceResource::canEdit($record)),
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
}
