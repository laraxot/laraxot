<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberInvoiceInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberInvoiceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'kind' => TextEntry::make('kind'),
            'number' => TextEntry::make('number'),
            'invoice_date' => TextEntry::make('invoice_date')->date(),
            'customer.business_name' => TextEntry::make('customer.business_name'),
            'total_amount' => TextEntry::make('total_amount')->money('EUR'),
            'issued_at' => TextEntry::make('issued_at')->dateTime(),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
