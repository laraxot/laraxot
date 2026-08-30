<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Quotation\Filament\Resources\QuotationResource;

/**
 * Saved estimates, with the rows they were computed from. This is what makes a
 * price defensible months later, once the material has moved: the rows hold the
 * cost of the day the quotation went out.
 */
final class MetalPartEstimateResource extends GatedXotBaseResource
{
    protected static ?string $model = MetalPartEstimate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.part_estimate');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.part_estimates');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * I preventivi nascono dal calcolo sul disegno: qui si consultano e si
     * trasformano in offerta, non si scrivono a mano.
     *
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('part_code')->label(__('metalwork::metalwork.fields.part_code'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('metalwork::metalwork.fields.name'))->searchable()->wrap(),
                Tables\Columns\TextColumn::make('quantity')->label(__('metalwork::metalwork.fields.quantity'))->numeric(),
                Tables\Columns\TextColumn::make('gross_weight_kg')
                    ->label(__('metalwork::metalwork.fields.gross_weight_kg'))
                    ->numeric(2)
                    ->suffix(' kg'),
                Tables\Columns\TextColumn::make('total_cost')->label(__('metalwork::metalwork.fields.total_cost'))->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('sale_price')->label(__('metalwork::metalwork.fields.sale_price'))->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('rawMaterial.code')->label(__('metalwork::metalwork.raw_material'))->placeholder('—'),
                Tables\Columns\TextColumn::make('quotation.code')
                    ->label(__('metalwork::metalwork.fields.quotation'))
                    ->placeholder('—')
                    ->url(fn (MetalPartEstimate $record): ?string => $record->quotation_id === null
                        ? null
                        : QuotationResource::getUrl('edit', ['record' => $record->quotation_id], panel: 'admin')),
                Tables\Columns\TextColumn::make('created_at')->label(__('metalwork::metalwork.fields.created_at'))->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MetalPartEstimateResource\RelationManagers\LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalPartEstimateResource\Pages\ListMetalPartEstimates::route('/'),
        ];
    }
}
