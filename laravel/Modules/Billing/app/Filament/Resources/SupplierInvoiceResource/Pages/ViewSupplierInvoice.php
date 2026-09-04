<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSupplierInvoice extends XotBaseViewRecord
{
    protected static string $resource = SupplierInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'reconcile' => SupplierInvoiceResource::reconcileAction(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'number' => TextEntry::make('number'),
            'supplier.legal_name' => TextEntry::make('supplier.legal_name'),
            'issued_on' => TextEntry::make('issued_on'),
            'total' => TextEntry::make('total'),
            'status_code' => TextEntry::make('status_code'),
            'lines_count' => TextEntry::make('lines_count'),
        ];
    }
}
