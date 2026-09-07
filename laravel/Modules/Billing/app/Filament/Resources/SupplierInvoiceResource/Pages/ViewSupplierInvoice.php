<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Filament\Actions\EditAction;
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
}
