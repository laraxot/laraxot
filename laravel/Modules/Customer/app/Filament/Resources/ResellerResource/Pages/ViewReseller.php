<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Pages;

use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Component;
use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewReseller extends XotBaseViewRecord
{
    protected static string $resource = ResellerResource::class;

    /**
     * @return array<string, Component|Entry>
     */
    protected function getInfolistSchema(): array
    {
        return ResellerResource::getInfolistSchema();
    }
}
