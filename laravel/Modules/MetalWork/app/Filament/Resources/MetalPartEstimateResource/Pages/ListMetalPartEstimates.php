<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\MetalWork\Filament\Resources\MetalPartEstimateResource;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListMetalPartEstimates extends XotBaseListRecords
{
    protected static string $resource = MetalPartEstimateResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'part_code' => Tables\Columns\TextColumn::make('part_code')->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->searchable()->wrap(),
            'quantity' => Tables\Columns\TextColumn::make('quantity')->numeric(),
            'gross_weight_kg' => Tables\Columns\TextColumn::make('gross_weight_kg')
                ->numeric(2)
                ->suffix(' kg'),
            'total_cost' => Tables\Columns\TextColumn::make('total_cost')->numeric(2)->suffix(' €'),
            'sale_price' => Tables\Columns\TextColumn::make('sale_price')->numeric(2)->suffix(' €'),
            'rawMaterial.code' => Tables\Columns\TextColumn::make('rawMaterial.code')->placeholder('—'),
            'quotation.code' => Tables\Columns\TextColumn::make('quotation.code')
                ->placeholder('—')
                ->url(fn (MetalPartEstimate $record): ?string => $record->quotation_id === null
                    ? null
                    : QuotationResource::getUrl('edit', ['record' => $record->quotation_id], panel: 'admin')),
            'created_at' => Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
