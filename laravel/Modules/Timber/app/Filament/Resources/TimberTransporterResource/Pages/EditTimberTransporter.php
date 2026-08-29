<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;

use Modules\Timber\Filament\Resources\TimberTransporterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditTimberTransporter extends XotBaseEditRecord
{
    protected static string $resource = TimberTransporterResource::class;
}
