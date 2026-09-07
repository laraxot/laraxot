<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberSupplierResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberSupplierResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberSupplierInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberSupplierResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'legal_name' => TextEntry::make('legal_name'),
            'vat_number' => TextEntry::make('vat_number'),
            'tax_code' => TextEntry::make('tax_code'),
            'email' => TextEntry::make('email'),
            'phone' => TextEntry::make('phone'),
            'certifications' => TextEntry::make('certifications'),
            'city' => TextEntry::make('city'),
            'province' => TextEntry::make('province'),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
