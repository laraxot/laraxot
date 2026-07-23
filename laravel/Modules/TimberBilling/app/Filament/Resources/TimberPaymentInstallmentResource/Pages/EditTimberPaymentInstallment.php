<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\TimberBilling\Filament\Resources\TimberPaymentInstallmentResource;

final class EditTimberPaymentInstallment extends EditRecord
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimberPaymentInstallmentResource::markPaidAction(),
            DeleteAction::make(),
        ];
    }
}
