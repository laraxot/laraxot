<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\TimberBilling\Filament\Resources\TimberPaymentInstallmentResource;

final class CreateTimberPaymentInstallment extends CreateRecord
{
    protected static string $resource = TimberPaymentInstallmentResource::class;
}
