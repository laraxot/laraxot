<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;
use Modules\Billing\Filament\Resources\DeliveryNoteResource\RelationManagers\LinesRelationManager;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\Supplier;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * DDT ricevuti: quello che il fornitore ha davvero consegnato, cioe' il metro
 * su cui si misura quello che poi fattura.
 */
final class DeliveryNoteResource extends XotBaseResource
{
    protected static ?string $model = DeliveryNote::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getNavigationGroup(): string
    {
        return __('suppliers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('suppliers.delivery_note');
    }

    public static function getPluralModelLabel(): string
    {
        return __('suppliers.delivery_notes');
    }

    /**
     * @return array<int, string>
     */
    public static function supplierOptions(): array
    {
        return Supplier::query()
            ->orderBy('legal_name')
            ->get(['id', 'legal_name'])
            ->mapWithKeys(static fn (Supplier $supplier): array => [$supplier->id => $supplier->legal_name])
            ->all();
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('supplier_id')
                            ->label(__('suppliers.fields.supplier'))
                            ->options(fn (): array => self::supplierOptions())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('number')
                            ->label(__('suppliers.fields.number'))
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('received_on')
                            ->label(__('suppliers.fields.received_on'))
                            ->displayFormat('d/m/Y'),
                        Select::make('work_order_id')
                            ->label(__('suppliers.fields.work_order'))
                            ->options(fn (): array => WorkOrder::query()
                                ->orderByDesc('id')
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(static fn (WorkOrder $workOrder): array => [
                                    $workOrder->id => (string) ($workOrder->code ?? $workOrder->title ?? $workOrder->id),
                                ])
                                ->all())
                            ->searchable()
                            ->preload(),
                    ]),

                    Textarea::make('notes')
                        ->label(__('suppliers.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('suppliers.fields.number'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.legal_name')
                    ->label(__('suppliers.fields.supplier'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('received_on')
                    ->label(__('suppliers.fields.received_on'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('lines_count')
                    ->label(__('suppliers.fields.lines_count'))
                    ->counts('lines')
                    ->badge(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('suppliers.fields.notes'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('received_on', 'desc')
            ->filters([
                SelectFilter::make('supplier_id')
                    ->label(__('suppliers.fields.supplier'))
                    ->options(fn (): array => self::supplierOptions())
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveryNotes::route('/'),
            'create' => Pages\CreateDeliveryNote::route('/create'),
            'view' => Pages\ViewDeliveryNote::route('/{record}'),
            'edit' => Pages\EditDeliveryNote::route('/{record}/edit'),
        ];
    }
}
