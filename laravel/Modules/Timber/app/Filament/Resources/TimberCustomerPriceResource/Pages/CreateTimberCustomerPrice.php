<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberCustomerPrice extends XotBaseCreateRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;
}
