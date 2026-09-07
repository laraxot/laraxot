<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class SupplierInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'legal_name' => TextEntry::make('legal_name'),
            'vat_number' => TextEntry::make('vat_number'),
            'email' => TextEntry::make('email'),
            'phone' => TextEntry::make('phone'),
            'is_active' => IconEntry::make('is_active')->boolean(),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
