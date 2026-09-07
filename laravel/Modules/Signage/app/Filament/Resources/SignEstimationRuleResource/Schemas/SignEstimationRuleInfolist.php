<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignEstimationRuleResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SignEstimationRuleInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SignEstimationRuleResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(3)->components([
                        TextEntry::make('role_code')
                            ->formatStateUsing(fn (string $state): string => __('signage::signage.roles.'.$state)),
                        TextEntry::make('unit_code'),
                        TextEntry::make('strategy_code')
                            ->formatStateUsing(fn (string $state): string => __('signage::signage.strategies.'.$state)),
                    ]),
                    Grid::make(3)->components([
                        TextEntry::make('factor'),
                        TextEntry::make('base_value'),
                        TextEntry::make('min_per_piece')->placeholder('—'),
                    ]),
                    TextEntry::make('notes')->placeholder('—'),
                    IconEntry::make('is_active')->boolean(),
                ]),
        ];
    }
}
