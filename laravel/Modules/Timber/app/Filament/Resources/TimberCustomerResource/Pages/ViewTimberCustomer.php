<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Timber\Filament\Resources\TimberCustomerResource;

final class ViewTimberCustomer extends XotBaseViewRecord
{
    protected static string $resource = TimberCustomerResource::class;

    /** @return array<string, \Filament\Schemas\Components\Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
