<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class WorkOrderStatusesTable extends XotBaseResourceTable
{
    protected static string $resource = WorkOrderStatusResource::class;

    /**
     * @return array<int|string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')
                ->searchable()
                ->sortable(),
            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable(),
            'color' => TextColumn::make('color')
                ->badge(),
            'sort_order' => TextColumn::make('sort_order')
                ->numeric()
                ->sortable(),
            'is_terminal' => TextColumn::make('is_terminal')
                ->badge(),
        ];
    }
}
