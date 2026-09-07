<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class InterventionInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = InterventionResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        'work_order_id' => TextEntry::make('work_order_id'),
                        'customer_display' => TextEntry::make('customer_display')
                            ->state(fn ($record): string => $record->workOrder->customer->legal_name ?? $record->customer->legal_name ?? '—'),
                        'performed_by_user_id' => TextEntry::make('performedBy.name'),
                        'scheduled_at' => TextEntry::make('scheduled_at')->dateTime(),
                        'started_at' => TextEntry::make('started_at')->dateTime(),
                        'ended_at' => TextEntry::make('ended_at')->dateTime(),
                        'amount' => TextEntry::make('amount')->money('EUR'),
                        'callout_fee' => TextEntry::make('callout_fee')->money('EUR'),
                        'materials_total' => TextEntry::make('materials_total')->money('EUR'),
                        'total_amount' => TextEntry::make('total_amount')->money('EUR'),
                        'work_minutes' => TextEntry::make('work_minutes')->numeric(),
                        'travel_km' => TextEntry::make('travel_km')->numeric(),
                        'outcome_code' => TextEntry::make('outcome_code'),
                    ]),
                    'notes' => TextEntry::make('notes')->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
