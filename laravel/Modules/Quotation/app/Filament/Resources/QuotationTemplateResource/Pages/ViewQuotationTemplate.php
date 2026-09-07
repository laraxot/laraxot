<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewQuotationTemplate extends XotBaseViewRecord
{
    protected static string $resource = QuotationTemplateResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'title' => TextEntry::make('title'),
            'updated_at' => TextEntry::make('updated_at'),
        ];
    }
}
