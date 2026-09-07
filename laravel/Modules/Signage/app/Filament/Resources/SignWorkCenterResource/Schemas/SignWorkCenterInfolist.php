<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignWorkCenterResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Signage\Filament\Resources\SignWorkCenterResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SignWorkCenterInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SignWorkCenterResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('code'),
                        TextEntry::make('name'),
                    ]),
                    Grid::make(3)->components([
                        TextEntry::make('default_markup_percent')
                            ->suffix('%')
                            ->placeholder('—'),
                        TextEntry::make('sort_order'),
                        TextEntry::make('context_code'),
                    ]),
                    Grid::make(2)->components([
                        IconEntry::make('is_internal')->boolean(),
                        IconEntry::make('is_active')->boolean(),
                    ]),
                ]),
        ];
    }
}
