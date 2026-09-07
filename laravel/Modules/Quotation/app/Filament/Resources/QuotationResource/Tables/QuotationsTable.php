<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Table per QuotationResource.
 *
 * NON ridefinisce table() — ereditato da HasXotTable.php:220.
 * Override consentiti: getTableColumns, getTableFilters, getTableActions, getTableBulkActions.
 */
final class QuotationsTable extends XotBaseResourceTable
{
    protected static string $resource = QuotationResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')

                ->weight(FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'status_code' => TextColumn::make('status_code')

                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'confirmed' => 'success',
                    'sent' => 'info',
                    'rejected' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => __("quotations.statuses.{$state}"))
                ->sortable(),
            'customer_id' => TextColumn::make('customer_id')

                ->formatStateUsing(function ($state): string {
                    $legalName = DB::table('customers')->where('id', $state)->value('legal_name');

                    return is_string($legalName) && $legalName !== '' ? $legalName : 'Cliente non disponibile';
                })
                ->toggleable()
                ->searchable(),
            'total' => TextColumn::make('total')

                ->money('EUR')
                ->sortable(),
            'cost_total' => TextColumn::make('cost_total')

                ->money('EUR')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            'margin' => TextColumn::make('margin')

                ->state(fn (Quotation $record): string => QuotationResource::marginSummary($record))
                ->badge()
                ->color(fn (Quotation $record): string => QuotationResource::marginColour($record))
                ->toggleable(),
            'project' => TextColumn::make('project')

                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            'delivery_at' => TextColumn::make('delivery_at')

                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            'issued_at' => TextColumn::make('issued_at')

                ->dateTime()
                ->toggleable()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
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
