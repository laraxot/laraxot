<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberPaymentInstallmentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'invoice.number' => TextEntry::make('invoice.number'),
            'customer.business_name' => TextEntry::make('customer.business_name'),
            'direction' => TextEntry::make('direction'),
            'due_date' => TextEntry::make('due_date')->date(),
            'amount' => TextEntry::make('amount')->money('EUR'),
            'status_code' => TextEntry::make('status_code'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
