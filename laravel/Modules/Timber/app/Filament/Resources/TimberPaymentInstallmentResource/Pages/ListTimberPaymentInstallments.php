<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;

final class ListTimberPaymentInstallments extends XotBaseListRecords
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'send_reminders' => TimberPaymentInstallmentResource::sendRemindersAction(),
            'create' => CreateAction::make()->label('Nuova scadenza'),
        ];
    }
}
