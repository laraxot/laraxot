<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;

final class CreateTimberCustomerPrice extends CreateRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;
}
