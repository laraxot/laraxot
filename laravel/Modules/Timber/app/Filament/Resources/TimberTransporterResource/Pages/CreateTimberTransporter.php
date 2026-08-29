<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Timber\Filament\Resources\TimberTransporterResource;

final class CreateTimberTransporter extends CreateRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
