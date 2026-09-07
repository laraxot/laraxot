<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\DailyNoteResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Intervention\Filament\Resources\DailyNoteResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class DailyNoteInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = DailyNoteResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        /** @var view-string $dailyNoteView */
        $dailyNoteView = 'intervention::filament.infolists.entries.daily-note-display';

        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('user.name'),
                        TextEntry::make('note_date')

                            ->date(),
                    ]),
                    ViewEntry::make('daily_note_display')
                        ->hiddenLabel()
                        ->view($dailyNoteView)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
