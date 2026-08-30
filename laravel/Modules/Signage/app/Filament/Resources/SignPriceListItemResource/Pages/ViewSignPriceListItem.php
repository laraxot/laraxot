<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;

final class ViewSignPriceListItem extends ViewRecord
{
    protected static string $resource = SignPriceListItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        TextEntry::make('code')->label(__('signage::signage.fields.code'))->weight('bold'),
                        TextEntry::make('work_center_code')->label(__('signage::signage.catalog_fields.work_center_code'))->placeholder('—')->badge(),
                        TextEntry::make('supplier_name')->label(__('signage::signage.fields.supplier_name'))->placeholder('—'),
                    ]),
                    TextEntry::make('name')->label(__('signage::signage.fields.name'))->columnSpanFull(),
                    Grid::make(3)->components([
                        TextEntry::make('unit_code')->label(__('signage::signage.fields.unit_code')),
                        TextEntry::make('unit_cost')->label(__('signage::signage.fields.unit_cost'))->money('EUR'),
                        TextEntry::make('currency')->label(__('signage::signage.fields.currency')),
                    ]),
                    TextEntry::make('notes')->label(__('signage::signage.fields.notes'))->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
