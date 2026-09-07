<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberCustomerPriceInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberCustomerPriceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'customer.business_name' => TextEntry::make('customer.business_name'),
            'product.description' => TextEntry::make('product.description'),
            'net_price' => TextEntry::make('net_price'),
        ];
    }
}
