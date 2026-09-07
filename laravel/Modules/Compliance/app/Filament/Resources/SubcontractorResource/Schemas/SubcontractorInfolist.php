<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\SubcontractorResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\SubcontractorResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SubcontractorInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = SubcontractorResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'business_name' => TextEntry::make('business_name'),
            'status' => TextEntry::make('status'),
            'vat_number' => TextEntry::make('vat_number'),
            'tax_code' => TextEntry::make('tax_code'),
            'contact_name' => TextEntry::make('contact_name'),
            'email' => TextEntry::make('email'),
            'phone' => TextEntry::make('phone'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
