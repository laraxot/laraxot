<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;

final class ListTimberPaymentInstallments extends ListRecords
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            TimberPaymentInstallmentResource::sendRemindersAction(),
            CreateAction::make()->label('Nuova scadenza'),
        ];
    }
}
