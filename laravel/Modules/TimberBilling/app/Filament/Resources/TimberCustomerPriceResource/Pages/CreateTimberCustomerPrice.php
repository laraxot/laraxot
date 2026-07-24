<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\TimberBilling\Filament\Resources\TimberCustomerPriceResource;

final class CreateTimberCustomerPrice extends CreateRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;
}
