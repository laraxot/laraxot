<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalPartEstimateResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Modules\MetalWork\Filament\Resources\MetalPartEstimateResource;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class MetalPartEstimatesTable extends XotBaseResourceTable
{
    protected static string $resource = MetalPartEstimateResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'part_code' => TextColumn::make('part_code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable()->wrap(),
            'quantity' => TextColumn::make('quantity')->numeric(),
            'gross_weight_kg' => TextColumn::make('gross_weight_kg')
                ->numeric(2)
                ->suffix(' kg'),
            'total_cost' => TextColumn::make('total_cost')->numeric(2)->suffix(' EUR'),
            'sale_price' => TextColumn::make('sale_price')->numeric(2)->suffix(' EUR'),
            'rawMaterial.code' => TextColumn::make('rawMaterial.code')->placeholder('—'),
            'quotation.code' => TextColumn::make('quotation.code')
                ->placeholder('—')
                ->url(fn (MetalPartEstimate $record): ?string => $record->quotation_id === null
                    ? null
                    : QuotationResource::getUrl('edit', ['record' => $record->quotation_id], panel: 'admin')),
            'created_at' => TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
        ];
    }

    /**
     * @return array<string, DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }
}
