<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class PublicProcurementTenderSourcesTable extends XotBaseResourceTable
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->label(__('public_procurement.fields.name'))->searchable()->sortable(),
            'source_type' => TextColumn::make('source_type')->label(__('public_procurement.fields.source_type'))->badge()->sortable(),
            'is_active' => IconColumn::make('is_active')->label(__('public_procurement.fields.is_active'))->boolean(),
            'url' => TextColumn::make('url')->label(__('public_procurement.fields.url'))->limit(50),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
