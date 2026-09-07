<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberDocumentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberDocumentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberDocumentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberDocumentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'document_type_code' => TextEntry::make('document_type_code'),
            'number' => TextEntry::make('number'),
            'document_date' => TextEntry::make('document_date')->date(),
            'customer.business_name' => TextEntry::make('customer.business_name'),
            'status_code' => TextEntry::make('status_code'),
            'reference' => TextEntry::make('reference'),
            'stock_posted_at' => TextEntry::make('stock_posted_at')->dateTime(),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
