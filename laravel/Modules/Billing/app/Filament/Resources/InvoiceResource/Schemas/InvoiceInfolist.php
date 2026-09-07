<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class InvoiceInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'number' => TextEntry::make('number'),
            'customer_id' => TextEntry::make('customer_id'),
            'issued_on' => TextEntry::make('issued_on')->date(),
            'total' => TextEntry::make('total')->money('EUR'),
            'work_order_id' => TextEntry::make('work_order_id'),
            'notes' => TextEntry::make('notes'),
            'electronic_invoice_external_id' => TextEntry::make('electronic_invoice_external_id'),
            'electronic_invoice_sent_at' => TextEntry::make('electronic_invoice_sent_at')->dateTime(),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
