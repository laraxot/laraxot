<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSignPriceListItem extends XotBaseViewRecord
{
    protected static string $resource = SignPriceListItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'summary' => Section::make()
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
        ];
    }
}
