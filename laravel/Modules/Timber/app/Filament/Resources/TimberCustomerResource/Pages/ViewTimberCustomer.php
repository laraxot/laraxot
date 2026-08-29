<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Timber\Filament\Resources\TimberCustomerResource;

final class ViewTimberCustomer extends ViewRecord
{
    protected static string $resource = TimberCustomerResource::class;
}
