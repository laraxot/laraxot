<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Pages;

use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateReseller extends XotBaseCreateRecord
{
    protected static string $resource = ResellerResource::class;
}
