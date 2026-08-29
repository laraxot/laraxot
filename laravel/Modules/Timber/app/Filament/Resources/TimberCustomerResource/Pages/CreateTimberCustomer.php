<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberCustomerResource;

final class CreateTimberCustomer extends CreateRecord
{
    protected static string $resource = TimberCustomerResource::class;
}
