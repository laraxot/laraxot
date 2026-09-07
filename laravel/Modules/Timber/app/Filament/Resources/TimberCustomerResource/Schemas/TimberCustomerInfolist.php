<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberCustomerInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberCustomerResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()->columnSpanFull()->components([
                Grid::make(2)->components([
                    'code' => TextEntry::make('code'),
                    'business_name' => TextEntry::make('business_name'),
                    'tax_code' => TextEntry::make('tax_code'),
                    'vat_number' => TextEntry::make('vat_number'),
                    'city' => TextEntry::make('city'),
                    'province' => TextEntry::make('province'),
                    'is_active' => TextEntry::make('is_active'),
                ]),
            ]),
            'contacts' => Section::make('Contatti')->components([
                TextEntry::make('contact_person'),
                TextEntry::make('phone'),
                TextEntry::make('email'),
            ]),
            'einvoice' => Section::make('Fattura elettronica')->components([
                TextEntry::make('sdi_recipient_code'),
                TextEntry::make('pec'),
            ]),
        ];
    }
}
