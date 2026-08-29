<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;

final class EditTimberPaymentInstallment extends XotBaseEditRecord
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'mark_paid' => TimberPaymentInstallmentResource::markPaidAction(),
            'delete' => DeleteAction::make(),
        ];
    }
}
