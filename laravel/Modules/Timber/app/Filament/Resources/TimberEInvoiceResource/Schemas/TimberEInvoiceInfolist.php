<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberEInvoiceInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberEInvoiceResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'invoice.number' => TextEntry::make('invoice.number'),
            'file_name' => TextEntry::make('file_name'),
            'sdi_status' => TextEntry::make('sdi_status'),
            'sdi_recipient_code' => TextEntry::make('sdi_recipient_code'),
            'sdi_message_id' => TextEntry::make('sdi_message_id'),
            'transmitted_at' => TextEntry::make('transmitted_at')->dateTime(),
            'delivered_at' => TextEntry::make('delivered_at')->dateTime(),
            'failed_at' => TextEntry::make('failed_at')->dateTime(),
            'failure_reason' => TextEntry::make('failure_reason'),
        ];
    }
}
