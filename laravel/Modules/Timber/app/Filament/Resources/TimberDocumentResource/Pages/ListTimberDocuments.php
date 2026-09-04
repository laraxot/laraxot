<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Timber\Models\TimberDocument;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberDocuments extends XotBaseListRecords
{
    protected static string $resource = TimberDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo documento'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'document_type_code' => Tables\Columns\TextColumn::make('document_type_code')
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.document_types')[$state] ?? (string) $state)
                ->toggleable(),
            'number' => Tables\Columns\TextColumn::make('number')
                ->searchable()
                ->sortable(),
            'document_date' => Tables\Columns\TextColumn::make('document_date')
                ->date('d/m/Y')
                ->sortable(),
            'customer.business_name' => Tables\Columns\TextColumn::make('customer.business_name')
                ->searchable()
                ->toggleable(),
            'status_code' => Tables\Columns\TextColumn::make('status_code')
                ->badge()
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.document_statuses')[$state] ?? (string) $state),
            'total_amount' => Tables\Columns\TextColumn::make('total_amount')
                ->money('EUR')
                ->sortable(),
            'stock_posted_at' => Tables\Columns\IconColumn::make('stock_posted_at')
                ->boolean()
                ->getStateUsing(fn (TimberDocument $record): bool => $record->stock_posted_at !== null),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'document_type_code' => Tables\Filters\SelectFilter::make('document_type_code')
                ->options(TimberConfigMap::get('timber.document_types')),
            'status_code' => Tables\Filters\SelectFilter::make('status_code')
                ->options(TimberConfigMap::get('timber.document_statuses')),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'posttostock' => TimberDocumentResource::postToStockAction(),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
