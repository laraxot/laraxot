<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

/**
 * Schema infolist per fattura fornitore.
 */
final class SupplierInvoiceInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'number' => TextEntry::make('number'),
            'supplier' => TextEntry::make('supplier.legal_name'),
            'issued_on' => TextEntry::make('issued_on')->date('d/m/Y'),
            'total' => TextEntry::make('total')->money('EUR'),
            'status_code' => TextEntry::make('status_code')
                ->formatStateUsing(fn (string $state): string => __("billing::suppliers.statuses.{$state}"))
                ->color(fn (string $state): string => self::statusColor($state)),
            'notes' => TextEntry::make('notes'),
            'lines_count' => TextEntry::make('lines_count'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }

    private static function statusColor(string $status): string
    {
        return match ($status) {
            'reconciled' => 'success',
            'disputed' => 'danger',
            default => 'warning',
        };
    }
}
