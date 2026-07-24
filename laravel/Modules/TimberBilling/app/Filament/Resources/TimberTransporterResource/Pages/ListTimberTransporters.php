<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource;

final class ListTimberTransporters extends ListRecords
{
    protected static string $resource = TimberTransporterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
