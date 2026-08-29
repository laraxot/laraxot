<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberPaymentInstallment extends XotBaseCreateRecord
{
    protected static string $resource = TimberPaymentInstallmentResource::class;
}
