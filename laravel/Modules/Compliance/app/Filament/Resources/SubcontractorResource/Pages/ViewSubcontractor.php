<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSubcontractor extends XotBaseViewRecord
{
    protected static string $resource = SubcontractorResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'business_name' => TextEntry::make('business_name'),
            'vat_number' => TextEntry::make('vat_number'),
            'contact_name' => TextEntry::make('contact_name'),
            'email' => TextEntry::make('email'),
            'status' => TextEntry::make('status'),
        ];
    }
}
