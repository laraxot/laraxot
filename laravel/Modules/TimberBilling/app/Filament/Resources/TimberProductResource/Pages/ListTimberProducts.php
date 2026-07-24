<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberProductResource;

final class ListTimberProducts extends ListRecords
{
    protected static string $resource = TimberProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
