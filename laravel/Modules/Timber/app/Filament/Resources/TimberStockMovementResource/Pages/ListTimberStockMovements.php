<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberStockMovements extends XotBaseListRecords
{
    protected static string $resource = TimberStockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo movimento'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'movement_date' => Tables\Columns\TextColumn::make('movement_date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable(),
            'product.code' => Tables\Columns\TextColumn::make('product.code')
                ->label('Cod.')
                ->searchable()
                ->toggleable(),
            'product.description' => Tables\Columns\TextColumn::make('product.description')
                ->label('Prodotto')
                ->searchable()
                ->wrap(),
            'customer.business_name' => Tables\Columns\TextColumn::make('customer.business_name')
                ->label('Cliente / Fornitore')
                ->toggleable(),
            'document_reference' => Tables\Columns\TextColumn::make('document_reference')
                ->label('Causale')
                ->toggleable(),
            'loaded' => Tables\Columns\TextColumn::make('loaded')
                ->label('Caricato')
                ->getStateUsing(fn (TimberStockMovement $record): ?string => $record->signedQuantity() > 0
                    ? rtrim(rtrim(number_format(abs($record->signedQuantity()), 3, ',', '.'), '0'), ',')
                    : null)
                ->alignEnd(),
            'lot_code' => Tables\Columns\TextColumn::make('lot_code')
                ->label('Lotto/Seriale')
                ->toggleable(),
            'unloaded' => Tables\Columns\TextColumn::make('unloaded')
                ->label('Scaricato')
                ->getStateUsing(fn (TimberStockMovement $record): ?string => $record->signedQuantity() < 0
                    ? rtrim(rtrim(number_format(abs($record->signedQuantity()), 3, ',', '.'), '0'), ',')
                    : null)
                ->alignEnd(),
            'category' => Tables\Columns\TextColumn::make('category')
                ->label('Categoria')
                ->toggleable(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'direction_code' => Tables\Filters\SelectFilter::make('direction_code')
                ->label('Stato')
                ->options(TimberConfigMap::get('timber.stock_movement_directions')),
            'product_id' => Tables\Filters\SelectFilter::make('product_id')
                ->label('Prodotto')
                ->relationship('product', 'description')
                ->searchable()
                ->preload(),
            'customer_id' => Tables\Filters\SelectFilter::make('customer_id')
                ->label('Cliente / Fornitore')
                ->relationship('customer', 'business_name')
                ->searchable()
                ->preload(),
            'lot_code' => Tables\Filters\Filter::make('lot_code')
                ->schema([
                    TextInput::make('lot_code')->label('Lotto / Seriale'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    $value = trim((string) ($data['lot_code'] ?? ''));

                    return $value === ''
                        ? $query
                        : $query->where('lot_code', 'like', "%{$value}%");
                }),
            'period' => Tables\Filters\Filter::make('period')
                ->schema([
                    DatePicker::make('from')->label('Dal'),
                    DatePicker::make('until')->label('Al'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['from'] ?? null, fn (Builder $q, mixed $date): Builder => $q->whereDate('movement_date', '>=', is_string($date) ? $date : null))
                        ->when($data['until'] ?? null, fn (Builder $q, mixed $date): Builder => $q->whereDate('movement_date', '<=', is_string($date) ? $date : null));
                }),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
        ];
    }
}
