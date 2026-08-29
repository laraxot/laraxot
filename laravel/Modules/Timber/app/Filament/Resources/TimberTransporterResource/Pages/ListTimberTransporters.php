<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberTransporterResource;

final class ListTimberTransporters extends XotBaseListRecords
{
    protected static string $resource = TimberTransporterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
