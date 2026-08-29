<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;

final class CreateTimberPaymentInstallment extends CreateRecord
{
    protected static string $resource = TimberPaymentInstallmentResource::class;
}
