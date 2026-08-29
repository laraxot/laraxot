<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Timber\Filament\Resources\TimberTransporterResource;

final class EditTimberTransporter extends EditRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
