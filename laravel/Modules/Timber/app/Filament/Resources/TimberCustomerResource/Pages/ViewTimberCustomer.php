<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberCustomer extends XotBaseViewRecord
{
    protected static string $resource = TimberCustomerResource::class;

    /** @return array<string, Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
