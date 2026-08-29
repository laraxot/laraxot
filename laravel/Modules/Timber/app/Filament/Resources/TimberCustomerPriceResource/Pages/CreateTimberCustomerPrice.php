<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource;

final class CreateTimberCustomerPrice extends XotBaseCreateRecord
{
    protected static string $resource = TimberCustomerPriceResource::class;
}
