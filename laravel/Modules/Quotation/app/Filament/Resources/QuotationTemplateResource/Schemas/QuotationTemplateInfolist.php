<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class QuotationTemplateInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = QuotationTemplateResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'title' => Section::make()
                ->columnSpanFull()
                ->components([
                    'title' => TextEntry::make('title'),
                    'notes' => TextEntry::make('notes'),
                ])
                ->columns(2),
        ];
    }
}
