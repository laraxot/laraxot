<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class QuotationInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = QuotationResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'code' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        'code' => TextEntry::make('code'),
                        'status_code' => TextEntry::make('status_code')
                            ->formatStateUsing(fn (string $state): string => __("quotations.statuses.{$state}")),
                        'project' => TextEntry::make('project'),
                    ]),
                    Grid::make(2)->components([
                        'customer' => TextEntry::make('customer.legal_name'),
                        'work_order' => TextEntry::make('workOrder.code'),
                    ]),
                    'title' => TextEntry::make('title'),
                    Grid::make(2)->components([
                        'issued_at' => TextEntry::make('issued_at')
                            ->dateTime(),
                        'confirmed_at' => TextEntry::make('confirmed_at')
                            ->dateTime(),
                    ]),
                    Grid::make(2)->components([
                        'delivery_at' => TextEntry::make('delivery_at')
                            ->dateTime(),
                        'lead_time_days' => TextEntry::make('lead_time_days'),
                    ]),
                ])
                ->columns(3),
            'financials' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        'subtotal' => TextEntry::make('subtotal')
                            ->money('EUR'),
                        'discount_amount' => TextEntry::make('discount_amount')
                            ->money('EUR'),
                        'taxable_amount' => TextEntry::make('taxable_amount')
                            ->money('EUR'),
                    ]),
                    Grid::make(2)->components([
                        'tax_amount' => TextEntry::make('tax_amount')
                            ->money('EUR'),
                        'total' => TextEntry::make('total')
                            ->money('EUR'),
                    ]),
                    Grid::make(2)->components([
                        'cost_total' => TextEntry::make('cost_total')
                            ->money('EUR'),
                        'margin' => TextEntry::make('margin')
                            ->state(fn ($record) => QuotationResource::marginSummary($record)),
                    ]),
                ])
                ->columns(3),
            'notes' => Section::make()
                ->columnSpanFull()
                ->components([
                    'notes' => TextEntry::make('notes'),
                ]),
        ];
    }
}
