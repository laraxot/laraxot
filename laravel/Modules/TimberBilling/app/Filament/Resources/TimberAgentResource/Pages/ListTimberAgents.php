<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberAgentResource;

final class ListTimberAgents extends ListRecords
{
    protected static string $resource = TimberAgentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
