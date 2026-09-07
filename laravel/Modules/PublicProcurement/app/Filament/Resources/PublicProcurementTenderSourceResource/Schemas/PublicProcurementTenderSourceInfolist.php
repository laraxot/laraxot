<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderSourceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class PublicProcurementTenderSourceInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = PublicProcurementTenderSourceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'source_type' => TextEntry::make('source_type'),
            'url' => TextEntry::make('url'),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
