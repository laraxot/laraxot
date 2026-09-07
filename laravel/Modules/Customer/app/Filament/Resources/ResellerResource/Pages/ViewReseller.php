<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Pages;

use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewReseller extends XotBaseViewRecord
{
    protected static string $resource = ResellerResource::class;
}
