<?php

declare(strict_types=1);

namespace Modules\Cms\Filament\Resources\SectionResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Cms\Models\Section as SectionModel;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class SectionInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'slug' => TextEntry::make('slug'),
            'blocks' => TextEntry::make('blocks')->markdown(),
            'preview' => Section::make('Anteprima')->schema([
                'preview' => ViewEntry::make('preview')
                    ->view('cms::sections.preview', static fn (SectionModel $record): array => [
                        'section' => $record,
                    ]),
            ]),
        ];
    }
}
